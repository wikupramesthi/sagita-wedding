import type { BackendPayload } from "../types/index.js";
import { sanitizeRichHtml } from "../utils/sanitize.js";
import apiRequest, { buildQuery } from "./apiClient.js";

export type BatchPage =
	| "home"
	| "about"
	| "classes"
	| "class-detail"
	| "blog"
	| "blog-detail"
	| "packages"
	| "package-detail"
	| "instructors"
	| "events"
	| "gallery"
	| "contact"
	| "page-detail"
	| "schedules";

const ALLOWED_KEYS = [
	"slug",
	"search",
	"filter",
	"sort",
	"date",
	"level",
	"time",
	"studio_uuid",
	"date_from",
	"date_to",
	"page",
	"per_page",
];

function sanitizeEvents(value: unknown): unknown[] {
	if (!Array.isArray(value)) return [];
	return value.map((event) => {
		if (!event || typeof event !== "object") return event;
		const item = event as Record<string, unknown>;
		return { ...item, deskripsi: sanitizeRichHtml(item.deskripsi) };
	});
}

/**
 * Satu request untuk seluruh data halaman. Backend mengembalikan payload
 * fresh (tanpa cache konten), jadi perubahan admin langsung terlihat.
 */
export async function getPageBatch(
	page: BatchPage,
	params: Record<string, unknown> = {},
): Promise<BackendPayload> {
	const data = await apiRequest(
		`/batch/${page}${buildQuery(params, ALLOWED_KEYS)}`,
	);

	if (data && Array.isArray(data.events)) {
		data.events = sanitizeEvents(data.events);
	}

	return data ?? {};
}
