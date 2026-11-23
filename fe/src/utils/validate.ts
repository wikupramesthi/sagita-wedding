/**
 * Lightweight input validation — FE is a theme, backend remains authoritative.
 * All functions return a clean string or throw ApiError (status 400).
 */
import type {
	ApiError,
	BookingPayload,
	CleanTextOptions,
	ContactPayload,
} from "../types/index.js";

const SLUG_RE = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
const SLUG_LOOSE_RE = /^[A-Za-z0-9_-]+$/;
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
const PHONE_RE = /^[0-9+\-\s()]{6,20}$/;
export const UUID_RE =
	/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;

function bad(message: string): ApiError {
	const err = new Error(message) as ApiError;
	err.status = 400;
	return err;
}

export function cleanSlug(value: unknown, label = "slug"): string {
	const slug = String(value ?? "")
		.trim()
		.toLowerCase()
		.slice(0, 120);
	if (!SLUG_RE.test(slug)) throw bad(`${label} is invalid`);
	return slug;
}

/**
 * Versi longgar untuk compose URL (sitemap, canonical): tidak pernah melempar,
 * nilai tak valid menjadi string kosong supaya pemanggil tetap fail-open.
 * Berbeda dari cleanSlug() yang ketat dan melempar ApiError.
 */
export function cleanSlugLoose(value: unknown): string {
	const s = String(value ?? "").trim();
	return SLUG_LOOSE_RE.test(s) ? s : "";
}

export function cleanText(
	value: unknown,
	{ max = 100, label = "input" }: CleanTextOptions = {},
): string {
	const text = String(value ?? "")
		.trim()
		.slice(0, max);
	if (/[<>]/.test(text)) throw bad(`${label} contains forbidden characters`);
	return text;
}

export function cleanEmail(value: unknown): string {
	const email = String(value ?? "")
		.trim()
		.toLowerCase()
		.slice(0, 160);
	if (!EMAIL_RE.test(email)) throw bad("Invalid email address");
	return email;
}

export function cleanPage(value: unknown): number {
	const page = Number.parseInt(String(value ?? "1"), 10);
	return Number.isFinite(page) && page > 0 ? Math.min(page, 1000) : 1;
}

export function cleanDate(value: unknown): string {
	const date = String(value ?? "")
		.trim()
		.slice(0, 10);
	if (!date) return "";
	if (!/^\d{4}-\d{2}-\d{2}$/.test(date))
		throw bad("Invalid date format (YYYY-MM-DD)");
	return date;
}

export function validateBooking(
	body: Record<string, unknown> = {},
): BookingPayload {
	const nama = cleanText(body.nama, { max: 80, label: "Name" });
	const email = cleanEmail(body.email);
	const no_telp = String(body.no_telp ?? "")
		.trim()
		.slice(0, 20);
	if (!PHONE_RE.test(no_telp)) throw bad("Invalid phone number");
	const paket = cleanText(body.paket, { max: 80, label: "Package" });
	const tanggal = cleanDate(body.tanggal);
	if (!tanggal) throw bad("Event date is required");
	const catatan = cleanText(body.isi, { max: 1000, label: "Notes" });
	const captcha_id = String(body.captcha_id ?? "").trim();
	if (!UUID_RE.test(captcha_id))
		throw bad("Verification expired, please reload the page");
	const captcha_answer = String(body.captcha_answer ?? "").trim();
	if (!/^\d{4}$/.test(captcha_answer)) throw bad("Invalid verification answer");
	if (!nama || !paket)
		throw bad("Name, email, package, and event date are required");

	const isi = `Permintaan booking paket ${paket} untuk tanggal ${tanggal}.`;
	return {
		nama,
		email,
		no_telp,
		paket,
		tanggal,
		isi: catatan ? `${isi} Catatan: ${catatan}` : isi,
		captcha_id,
		captcha_answer,
	};
}

export function validateContact(
	body: Record<string, unknown> = {},
): ContactPayload {
	const nama = cleanText(body.nama, { max: 80, label: "Name" });
	const email = cleanEmail(body.email);
	const no_telp = String(body.no_telp ?? "")
		.trim()
		.slice(0, 20);
	if (!PHONE_RE.test(no_telp)) throw bad("Invalid phone number");
	const isi = cleanText(body.isi, { max: 2000, label: "Message" });
	const captcha_id = String(body.captcha_id ?? "").trim();
	if (!UUID_RE.test(captcha_id))
		throw bad("Verification expired, please reload the page");
	const captcha_answer = String(body.captcha_answer ?? "");
	if (!/^\d{4}$/.test(captcha_answer)) throw bad("Invalid verification answer");
	if (!nama || !isi) throw bad("Name, email, and message are required");
	return { nama, email, no_telp, isi, captcha_id, captcha_answer };
}

export function validateNewsletter(body: Record<string, unknown> = {}): {
	email: string;
} {
	return { email: cleanEmail(body.email) };
}

export type SitaChatTopic = "packages" | "venues" | "vendors";

export interface SitaChatCriteria {
	budgetLimit?: number;
	guestCount?: number;
	city?: string;
	eventDate?: string;
	vendorCategory?: string;
}

export interface SitaChatContext {
	topic: SitaChatTopic;
	criteria?: SitaChatCriteria;
}

export function validateSitaChat(body: Record<string, unknown> = {}): {
	message: string;
	locale: "id" | "en";
	context?: SitaChatContext;
} {
	const rawMessage = String(body.message ?? "").trim();
	if (rawMessage.length > 500) {
		throw bad("Message must be 500 characters or fewer");
	}
	const message = cleanText(rawMessage, { max: 500, label: "Message" });
	if (!message) throw bad("Message is required");
	let context: SitaChatContext | undefined;
	if (body.context !== undefined) {
		if (
			!body.context ||
			typeof body.context !== "object" ||
			Array.isArray(body.context)
		) {
			throw bad("Chat context must be an object");
		}
		const rawContext = body.context as Record<string, unknown>;
		if (
			Object.keys(rawContext).some(
				(key) => !["topic", "criteria"].includes(key),
			)
		) {
			throw bad("Unsupported chat context field");
		}
		const topic = rawContext.topic;
		if (topic !== "packages" && topic !== "venues" && topic !== "vendors") {
			throw bad("Invalid chat topic");
		}
		let criteria: SitaChatCriteria | undefined;
		if (rawContext.criteria !== undefined) {
			if (
				!rawContext.criteria ||
				typeof rawContext.criteria !== "object" ||
				Array.isArray(rawContext.criteria)
			) {
				throw bad("Chat criteria must be an object");
			}
			const rawCriteria = rawContext.criteria as Record<string, unknown>;
			const allowedCriteria = [
				"budgetLimit",
				"guestCount",
				"city",
				"eventDate",
				"vendorCategory",
			];
			if (
				Object.keys(rawCriteria).some((key) => !allowedCriteria.includes(key))
			) {
				throw bad("Unsupported chat criteria field");
			}
			const normalized: SitaChatCriteria = {};
			if (rawCriteria.budgetLimit !== undefined) {
				const value = rawCriteria.budgetLimit;
				if (
					typeof value !== "number" ||
					!Number.isFinite(value) ||
					value < 0 ||
					value > 1_000_000_000_000
				) {
					throw bad("Invalid budget limit");
				}
				normalized.budgetLimit = value;
			}
			if (rawCriteria.guestCount !== undefined) {
				const value = rawCriteria.guestCount;
				if (
					typeof value !== "number" ||
					!Number.isSafeInteger(value) ||
					value < 1 ||
					value > 100_000
				) {
					throw bad("Invalid guest count");
				}
				normalized.guestCount = value;
			}
			if (rawCriteria.city !== undefined) {
				const city = cleanText(rawCriteria.city, {
					max: 100,
					label: "City",
				});
				if (city) normalized.city = city;
			}
			if (rawCriteria.eventDate !== undefined) {
				if (typeof rawCriteria.eventDate !== "string") {
					throw bad("Invalid event date");
				}
				const eventDate = cleanDate(rawCriteria.eventDate);
				if (eventDate) {
					const parsedDate = new Date(`${eventDate}T00:00:00Z`);
					if (
						Number.isNaN(parsedDate.getTime()) ||
						parsedDate.toISOString().slice(0, 10) !== eventDate
					) {
						throw bad("Invalid event date");
					}
				}
				if (eventDate) normalized.eventDate = eventDate;
			}
			if (rawCriteria.vendorCategory !== undefined) {
				const category = cleanText(rawCriteria.vendorCategory, {
					max: 60,
					label: "Vendor category",
				});
				if (category) normalized.vendorCategory = category;
			}
			if (Object.keys(normalized).length) criteria = normalized;
		}
		context = { topic, ...(criteria ? { criteria } : {}) };
	}
	return {
		message,
		locale: body.locale === "en" ? "en" : "id",
		...(context ? { context } : {}),
	};
}
