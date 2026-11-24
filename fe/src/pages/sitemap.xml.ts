import type { APIRoute } from "astro";
import { env } from "../lib/config/env.js";
import apiRequest from "../lib/services/apiClient.js";
import { toApiError } from "../lib/utils/errors.js";
import { logger } from "../lib/utils/logger.js";
import { cleanSlugLoose } from "../lib/utils/validate.js";

const STATIC_URLS = [
	{ path: "/", changefreq: "daily", priority: "1.0" },
	{ path: "/about", changefreq: "monthly", priority: "0.8" },
	{ path: "/venues", changefreq: "weekly", priority: "0.9" },
	{ path: "/vendors", changefreq: "weekly", priority: "0.7" },
	{ path: "/blog", changefreq: "weekly", priority: "0.8" },
	{ path: "/packages", changefreq: "weekly", priority: "0.8" },
	{ path: "/event", changefreq: "weekly", priority: "0.7" },
	{ path: "/portofolio", changefreq: "monthly", priority: "0.5" },
	{ path: "/faq", changefreq: "monthly", priority: "0.6" },
	{
		path: "/formulir-konsultasi",
		changefreq: "monthly",
		priority: "0.7",
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

const XML_HEADERS = { "Content-Type": "application/xml; charset=utf-8" };

function escapeXml(value: unknown): string {
	return String(value ?? "")
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&apos;");
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
	let xml =
		'<?xml version="1.0" encoding="UTF-8"?>\n' +
		'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';

	const push = (path: string, changefreq: string, priority: string) => {
		if (!path || seen.has(path)) return;
		seen.add(path);
		xml += urlEntry(`${base}${path}`, changefreq, priority, today);
	};

	for (const s of STATIC_URLS) push(s.path, s.changefreq, s.priority);

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
			const record = item as { slug?: unknown } | null;
			const slug = cleanSlugLoose(record?.slug);
			if (slug) push(`${src.base}/${slug}`, src.changefreq, src.priority);
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
