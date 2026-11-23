import { type BatchPage, SUPPORTED_BATCH_PAGES } from "./batchService.js";

export const BATCH_CONTRACT: Record<BatchPage, readonly string[]> = {
	home: [
		"weddingContentReady",
		"sliderBanners",
		"testimonials",
		"venues",
		"packages",
		"articles",
		"faqs",
	],
	venues: ["venues"],
	about: ["stats", "articles"],
	blog: ["articles", "total"],
	"blog-detail": ["post", "related", "popular", "venues"],
	packages: ["packages", "meta"],
	"package-detail": ["package"],
	vendors: ["vendors", "meta"],
	events: ["events", "meta"],
	gallery: ["gallery"],
	contact: ["faqs"],
	faq: ["faqs", "categories"],
	"page-detail": ["page"],
};

export const IDENTITY_CONTRACT = [
	"site_name",
	"site_title",
	"tagline",
	"short_description",
	"email",
	"phone",
	"address",
	"facebook_url",
	"instagram_url",
	"youtube_url",
	"tiktok_url",
	"meta_title",
	"meta_description",
	"meta_keywords",
	"google_analytics_id",
	"google_site_verification",
	"logo_url",
	"logo_white_url",
	"favicon_url",
	"og_image_url",
	"year",
] as const;

export const FAQ_ITEM_CONTRACT = [
	"uuid",
	"kategori",
	"question",
	"answer",
] as const;

export const VENUE_ITEM_CONTRACT = [
	"uuid",
	"name",
	"slug",
	"excerpt",
	"address",
	"google_maps_url",
	"image",
] as const;

export function missingKeys(value: unknown, keys: readonly string[]): string[] {
	if (!value || typeof value !== "object" || Array.isArray(value)) {
		return [...keys];
	}

	const record = value as Record<string, unknown>;

	return keys.filter((key) => record[key] === undefined);
}

export function missingBatchKeys(page: BatchPage, value: unknown): string[] {
	return missingKeys(value, BATCH_CONTRACT[page]);
}

export function missingIdentityKeys(value: unknown): string[] {
	return missingKeys(value, IDENTITY_CONTRACT);
}

export function missingItemKeys(
	value: unknown,
	items: readonly string[],
): string[] {
	const first = Array.isArray(value) ? value[0] : value;
	return missingKeys(first, items);
}

export function assertBatchPagesMatchContract(): void {
	const expected = Object.keys(BATCH_CONTRACT).sort();
	const actual = [...SUPPORTED_BATCH_PAGES].sort();

	if (JSON.stringify(expected) !== JSON.stringify(actual)) {
		throw new Error(
			`Batch contract mismatch: expected ${expected.join(", ")}, got ${actual.join(", ")}`,
		);
	}
}
