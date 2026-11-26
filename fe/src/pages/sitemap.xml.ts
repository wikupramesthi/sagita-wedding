import type { APIRoute } from "astro";
import { env } from "../lib/config/env.js";
import apiRequest from "../lib/services/apiClient.js";
import {
	type BatchPage,
	getPageBatch,
	getPageContentUpdatedAt,
} from "../lib/services/batchService.js";
import { toApiError } from "../lib/utils/errors.js";
import { logger } from "../lib/utils/logger.js";
import { cleanSlugLoose } from "../lib/utils/validate.js";

const STATIC_URLS: {
	path: string;
	changefreq: string;
	priority: string;
	batchPage?: BatchPage;
}[] = [
	{ path: "/", changefreq: "daily", priority: "1.0", batchPage: "home" },
	{
		path: "/about",
		changefreq: "monthly",
		priority: "0.8",
		batchPage: "about",
	},
	{
		path: "/venues",
		changefreq: "weekly",
		priority: "0.9",
		batchPage: "venues",
	},
	{
		path: "/vendors",
		changefreq: "weekly",
		priority: "0.7",
		batchPage: "vendors",
	},
	{ path: "/blog", changefreq: "weekly", priority: "0.8", batchPage: "blog" },
	{
		path: "/packages",
		changefreq: "weekly",
		priority: "0.8",
		batchPage: "packages",
	},
	{
		path: "/event",
		changefreq: "weekly",
		priority: "0.7",
		batchPage: "events",
	},
	{ path: "/portofolio", changefreq: "monthly", priority: "0.5" },
	{ path: "/faq", changefreq: "monthly", priority: "0.6", batchPage: "faq" },
	{
		path: "/formulir-konsultasi",
		changefreq: "monthly",
		priority: "0.7",
		batchPage: "contact",
	},
];

const DYNAMIC_SOURCES = [
	{
		base: "/packages",
		endpoint: "/packages?per_page=50",
		changefreq: "weekly",
		priority: "0.7",
	},
	{
		base: "/blog",
		endpoint: "/articles",
		changefreq: "weekly",
		priority: "0.6",
	},
	{
		base: "/pages",
		endpoint: "/pages",
		changefreq: "monthly",
		priority: "0.5",
	},
];

const XML_HEADERS = {
	"Content-Type": "application/xml; charset=utf-8",
	"Cache-Control": "public, max-age=3600, stale-while-revalidate=86400",
};

function escapeXml(value: unknown): string {
	return String(value ?? "")
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&apos;");
}

function parseLastmod(value: unknown, fallback: string): string {
	if (typeof value !== "string") return fallback;
	const date = new Date(value);
	return Number.isNaN(date.getTime())
		? fallback
		: date.toISOString().slice(0, 10);
}

function urlEntry(
	loc: string,
	changefreq: string,
	priority: string,
	lastmod: string,
): string {
	return (
		"  <url>\n" +
		`    <loc>${escapeXml(loc)}</loc>\n` +
		`    <lastmod>${lastmod}</lastmod>\n` +
		`    <changefreq>${changefreq}</changefreq>\n` +
		`    <priority>${priority}</priority>\n` +
		"  </url>\n"
	);
}

async function buildSitemap(base: string): Promise<string> {
	const today = new Date().toISOString().slice(0, 10);
	const seen = new Set<string>();
	const lastmodByPath = new Map<string, string>();
	let xml =
		'<?xml version="1.0" encoding="UTF-8"?>\n' +
		'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';

	const push = (
		path: string,
		changefreq: string,
		priority: string,
		lastmod = today,
	) => {
		if (!path || seen.has(path)) return;
		seen.add(path);
		xml += urlEntry(`${base}${path}`, changefreq, priority, lastmod);
	};

	const batchPages = STATIC_URLS.flatMap((s) =>
		s.batchPage ? [{ path: s.path, page: s.batchPage }] : [],
	);
	const batchResults = await Promise.allSettled(
		batchPages.map(async ({ path, page }) => {
			await getPageBatch(page);
			const updatedAt = getPageContentUpdatedAt(page);
			if (updatedAt) lastmodByPath.set(path, parseLastmod(updatedAt, today));
		}),
	);
	for (const result of batchResults) {
		if (result.status === "rejected") {
			logger.debug("sitemap.batch_lastmod_failed", {
				error: toApiError(result.reason).message.slice(0, 200),
			});
		}
	}

	for (const s of STATIC_URLS) {
		push(s.path, s.changefreq, s.priority, lastmodByPath.get(s.path));
	}

	const results = await Promise.allSettled(
		DYNAMIC_SOURCES.map(async (src) => {
			const items: unknown[] = [];
			let page = 1;
			let lastPage = 1;

			while (page <= lastPage && page <= 50) {
				const separator = src.endpoint.includes("?") ? "&" : "?";
				const data = await apiRequest(
					`${src.endpoint}${separator}page=${page}`,
				);
				const pageItems = Array.isArray(data) ? data : data?.data || [];
				items.push(...pageItems);
				lastPage = Math.min(Number(data?.meta?.last_page) || 1, 50);
				page += 1;
			}

			return { src, items };
		}),
	);
	results.forEach((result) => {
		if (result.status !== "fulfilled") return;
		const { src, items } = result.value;
		for (const item of items) {
			const record = item as {
				slug?: unknown;
				updated_at?: unknown;
				updatedAt?: unknown;
				modified_at?: unknown;
				contentUpdatedAt?: unknown;
			} | null;
			const slug = cleanSlugLoose(record?.slug);
			if (!slug) continue;
			const updatedAt =
				record?.updated_at ??
				record?.updatedAt ??
				record?.modified_at ??
				record?.contentUpdatedAt;
			push(
				`${src.base}/${slug}`,
				src.changefreq,
				src.priority,
				parseLastmod(updatedAt, today),
			);
		}
	});

	xml += "</urlset>";
	return xml;
}

export const GET: APIRoute = async ({ locals }) => {
	const base = (locals.siteUrl || env.SITE_URL).replace(/\/+$/, "");
	try {
		return new Response(await buildSitemap(base), { headers: XML_HEADERS });
	} catch (e) {
		logger.error("sitemap.failed", {
			error: toApiError(e).message.slice(0, 200),
		});
		try {
			const today = new Date().toISOString().slice(0, 10);
			let xml =
				'<?xml version="1.0" encoding="UTF-8"?>\n' +
				'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
			for (const s of STATIC_URLS) {
				xml += urlEntry(`${base}${s.path}`, s.changefreq, s.priority, today);
			}
			xml += "</urlset>";
			return new Response(xml, { headers: XML_HEADERS });
		} catch {
			return new Response("Sitemap unavailable", {
				status: 500,
				headers: { "Content-Type": "text/plain; charset=utf-8" },
			});
		}
	}
};
