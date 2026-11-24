/**
 * Lightweight input validation — FE is a theme, backend remains authoritative.
 * All functions return a clean string or throw ApiError (status 400).
 */
import type {
	ApiError,
	BookingPayload,
	CleanTextOptions,
	ContactPayload,
	NewsletterPayload,
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

export function validateNewsletter(
	body: Record<string, unknown> = {},
): NewsletterPayload {
	return { email: cleanEmail(body.email) };
}

export type SitaChatTopic = "packages" | "venues" | "vendors";
export type SitaLeadTopic = SitaChatTopic | "services" | "other";

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

export interface SitaLeadPayload {
	name: string;
	phone: string;
	topic: SitaLeadTopic;
	criteria?: SitaChatCriteria;
	score: number;
	message?: string;
	website?: string;
}

const SITA_CRITERIA_KEYS = [
	"budgetLimit",
	"guestCount",
	"city",
	"eventDate",
	"vendorCategory",
];

function cleanSitaCriteria(value: unknown): SitaChatCriteria | undefined {
	if (value === undefined) return undefined;
	if (!value || typeof value !== "object" || Array.isArray(value)) {
		throw bad("Chat criteria must be an object");
	}
	const raw = value as Record<string, unknown>;
	if (Object.keys(raw).some((key) => !SITA_CRITERIA_KEYS.includes(key))) {
		throw bad("Unsupported chat criteria field");
	}
	const normalized: SitaChatCriteria = {};
	if (raw.budgetLimit !== undefined) {
		const amount = raw.budgetLimit;
		if (
			typeof amount !== "number" ||
			!Number.isFinite(amount) ||
			amount < 0 ||
			amount > 1_000_000_000_000
		) {
			throw bad("Invalid budget limit");
		}
		normalized.budgetLimit = amount;
	}
	if (raw.guestCount !== undefined) {
		const count = raw.guestCount;
		if (
			typeof count !== "number" ||
			!Number.isSafeInteger(count) ||
			count < 1 ||
			count > 100_000
		) {
			throw bad("Invalid guest count");
		}
		normalized.guestCount = count;
	}
	if (raw.city !== undefined) {
		const city = cleanText(raw.city, { max: 100, label: "City" });
		if (city) normalized.city = city;
	}
	if (raw.eventDate !== undefined) {
		if (typeof raw.eventDate !== "string") throw bad("Invalid event date");
		const eventDate = cleanDate(raw.eventDate);
		if (eventDate) {
			const parsed = new Date(`${eventDate}T00:00:00Z`);
			if (
				Number.isNaN(parsed.getTime()) ||
				parsed.toISOString().slice(0, 10) !== eventDate
			) {
				throw bad("Invalid event date");
			}
			normalized.eventDate = eventDate;
		}
	}
	if (raw.vendorCategory !== undefined) {
		const category = cleanText(raw.vendorCategory, {
			max: 60,
			label: "Vendor category",
		});
		if (category) normalized.vendorCategory = category;
	}
	return Object.keys(normalized).length ? normalized : undefined;
}

export function validateSitaLead(
	body: Record<string, unknown> = {},
): SitaLeadPayload {
	const name = cleanText(body.name, { max: 150, label: "Name" });
	const phone = String(body.phone ?? "")
		.trim()
		.slice(0, 30);
	if (!name) throw bad("Name is required");
	if (!/^[0-9+\-\s()]{6,30}$/.test(phone)) throw bad("Invalid phone number");
	const topic = body.topic;
	const allowedTopics = ["packages", "venues", "vendors", "services", "other"];
	if (typeof topic !== "string" || !allowedTopics.includes(topic)) {
		throw bad("Invalid chat topic");
	}
	const score = Number(body.score);
	if (!Number.isInteger(score) || score < 0 || score > 100) {
		throw bad("Invalid lead score");
	}
	const message = cleanText(body.message, { max: 1000, label: "Message" });
	const criteria = cleanSitaCriteria(body.criteria);
	const website = String(body.website ?? "").trim();
	return {
		name,
		phone,
		topic: topic as SitaLeadTopic,
		...(criteria ? { criteria } : {}),
		score,
		...(message ? { message } : {}),
		website,
	};
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
		const criteria = cleanSitaCriteria(rawContext.criteria);
		context = { topic, ...(criteria ? { criteria } : {}) };
	}
	return {
		message,
		locale: body.locale === "en" ? "en" : "id",
		...(context ? { context } : {}),
	};
}
