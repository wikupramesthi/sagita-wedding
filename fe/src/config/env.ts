import dotenv from "dotenv";

dotenv.config({ quiet: true });

/**
 * Centralised & validated environment config.
 * All URLs/numbers are defined here only — no hardcoding in views/services.
 */
function required(name: string, fallback?: string): string {
	const value = (process.env[name] ?? fallback ?? "").trim();
	if (!value)
		throw new Error(`Missing required env: ${name} (see .env.example)`);
	return value;
}

function optional(name: string, fallback = ""): string {
	return (process.env[name] ?? fallback).trim();
}

const NODE_ENV = optional("NODE_ENV", "development");
const rawApiUrl = required("API_URL", "http://127.0.0.1:8000/api");
const rawSiteUrl = optional(
	"SITE_URL",
	NODE_ENV === "production" ? "" : "http://localhost:3000",
);
if (!rawSiteUrl) {
	throw new Error(
		"Missing required env: SITE_URL (set the public canonical URL)",
	);
}
let parsedSiteUrl: URL;
try {
	parsedSiteUrl = new URL(rawSiteUrl);
} catch {
	throw new Error("Invalid SITE_URL: expected an absolute HTTP(S) URL");
}
if (
	!["http:", "https:"].includes(parsedSiteUrl.protocol) ||
	(NODE_ENV === "production" &&
		(parsedSiteUrl.protocol !== "https:" ||
			["localhost", "127.0.0.1", "::1"].includes(parsedSiteUrl.hostname)))
) {
	throw new Error("Invalid SITE_URL: production requires a public HTTPS URL");
}

// API_URL berakhiran /api; endpoint web backend (auth) berada di root-nya.
const apiBase = rawApiUrl.replace(/\/+$/, "").replace(/\/api$/, "");

export const env = {
	NODE_ENV,
	IS_PROD: NODE_ENV === "production",
	PORT: Number(optional("PORT", "3000")) || 3000,

	// No trailing slash for consistent endpoint concat
	API_URL: rawApiUrl.replace(/\/+$/, ""),
	// Root backend Laravel — untuk endpoint web non-/api (mis. /auth/me)
	API_BASE: apiBase,
	// Nama cookie session Laravel. Hanya dipakai sebagai petunjuk cepat untuk
	// deciding apakah perlu memanggil backend; seluruh header Cookie tetap
	// diteruskan apa adanya supaya tidak perlu tahu nama ini di FE.
	SESSION_COOKIE: optional("SESSION_COOKIE_NAME", "sagita_session"),
	API_KEY: required("API_KEY"),
	SITE_URL: rawSiteUrl.replace(/\/+$/, ""),

	GOOGLE_AUTH_URL: optional(
		"GOOGLE_AUTH_URL",
		`${rawApiUrl.replace(/\/api$/, "")}/auth/google`,
	),
	WHATSAPP_NUMBER: optional("WHATSAPP_NUMBER", "6281380760095"),
	WHATSAPP_NUMBERS: optional("WHATSAPP_NUMBERS", "6281380760095,6281288569002"),
	// Cache-busting untuk /css/* dan /js/* (?v=) — naikkan tiap rilis statis
	ASSET_V: optional("ASSET_V", "20261120t"),
	// "0" = matikan HTML minify (view-source readable saat debug)
	HTML_MINIFY: optional("HTML_MINIFY", "1"),
};

const WHATSAPP_MESSAGE = `Halo Sagita Wedding Planner 👋

Saya mendapatkan informasi mengenai Sagita Wedding Planner melalui website dan tertarik untuk mengetahui lebih lanjut tentang layanan Wedding Organizer yang ditawarkan.

Saya ingin berkonsultasi mengenai rencana pernikahan saya, termasuk pilihan paket, layanan, dan estimasi biayanya.

Apakah saya bisa mendapatkan informasi lebih lanjut?

Terima kasih 😊`;

function toInternationalWhatsappNumber(value: string): string {
	const digits = value.replace(/\D/g, "");
	if (digits.startsWith("62")) return digits;
	if (digits.startsWith("0")) return `62${digits.slice(1)}`;
	return digits;
}

export function createWhatsAppContact(number: string) {
	const whatsappNumber = toInternationalWhatsappNumber(number);
	const encodedMessage = encodeURIComponent(WHATSAPP_MESSAGE);
	return {
		whatsappNumber,
		whatsappLink:
			`https://api.whatsapp.com/send/?phone=${encodeURIComponent(whatsappNumber)}` +
			`&text=${encodedMessage}` +
			`&app_absent=0`,
		whatsappShortLink: `https://wa.me/${encodeURIComponent(whatsappNumber)}?text=${encodedMessage}`,
	};
}

export const whatsappNumbers = [
	...new Set(
		env.WHATSAPP_NUMBERS.split(",")
			.map(toInternationalWhatsappNumber)
			.filter(Boolean),
	),
];

export function getAlternatingWhatsAppContact(index: number) {
	if (whatsappNumbers.length === 0) {
		return createWhatsAppContact(env.WHATSAPP_NUMBER);
	}
	return createWhatsAppContact(whatsappNumbers[index % whatsappNumbers.length]);
}

export const siteContact = {
	...createWhatsAppContact(env.WHATSAPP_NUMBER),
	/**
	 * Login Google. `redirect` wajib diisi: tanpa itu backend melakukan
	 * redirect ke panel admin /backend/dashboard, bukan kembali ke situs publik.
	 */
	googleAuthUrl: `${env.GOOGLE_AUTH_URL}${env.GOOGLE_AUTH_URL.includes("?") ? "&" : "?"}redirect=${encodeURIComponent(env.SITE_URL)}`,
};
