import type { BackendPayload } from "../types/index.js";
import { sanitizeRichHtml } from "../utils/sanitize.js";
import { apiEnvelopeRaw, buildQuery } from "./apiClient.js";

export const SUPPORTED_BATCH_PAGES = [
	"home",
	"venues",
	"about",
	"blog",
	"blog-detail",
	"packages",
	"package-detail",
	"vendors",
	"events",
	"gallery",
	"contact",
	"faq",
	"page-detail",
] as const;

export type BatchPage = (typeof SUPPORTED_BATCH_PAGES)[number];

const ALLOWED_KEYS = [
	"slug",
	"search",
	"filter",
	"sort",
	"date_from",
	"date_to",
	"page",
	"per_page",
];

const MAX_CACHE_ENTRIES = 200;

interface CachedBatch {
	etag?: string;
	value: BackendPayload;
}

const cache = new Map<string, CachedBatch>();
const inflight = new Map<string, Promise<BackendPayload>>();
let latestContentUpdatedAt: string | null = null;

export function clearBatchCache(): void {
	cache.clear();
	latestContentUpdatedAt = null;
}

export function getBatchContentUpdatedAt(): string | null {
	return latestContentUpdatedAt;
}

function rememberContentUpdatedAt(value: BackendPayload): void {
	if (!value || typeof value !== "object" || Array.isArray(value)) {
		latestContentUpdatedAt = null;
		return;
	}

	const updatedAt = (value as Record<string, unknown>).contentUpdatedAt;
	latestContentUpdatedAt =
		typeof updatedAt === "string" && Number.isFinite(Date.parse(updatedAt))
			? updatedAt
			: null;
}

function remember(key: string, entry: CachedBatch): void {
	cache.delete(key);
	cache.set(key, entry);
	while (cache.size > MAX_CACHE_ENTRIES) {
		const oldest = cache.keys().next().value;
		if (oldest === undefined) break;
		cache.delete(oldest);
	}
}

function sanitizeEvents(value: unknown): unknown[] {
	if (!Array.isArray(value)) return [];
	return value.map((event) => {
		if (!event || typeof event !== "object") return event;
		const item = event as Record<string, unknown>;
		return { ...item, deskripsi: sanitizeRichHtml(item.deskripsi) };
	});
}

/**
 * Satu request untuk seluruh data halaman, dengan ETag + cache lokal di FE.
 * Setiap akses halaman tetap revalidasi ke backend: 304 memakai body cache,
 * 200 (setelah admin mengubah konten) langsung menggantikan cache.
 */
export async function getPageBatch(
	page: BatchPage,
	params: Record<string, unknown> = {},
): Promise<BackendPayload> {
	const query = buildQuery(params, ALLOWED_KEYS);
	const key = `${page}${query}`;
	const pending = inflight.get(key);
	if (pending) return pending;

	const task = (async () => {
		const cached = cache.get(key);
		const result = await apiEnvelopeRaw(`/batch/${page}${query}`, {
			headers: cached?.etag ? { "If-None-Match": cached.etag } : {},
		});

		if (result.status === 304) {
			if (!cached) {
				throw new Error("Backend returned 304 without a cached batch body");
			}
			remember(key, cached);
			rememberContentUpdatedAt(cached.value);
			return cached.value;
		}

		const body = result.body ?? {};
		const data = body?.data ?? body;
		if (data && Array.isArray(data.events)) {
			data.events = sanitizeEvents(data.events);
		}
		remember(key, { etag: result.etag ?? undefined, value: data });
		rememberContentUpdatedAt(data);
		return data;
	})();

	inflight.set(key, task);
	try {
		return await task;
	} finally {
		inflight.delete(key);
	}
}
