import type { APIRoute } from "astro";
import { env } from "../lib/config/env.js";

export const GET: APIRoute = async ({ locals }) => {
	const base = (locals.siteUrl || env.SITE_URL).replace(/\/+$/, "");
	return new Response(
		`User-agent: *\nAllow: /\n\nSitemap: ${base}/sitemap.xml\n`,
		{ headers: { "Content-Type": "text/plain; charset=utf-8" } },
	);
};
