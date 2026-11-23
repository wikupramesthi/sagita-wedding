import { Router } from "express";
import rateLimit from "express-rate-limit";
import { clearSessionCache } from "../middleware/locals.js";
import { getSessionUser, logoutSession } from "../services/authService.js";
import {
	getContactCaptcha,
	sendBooking,
	sendContact,
} from "../services/contactService.js";
import {
	createSitaLead,
	fetchSitaAnalytics,
	fetchSitaSynonyms,
	forwardSitaTelemetry,
	getSitaAnalytics,
	recordSitaTelemetry,
} from "../services/sitaAnalytics.js";
import { answerSitaQuestion } from "../services/sitaService.js";
import type { Request, Response } from "../types/index.js";
import { toApiError } from "../utils/errors.js";
import { logger } from "../utils/logger.js";
import {
	validateBooking,
	validateContact,
	validateNewsletter,
	validateSitaChat,
	validateSitaLead,
} from "../utils/validate.js";

const router = Router();

const sitaChatLimiter = rateLimit({
	windowMs: 60 * 1000,
	limit: 15,
	standardHeaders: "draft-7",
	legacyHeaders: false,
	message: {
		success: false,
		message: "Too many chat messages, please try again shortly.",
	},
});

const telemetryLimiter = rateLimit({
	windowMs: 60 * 1000,
	limit: 40,
	standardHeaders: "draft-7",
	legacyHeaders: false,
	message: {
		success: false,
		message: "Too many telemetry events, please try again shortly.",
	},
});

const analyticsLimiter = rateLimit({
	windowMs: 60 * 1000,
	limit: 30,
	standardHeaders: "draft-7",
	legacyHeaders: false,
});

const sitaKnowledgeLimiter = rateLimit({
	windowMs: 60 * 1000,
	limit: 30,
	standardHeaders: "draft-7",
	legacyHeaders: false,
});

const sitaLeadLimiter = rateLimit({
	windowMs: 60 * 1000,
	limit: 10,
	standardHeaders: "draft-7",
	legacyHeaders: false,
	message: {
		success: false,
		message: "Too many contact requests, please try again shortly.",
	},
});

type SitaTopic = "packages" | "venues" | "vendors";

export type TelemetryPayload =
	| {
			kind: "error";
			name: string;
			path: string;
			message: string;
	  }
	| {
			kind: "vital";
			name: string;
			path: string;
			value: number;
	  }
	| {
			kind: "event";
			name: "sita.no_match";
			path: "/";
			topic?: SitaTopic;
			queryHash?: string;
	  }
	| {
			kind: "event";
			name: "sita.answer";
			path: "/";
			topic?: SitaTopic;
			confidence: number;
			fallback: boolean;
			clarify: boolean;
			responseMs: number;
			turnId?: string;
	  }
	| {
			kind: "event";
			name: "sita.feedback";
			path: "/";
			helpful: boolean;
			turnId?: string;
			topic?: SitaTopic;
	  };

function cleanSitaTopic(value: unknown): SitaTopic | undefined {
	return value === "packages" || value === "venues" || value === "vendors"
		? value
		: undefined;
}

function cleanTurnId(value: unknown): string | undefined {
	return typeof value === "string"
		? value.replace(/[^a-zA-Z0-9_-]/g, "").slice(0, 80) || undefined
		: undefined;
}

export function parseTelemetry(value: unknown): TelemetryPayload | null {
	if (!value || typeof value !== "object" || Array.isArray(value)) return null;
	const item = value as Record<string, unknown>;
	if (item.kind === "event") {
		const topic = cleanSitaTopic(item.topic);
		const turnId = cleanTurnId(item.turnId);
		if (item.name === "sita.no_match") {
			const queryHash =
				typeof item.queryHash === "string" &&
				/^[a-z0-9]{1,32}$/.test(item.queryHash)
					? item.queryHash
					: undefined;
			return {
				kind: "event",
				name: "sita.no_match",
				path: "/",
				...(topic ? { topic } : {}),
				...(queryHash ? { queryHash } : {}),
			};
		}
		if (item.name === "sita.answer") {
			const confidence = Number(item.confidence);
			const responseMs = Number(item.responseMs);
			if (
				!Number.isFinite(confidence) ||
				confidence < 0 ||
				confidence > 1 ||
				!Number.isFinite(responseMs) ||
				responseMs < 0 ||
				responseMs > 600000 ||
				typeof item.fallback !== "boolean" ||
				typeof item.clarify !== "boolean"
			) {
				return null;
			}
			return {
				kind: "event",
				name: "sita.answer",
				path: "/",
				confidence,
				fallback: item.fallback,
				clarify: item.clarify,
				responseMs,
				...(topic ? { topic } : {}),
				...(turnId ? { turnId } : {}),
			};
		}
		if (item.name === "sita.feedback" && typeof item.helpful === "boolean") {
			return {
				kind: "event",
				name: "sita.feedback",
				path: "/",
				helpful: item.helpful,
				...(turnId ? { turnId } : {}),
				...(topic ? { topic } : {}),
			};
		}
		return null;
	}
	if (item.kind !== "error" && item.kind !== "vital") return null;

	const name =
		typeof item.name === "string"
			? item.name.replace(/[^a-zA-Z0-9_.:-]/g, "").slice(0, 80)
			: "";
	const path =
		typeof item.path === "string" && item.path.startsWith("/")
			? item.path.slice(0, 200)
			: "";
	if (!name || !path) return null;

	if (item.kind === "vital") {
		const metric = Number(item.value);
		if (!Number.isFinite(metric) || metric < 0 || metric > 600000) {
			return null;
		}
		return {
			kind: "vital",
			name,
			path,
			value: Math.round(metric * 100) / 100,
		};
	}

	const message =
		typeof item.message === "string"
			? item.message
					.replace(/\S+@\S+/g, "[email]")
					.replace(/[A-Za-z0-9_-]{32,}/g, "[token]")
					.slice(0, 200)
			: "";
	return { kind: "error", name, path, message };
}

/**
 * Modul class/schedules sudah dihapus di backend. Endpoint lama sengaja
 * membalas 410 supaya klien lama tidak menerima 404/502 yang menyesatkan.
 */
router.all(
	["/classes", "/class-schedules", "/batch/schedules"],
	(_req: Request, res: Response) => {
		res.set("Cache-Control", "no-store");
		res.status(410).json({
			success: false,
			message: "Class and schedule endpoints are no longer available.",
		});
	},
);

router.post("/telemetry", telemetryLimiter, (req: Request, res: Response) => {
	const payload = parseTelemetry(req.body);
	if (!payload) {
		return res.status(400).json({
			success: false,
			message: "Invalid telemetry payload.",
		});
	}
	if (payload.kind === "error") logger.error("client.error", payload);
	else if (payload.kind === "vital") logger.info("client.vital", payload);
	else {
		recordSitaTelemetry(payload);
		void forwardSitaTelemetry(payload).catch(() => {});
		logger.info("client.event", payload);
	}
	res.status(204).end();
});

router.get(
	"/sita/analytics",
	analyticsLimiter,
	async (req: Request, res: Response) => {
		const user = await getSessionUser(req.headers?.cookie);
		const roles = user?.roles ?? [];
		if (!roles.some((role) => role === "admin" || role === "super-admin")) {
			res.status(403).json({
				success: false,
				message: "Sita analytics is available for administrators only.",
			});
			return;
		}
		res.set("Cache-Control", "no-store");
		const analytics = await fetchSitaAnalytics().catch(() => null);
		res.json(analytics ?? { success: true, ...getSitaAnalytics() });
	},
);

router.get(
	"/sita/knowledge",
	sitaKnowledgeLimiter,
	async (_req: Request, res: Response) => {
		const synonyms = await fetchSitaSynonyms().catch(() => []);
		res.set("Cache-Control", "public, max-age=300");
		res.json({ success: true, data: synonyms });
	},
);

router.post(
	"/sita/leads",
	sitaLeadLimiter,
	async (req: Request, res: Response) => {
		try {
			const lead = validateSitaLead(req.body);
			if (lead.website) {
				res.status(400).json({
					success: false,
					message: "Invalid lead payload.",
				});
				return;
			}
			const uuid = await createSitaLead(lead);
			if (!uuid) {
				res.status(502).json({
					success: false,
					message: "Could not save your contact request. Please try WhatsApp.",
				});
				return;
			}
			res.status(201).json({ success: true, uuid });
		} catch (e) {
			const err = toApiError(e);
			res.status(err.status ?? 400).json({
				success: false,
				message: err.message || "Invalid lead payload.",
			});
		}
	},
);

router.post(
	"/sita/chat",
	sitaChatLimiter,
	async (req: Request, res: Response) => {
		try {
			const { message, locale, context } = validateSitaChat(req.body);
			const officeAddress = (
				res.locals.site as { address?: string } | undefined
			)?.address;
			const result = await answerSitaQuestion(message, locale, context, {
				officeAddress,
			});
			res.set("Cache-Control", "no-store");
			res.json({ success: true, ...result });
		} catch (e) {
			const err = toApiError(e);
			const status = Number(err.status) || 503;
			const isClientError = status >= 400 && status < 500;
			if (!isClientError) {
				logger.error("sita.chat.failed", { error: err.message.slice(0, 200) });
			}
			res.status(status).json({
				success: false,
				message: isClientError
					? err.message || "Invalid chat question."
					: "Sagita data is temporarily unavailable. Please try again or contact the team.",
			});
		}
	},
);

/**
 * Logout untuk user yang login via Google OAuth. Cookie session di-putuskan
 * di backend; FE meneruskannya apa adanya (sudah dijaga sameOriginOnly
 * oleh middleware di /api).
 */
router.post("/logout", async (req: Request, res: Response) => {
	const ok = await logoutSession(req.headers?.cookie);
	if (!ok) {
		return res.status(502).json({
			success: false,
			message: "Could not reach the sign-in service. Please try again.",
		});
	}
	// Hapus cache session supaya render berikutnya langsung anonim.
	clearSessionCache();
	res.json({ success: true, authenticated: false });
});

/** Status login untuk dipanggil browser bila perlu (mis. setelah redirect OAuth). */
router.get("/session", async (req: Request, res: Response) => {
	const user = await getSessionUser(req.headers?.cookie);
	res.set("Cache-Control", "no-store");
	res.status(user ? 200 : 401).json({
		authenticated: !!user,
		user,
	});
});

router.post("/booking", async (req: Request, res: Response) => {
	try {
		const result = await sendBooking(validateBooking(req.body));
		res.status(200).json({
			success: true,
			message: result.message || "Permintaan booking berhasil dikirim.",
			duplicate: Boolean(result.duplicate),
		});
	} catch (e) {
		const err = toApiError(e);
		const status = Number(err.status) || 500;
		const isClientError = status >= 400 && status < 500;
		res.status(status).json({
			success: false,
			message: isClientError
				? err.message || "Failed to send booking request."
				: "Failed to send booking request.",
			errors: isClientError ? err.errors || null : null,
		});
	}
});

const captchaHandler = async (req: Request, res: Response) => {
	try {
		const captcha = await getContactCaptcha(
			req.query.lang === "en" ? "en" : "id",
		);
		res.set("Cache-Control", "no-store");
		res.json({
			success: true,
			captcha_id: String(captcha?.captcha_id ?? ""),
			question: String(captcha?.question ?? ""),
			image: String(captcha?.image ?? ""),
		});
	} catch {
		res.status(503).json({
			success: false,
			message: "Verification unavailable, please reload the page.",
		});
	}
};

router.get("/contact/captcha", captchaHandler);
router.get("/booking/captcha", captchaHandler);

// Forward contact messages to backend
router.post("/contact", async (req: Request, res: Response) => {
	try {
		const result = await sendContact(validateContact(req.body));
		res.status(200).json({
			success: true,
			message: result.message || "Message sent successfully.",
		});
	} catch (e) {
		const err = toApiError(e);
		const status = Number(err.status) || 500;
		const isClientError = status >= 400 && status < 500;
		res.status(status).json({
			success: false,
			message: isClientError
				? err.message || "Failed to send message."
				: "Failed to send message.",
			errors: isClientError ? err.errors || null : null,
		});
	}
});

/**
 * Newsletter belum ada di backend maupun di UI. Balas 501, bukan sukses
 * palsu, supaya klien tidak mengira alamat email tersimpan.
 */
router.post("/newsletter", (req: Request, res: Response) => {
	try {
		validateNewsletter(req.body);
	} catch (e) {
		return res.status(400).json({
			success: false,
			message: toApiError(e).message,
		});
	}
	res.status(501).json({
		success: false,
		message: "Newsletter signup is not available yet.",
	});
});

export default router;
