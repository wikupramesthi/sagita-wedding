import { Router } from "express";
import { clearSessionCache } from "../middleware/locals.js";
import { getSessionUser, logoutSession } from "../services/authService.js";
import {
	getContactCaptcha,
	sendBooking,
	sendContact,
} from "../services/contactService.js";
import type { Request, Response } from "../types/index.js";
import { toApiError } from "../utils/errors.js";
import { logger } from "../utils/logger.js";
import {
	validateBooking,
	validateContact,
	validateNewsletter,
} from "../utils/validate.js";

const router = Router();

type TelemetryPayload = {
	kind: "error" | "vital";
	name: string;
	path: string;
	value?: number;
	message?: string;
};

function parseTelemetry(value: unknown): TelemetryPayload | null {
	if (!value || typeof value !== "object" || Array.isArray(value)) return null;
	const item = value as Record<string, unknown>;
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

router.post("/telemetry", (req: Request, res: Response) => {
	const payload = parseTelemetry(req.body);
	if (!payload) {
		return res.status(400).json({
			success: false,
			message: "Invalid telemetry payload.",
		});
	}
	if (payload.kind === "error") logger.error("client.error", payload);
	else logger.info("client.vital", payload);
	res.status(204).end();
});

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
