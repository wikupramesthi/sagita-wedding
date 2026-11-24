import { defineMiddleware } from "astro:middleware";
import { randomBytes } from "node:crypto";
import { minify } from "html-minifier-terser";
import {
	buildVisitPayload,
	randomUUID,
	shouldTrackVisit,
	submitVisit,
	visitorIdFromCookie,
} from "./lib/analytics.js";
import {
	env,
	getAlternatingWhatsAppContact,
	siteContact,
} from "./lib/config/env.js";
import {
	DEFAULT_LOCALE,
	isLocale,
	LANGUAGE_OPTIONS,
} from "./lib/config/locales.js";
import { sagitaData } from "./lib/data/sagitaData.js";
import { translations } from "./lib/data/translations.js";
import {
	clientIp,
	json,
	type RateLimitResult,
	rateLimitHeaders,
	sameOriginAllowed,
	securityHeaders,
	takeRateLimit,
} from "./lib/http.js";
import { resolveSite } from "./lib/services/siteIdentityService.js";
import { resolveUser } from "./lib/session.js";

let whatsappRenderIndex = 0;

const MINIFY_OPTIONS = {
	collapseWhitespace: true,
	removeComments: true,
	removeRedundantAttributes: true,
	removeScriptTypeAttributes: true,
	removeStyleLinkTypeAttributes: true,
	useShortDoctype: true,
	minifyCSS: false,
	minifyJS: false,
};

async function withMinifiedHtml(response: Response): Promise<Response> {
	const contentType = response.headers.get("content-type") ?? "";
	if (!contentType.includes("text/html")) return response;
	try {
		const html = await response.text();
		if (!html.includes("<html")) return new Response(html, response);
		return new Response(await minify(html, MINIFY_OPTIONS), response);
	} catch {
		return response;
	}
}

export const onRequest = defineMiddleware(async (context, next) => {
	const { request, cookies, locals, url } = context;
	const pathname = url.pathname;
	const nonce = randomBytes(16).toString("base64");
	locals.nonce = nonce;

	const langParam = url.searchParams.get("lang") ?? "";
	if (isLocale(langParam)) {
		cookies.set("locale", langParam, {
			path: "/",
			maxAge: 365 * 24 * 60 * 60,
			sameSite: "lax",
			httpOnly: true,
			secure: env.IS_PROD,
		});
	}
	const cookieLang = cookies.get("locale")?.value ?? "";
	const lang = isLocale(langParam)
		? langParam
		: isLocale(cookieLang)
			? cookieLang
			: DEFAULT_LOCALE;
	locals.lang = lang;
	locals.dateLocale = lang === "id" ? "id-ID" : "en-US";
	locals.t = translations[lang] ?? translations[DEFAULT_LOCALE];

	const isApi = pathname.startsWith("/api/");
	let apiLimit: RateLimitResult | null = null;
	if (isApi) {
		if (!sameOriginAllowed(request)) {
			return json(
				{ success: false, message: "Origin not allowed." },
				403,
				securityHeaders(nonce),
			);
		}
		const bucketKey = `${clientIp(request)}:${pathname === "/api/telemetry" ? "telemetry" : "api"}`;
		apiLimit = takeRateLimit(bucketKey, 20);
		if (!apiLimit.ok) {
			return json(
				{
					success: false,
					message: "Too many requests, please try again shortly.",
				},
				429,
				{ ...securityHeaders(nonce), ...rateLimitHeaders(apiLimit) },
			);
		}
	}

	const site = await resolveSite();
	locals.site = site;
	locals.nav = sagitaData.nav;
	locals.languages = LANGUAGE_OPTIONS;
	locals.contactLinks = {
		...getAlternatingWhatsAppContact(whatsappRenderIndex++),
		googleAuthUrl: siteContact.googleAuthUrl,
		loginUrl: `${env.BACKEND_URL}/auth/login`,
		backendBaseUrl: env.BACKEND_URL,
		dashboardUrl: `${env.BACKEND_URL}/backend/dashboard`,
	};
	locals.currentPath = pathname;
	locals.currentUrl = `${env.SITE_URL}${pathname}${lang === DEFAULT_LOCALE ? "" : `?lang=${lang}`}`;
	locals.siteUrl = env.SITE_URL;
	locals.assetV = env.ASSET_V;
	locals.user = await resolveUser(request.headers.get("cookie") ?? undefined);
	locals.title = site.metaTitle || `${site.name} — ${site.tagline}`;
	locals.seo = {
		description: site.metaDescription || site.description,
		keywords: site.metaKeywords,
		robots: "index, follow",
		ogImage: site.ogImage,
	};

	let visitorId: string | null = null;
	let visitPath = "";
	let visitAgent = "";
	const trackVisit = shouldTrackVisit(request, pathname);
	if (trackVisit) {
		visitorId = visitorIdFromCookie(request.headers.get("cookie") ?? undefined);
		if (!visitorId) {
			visitorId = randomUUID();
			cookies.set("sagita_visitor", visitorId, {
				path: "/",
				httpOnly: true,
				sameSite: "lax",
				secure: env.IS_PROD,
			});
		}
		visitPath = pathname;
		visitAgent = request.headers.get("user-agent") ?? "";
	}

	const response = await next();
	for (const [key, value] of Object.entries(securityHeaders(nonce))) {
		response.headers.set(key, value);
	}
	if (apiLimit) {
		for (const [key, value] of Object.entries(rateLimitHeaders(apiLimit))) {
			response.headers.set(key, value);
		}
	}

	if (trackVisit && response.status === 200 && visitorId) {
		void submitVisit(buildVisitPayload(visitorId, visitPath, visitAgent));
	}

	return env.HTML_MINIFY === "0" ? response : await withMinifiedHtml(response);
});
