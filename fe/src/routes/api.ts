import { Router } from "express";
import { clearSessionCache } from "../middleware/locals.js";
import { getSessionUser, logoutSession } from "../services/authService.js";
import { getPageBatch } from "../services/batchService.js";
import { getClassSchedules } from "../services/classScheduleService.js";
import { getClasses } from "../services/classService.js";
import { getContactCaptcha, sendContact } from "../services/contactService.js";
import type { NextFunction, Request, Response } from "../types/index.js";
import { toApiError } from "../utils/errors.js";
import {
	cleanDate,
	cleanText,
	UUID_RE,
	validateBooking,
	validateContact,
	validateNewsletter,
} from "../utils/validate.js";

const router = Router();

/** Validasi + normalisasi filter jadwal yang boleh diteruskan ke backend. */
function scheduleParams(req: Request): Record<string, string | number> {
	const str = (v: unknown): string => (typeof v === "string" ? v : "");
	const params: Record<string, string | number> = {};
	const date = cleanDate(str(req.query.date));
	if (date) params.date = date;
	const level = cleanText(str(req.query.level), {
		max: 30,
		label: "level",
	});
	if (level) params.level = level;
	const time = cleanText(str(req.query.time), { max: 30, label: "time" });
	if (time) params.time = time;
	const venue = str(req.query.venue_uuid).trim();
	if (UUID_RE.test(venue)) params.venue_uuid = venue;
	const perPage = Number.parseInt(str(req.query.per_page), 10) || 50;
	params.per_page = Math.min(Math.max(perPage, 1), 50);
	return params;
}

// Class list (clean proxy to backend)
router.get(
	"/classes",
	async (_req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getClasses({ per_page: 50 });
			res.json(data?.data || data || []);
		} catch (err) {
			next(err);
		}
	},
);

// Class schedules (clean proxy: forwards date/level/time/venue filters)
router.get(
	"/class-schedules",
	async (req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getClassSchedules(scheduleParams(req));
			res.json(Array.isArray(data) ? data : data?.data || []);
		} catch (err) {
			next(err);
		}
	},
);

// Satu request untuk jadwal + daftar venue (dipakai browser halaman /schedules).
router.get(
	"/batch/schedules",
	async (req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getPageBatch("schedules", scheduleParams(req));
			res.set("Cache-Control", "no-store");
			res.json({
				date: data?.date || "",
				schedules: Array.isArray(data?.schedules) ? data.schedules : [],
				venues: Array.isArray(data?.venues) ? data.venues : [],
				meta: data?.meta || {},
			});
		} catch (err) {
			next(err);
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

/**
 * Booking belum punya endpoint di backend (order dibuat lewat panel
 * /backend yang butuh session). Endpoint ini sengaja TIDAK mengembalikan
 * sukses palsu — balas 501 supaya klien tahu belum tersedia.
 * Booking dari situs publik saat ini lewat WhatsApp (siteContact).
 */
router.post("/booking", (req: Request, res: Response) => {
	try {
		validateBooking(req.body);
	} catch (e) {
		const err = toApiError(e);
		const status = Number(err.status) || 400;
		return res.status(status).json({
			success: false,
			message: status < 500 ? err.message : "Booking failed.",
		});
	}
	res.status(501).json({
		success: false,
		message: "Online booking is not available yet. Please book via WhatsApp.",
	});
});

router.get("/contact/captcha", async (req: Request, res: Response) => {
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
});

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
