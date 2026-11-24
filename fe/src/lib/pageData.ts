import { getPageBatch } from "./services/batchService.js";
import type { BannerItem, CmsPage } from "./types/index.js";
import { sanitizeRichHtml } from "./utils/sanitize.js";

export interface HomeArticle {
	slug: string;
	title: string;
	image: string;
	date: string;
}

export interface HomePackage {
	slug: string;
	name: string;
	description: string;
	coverUrl: string;
	isPopular: boolean;
	startingPrice: number | null;
	features: string[];
}

export interface ArticleView extends Record<string, unknown> {
	slug: string;
	title: string;
	excerpt: string;
	content: string;
	image: string;
	category: string;
	date: string;
	read: string;
	publishedAt: string;
	modifiedAt: string;
}

export interface VenueLink {
	name: string;
	slug: string;
	address: string;
	image: string;
}

export function firstString(...values: unknown[]): string {
	for (const value of values) {
		if (typeof value === "string" && value.trim()) return value.trim();
	}
	return "";
}

export async function optionalCmsPage(slug: string): Promise<CmsPage | null> {
	const data = await getPageBatch("page-detail", { slug });
	const page = data?.page;
	if (!page || typeof page !== "object") return null;
	return {
		...(page as CmsPage),
		content: sanitizeRichHtml(String((page as CmsPage).content ?? "")),
	};
}

export function metaFrom(value: unknown, max = 160): string {
	return firstString(String(value ?? "").replace(/<[^>]*>/g, " ")).slice(
		0,
		max,
	);
}

function isoArticleDate(value: unknown): string {
	const datePart = firstString(value).slice(0, 10);
	return /^\d{4}-\d{2}-\d{2}$/.test(datePart) ? datePart : "";
}

function formatArticleDate(value: unknown, locale: string): string {
	const datePart = isoArticleDate(value);
	if (!datePart) return "";
	const parsed = new Date(`${datePart}T00:00:00Z`);
	if (!parsed.toISOString().startsWith(datePart)) return "";
	return new Intl.DateTimeFormat(locale, {
		day: "numeric",
		month: "short",
		year: "numeric",
		timeZone: "UTC",
	}).format(parsed);
}

function readingTime(content: unknown, locale: string): string {
	const plain = firstString(content)
		.replace(/<[^>]*>/g, " ")
		.replace(/\s+/g, " ")
		.trim();
	const words = plain ? plain.split(" ").length : 0;
	const minutes = Math.max(1, Math.ceil(words / 200));
	return locale.toLowerCase().startsWith("id")
		? `${minutes} menit baca`
		: `${minutes} min read`;
}

export function normalizeBlogPost(
	value: unknown,
	locale: string,
): ArticleView | null {
	if (!value || typeof value !== "object") return null;
	const article = value as Record<string, unknown>;
	const slug = firstString(article.slug);
	const title = firstString(article.title);
	if (!slug || !title) return null;

	const dateValue = firstString(
		article.published_at,
		article.scheduled_at,
		article.created_at,
		article.date,
	);
	const content = firstString(article.content);

	return {
		...article,
		slug,
		title,
		excerpt: firstString(article.excerpt),
		content,
		image: firstString(article.image, article.featured_image),
		category: firstString(article.category),
		date: formatArticleDate(dateValue, locale),
		read: firstString(article.read) || readingTime(content, locale),
		publishedAt: isoArticleDate(
			article.published_at || article.scheduled_at || article.created_at,
		),
		modifiedAt: isoArticleDate(article.updated_at),
	};
}

export function normalizeBlogPosts(
	value: unknown,
	locale: string,
): ArticleView[] {
	if (!Array.isArray(value)) return [];
	return value.flatMap((item): ArticleView[] => {
		const post = normalizeBlogPost(item, locale);
		return post ? [post] : [];
	});
}

export function normalizeVenueLinks(value: unknown): VenueLink[] {
	if (!Array.isArray(value)) return [];
	return value.flatMap((item): VenueLink[] => {
		if (!item || typeof item !== "object") return [];
		const venue = item as Record<string, unknown>;
		const name = firstString(venue.name);
		const slug = firstString(venue.slug, venue.uuid);
		if (!name || !slug) return [];
		return [
			{
				name,
				slug,
				address: firstString(venue.address),
				image: firstString(venue.image),
			},
		];
	});
}

export function getHomeArticles(value: unknown, locale: string): HomeArticle[] {
	if (!Array.isArray(value)) return [];
	return value
		.flatMap((item): HomeArticle[] => {
			if (!item || typeof item !== "object") return [];
			const article = item as Record<string, unknown>;
			if (
				typeof article.slug !== "string" ||
				typeof article.title !== "string"
			) {
				return [];
			}
			const dateValue =
				typeof article.scheduled_at === "string"
					? article.scheduled_at
					: article.created_at;
			const datePart =
				typeof dateValue === "string" ? dateValue.slice(0, 10) : "";
			let date = "";
			if (/^\d{4}-\d{2}-\d{2}$/.test(datePart)) {
				const parsedDate = new Date(`${datePart}T00:00:00Z`);
				if (parsedDate.toISOString().startsWith(datePart)) {
					date = new Intl.DateTimeFormat(locale, {
						day: "numeric",
						month: "short",
						timeZone: "UTC",
						year: "numeric",
					}).format(parsedDate);
				}
			}
			return [
				{
					slug: article.slug,
					title: article.title,
					image:
						typeof article.featured_image === "string"
							? article.featured_image
							: "",
					date,
				},
			];
		})
		.slice(0, 3);
}

export function getHomePackages(value: unknown): HomePackage[] {
	if (!Array.isArray(value)) return [];
	const homePackages = value.flatMap((item): HomePackage[] => {
		if (!item || typeof item !== "object") return [];
		const packageItem = item as Record<string, unknown>;
		if (
			typeof packageItem.slug !== "string" ||
			typeof packageItem.name !== "string"
		) {
			return [];
		}
		const options = Array.isArray(packageItem.options)
			? packageItem.options
			: [];
		const prices = options.flatMap((option): number[] => {
			if (!option || typeof option !== "object") return [];
			const optionItem = option as Record<string, unknown>;
			const price = Number(optionItem.final_price ?? optionItem.price);
			return Number.isFinite(price) && price > 0 ? [price] : [];
		});
		const features = Array.isArray(packageItem.features)
			? packageItem.features.flatMap((feature): string[] => {
					if (!feature || typeof feature !== "object") return [];
					const featureItem = feature as Record<string, unknown>;
					const text = featureItem.feature ?? featureItem.name;
					return typeof text === "string" && text.trim() ? [text] : [];
				})
			: [];
		return [
			{
				slug: packageItem.slug,
				name: packageItem.name,
				description:
					typeof packageItem.description === "string"
						? packageItem.description
						: "",
				coverUrl:
					typeof packageItem.cover_url === "string"
						? packageItem.cover_url
						: "",
				isPopular:
					packageItem.is_popular === true ||
					packageItem.is_popular === 1 ||
					packageItem.is_popular === "1",
				startingPrice: prices.length ? Math.min(...prices) : null,
				features: features.slice(0, 4),
			},
		];
	});
	const popularIndex = homePackages.findIndex((item) => item.isPopular);
	if (popularIndex >= 0 && popularIndex !== 1 && homePackages.length > 1) {
		const [popularPackage] = homePackages.splice(popularIndex, 1);
		homePackages.splice(1, 0, popularPackage);
	}
	return homePackages.slice(0, 3);
}

export function getHomeSliderBanners(value: unknown): BannerItem[] {
	if (!Array.isArray(value)) return [];
	return value
		.filter((item): item is BannerItem => {
			if (!item || typeof item !== "object" || !("gambar" in item))
				return false;
			const banner = item as Record<string, unknown>;
			const imageUrl = banner.gambar;
			return (
				banner.posisi === "slider" &&
				typeof imageUrl === "string" &&
				/^(https?:\/\/|\/(?!\/))/i.test(imageUrl) &&
				!/\/images\/default\.png(?:[?#]|$)/i.test(imageUrl)
			);
		})
		.slice(0, 5);
}
