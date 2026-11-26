import { randomBytes } from "node:crypto";
import { createServer, type Server } from "node:http";
import { experimental_AstroContainer as AstroContainer } from "astro/container";
import {
	env,
	getAlternatingWhatsAppContact,
	siteContact,
} from "../../src/lib/config/env.js";
import {
	DEFAULT_LOCALE,
	isLocale,
	LANGUAGE_OPTIONS,
} from "../../src/lib/config/locales.js";
import { sagitaData } from "../../src/lib/data/sagitaData.js";
import { translations } from "../../src/lib/data/translations.js";
import {
	json,
	sameOriginAllowed,
	securityHeaders,
} from "../../src/lib/http.js";
import { resolveSite } from "../../src/lib/services/siteIdentityService.js";
import { resolveUser } from "../../src/lib/session.js";
import NotFound from "../../src/pages/404.astro";
import About from "../../src/pages/about.astro";
import * as BatchSchedules from "../../src/pages/api/batch/schedules.js";
import * as BookingCaptcha from "../../src/pages/api/booking/captcha.js";
import * as Booking from "../../src/pages/api/booking.js";
import * as ClassSchedules from "../../src/pages/api/class-schedules.js";
import * as Classes from "../../src/pages/api/classes.js";
import * as ContactCaptcha from "../../src/pages/api/contact/captcha.js";
import * as Contact from "../../src/pages/api/contact.js";
import * as Logout from "../../src/pages/api/logout.js";
import * as Newsletter from "../../src/pages/api/newsletter.js";
import * as Session from "../../src/pages/api/session.js";
import * as SitaAnalytics from "../../src/pages/api/sita/analytics.js";
import * as SitaKnowledge from "../../src/pages/api/sita/knowledge.js";
import * as SitaLeads from "../../src/pages/api/sita/leads.js";
import * as Telemetry from "../../src/pages/api/telemetry.js";
import ArticleDetail from "../../src/pages/blog/[slug].astro";
import Blog from "../../src/pages/blog.astro";
import Events from "../../src/pages/event.astro";
import Faq from "../../src/pages/faq.astro";
import FormulirKonsultasi from "../../src/pages/formulir-konsultasi.astro";
import * as Healthz from "../../src/pages/healthz.js";
import Home from "../../src/pages/index.astro";
import PackageDetail from "../../src/pages/packages/[slug].astro";
import Packages from "../../src/pages/packages.astro";
import CmsPage from "../../src/pages/pages/[slug].astro";
import Portofolio from "../../src/pages/portofolio.astro";
import * as Readyz from "../../src/pages/readyz.js";
import * as Robots from "../../src/pages/robots.txt.js";
import * as Sitemap from "../../src/pages/sitemap.xml.js";
import Vendors from "../../src/pages/vendors.astro";
import Venues from "../../src/pages/venues.astro";

const containerPromise = AstroContainer.create();

type RouteHandler = (context: any) => Promise<Response> | Response;

const pageRoutes: { pattern: RegExp; component: any; params?: string[] }[] = [
	{ pattern: /^\/$/, component: Home },
	{ pattern: /^\/about$/, component: About },
	{ pattern: /^\/formulir-konsultasi$/, component: FormulirKonsultasi },
	{ pattern: /^\/faq$/, component: Faq },
	{ pattern: /^\/venues$/, component: Venues },
	{ pattern: /^\/packages$/, component: Packages },
	{
		pattern: /^\/packages\/([^/]+)$/,
		component: PackageDetail,
		params: ["slug"],
	},
	{ pattern: /^\/blog$/, component: Blog },
	{ pattern: /^\/blog\/([^/]+)$/, component: ArticleDetail, params: ["slug"] },
	{ pattern: /^\/event$/, component: Events },
	{ pattern: /^\/portofolio$/, component: Portofolio },
	{ pattern: /^\/vendors$/, component: Vendors },
	{ pattern: /^\/pages\/([^/]+)$/, component: CmsPage, params: ["slug"] },
];

const apiRoutes: Record<string, Record<string, RouteHandler>> = {
	"/api/session": { GET: Session.GET as RouteHandler },
	"/api/logout": { POST: Logout.POST as RouteHandler },
	"/api/booking": { POST: Booking.POST as RouteHandler },
	"/api/booking/captcha": { GET: BookingCaptcha.GET as RouteHandler },
	"/api/classes": { GET: Classes.GET as RouteHandler },
	"/api/class-schedules": { GET: ClassSchedules.GET as RouteHandler },
	"/api/batch/schedules": { GET: BatchSchedules.GET as RouteHandler },
	"/api/contact": { POST: Contact.POST as RouteHandler },
	"/api/contact/captcha": { GET: ContactCaptcha.GET as RouteHandler },
	"/api/newsletter": { POST: Newsletter.POST as RouteHandler },
	"/api/telemetry": { POST: Telemetry.POST as RouteHandler },
	"/api/sita/analytics": { GET: SitaAnalytics.GET as RouteHandler },
	"/api/sita/knowledge": { GET: SitaKnowledge.GET as RouteHandler },
	"/api/sita/leads": { POST: SitaLeads.POST as RouteHandler },
};

const endpointRoutes: Record<string, RouteHandler> = {
	"/healthz": Healthz.GET as RouteHandler,
	"/readyz": Readyz.GET as RouteHandler,
	"/robots.txt": Robots.GET as RouteHandler,
	"/sitemap.xml": Sitemap.GET as RouteHandler,
};

function cookieValue(header: string, name: string): string {
	for (const part of header.split(";")) {
		const [key, ...rest] = part.trim().split("=");
		if (key === name) return rest.join("=");
	}
	return "";
}

async function buildLocals(
	request: Request,
	url: URL,
	nonce: string,
): Promise<App.Locals> {
	const cookieHeader = request.headers.get("cookie") ?? "";
	const langParam = url.searchParams.get("lang") ?? "";
	const cookieLang = cookieValue(cookieHeader, "locale");
	const lang = isLocale(langParam)
		? langParam
		: isLocale(cookieLang)
			? cookieLang
			: DEFAULT_LOCALE;
	const site = await resolveSite();
	return {
		lang,
		dateLocale: lang === "id" ? "id-ID" : "en-US",
		t: translations[lang] ?? translations[DEFAULT_LOCALE],
		site,
		nav: sagitaData.nav,
		languages: LANGUAGE_OPTIONS,
		contactLinks: {
			...getAlternatingWhatsAppContact(0),
			googleAuthUrl: siteContact.googleAuthUrl,
			loginUrl: `${env.BACKEND_URL}/auth/login`,
			backendBaseUrl: env.BACKEND_URL,
			dashboardUrl: `${env.BACKEND_URL}/backend/dashboard`,
		},
		currentPath: url.pathname,
		currentUrl: `${env.SITE_URL}${url.pathname}${lang === DEFAULT_LOCALE ? "" : `?lang=${lang}`}`,
		siteUrl: env.SITE_URL,
		assetV: env.ASSET_V,
		user: await resolveUser(cookieHeader || undefined),
		title: site.metaTitle || `${site.name} — ${site.tagline}`,
		seo: {
			description: site.metaDescription || site.description,
			keywords: site.metaKeywords,
			robots: "index, follow",
			ogImage: site.ogImage,
		},
		nonce,
	} as App.Locals;
}

async function readBody(nodeReq: any): Promise<Buffer | undefined> {
	const chunks: Buffer[] = [];
	for await (const chunk of nodeReq) {
		chunks.push(Buffer.isBuffer(chunk) ? chunk : Buffer.from(chunk));
	}
	if (!chunks.length) return undefined;
	return Buffer.concat(chunks);
}

export function createApp(): Server {
	return createServer(async (nodeReq, nodeRes) => {
		try {
			const host = nodeReq.headers.host || "127.0.0.1";
			const url = new URL(nodeReq.url || "/", `http://${host}`);
			const headers = new Headers();
			for (const [key, value] of Object.entries(nodeReq.headers)) {
				if (Array.isArray(value))
					for (const item of value) headers.append(key, item);
				else if (value !== undefined) headers.set(key, value);
			}
			const body = await readBody(nodeReq);
			const request = new Request(url, {
				method: nodeReq.method || "GET",
				headers,
				body: body as any,
			});
			const nonce = randomBytes(16).toString("base64");
			const locals = await buildLocals(request, url, nonce);
			const context = {
				request,
				url,
				locals,
				params: {},
				cookies: { get: () => undefined, set: () => {}, delete: () => {} },
				redirect: (location: string, status = 302) =>
					new Response(null, { status, headers: { Location: location } }),
				rewrite: async (location: string) => new Response(location),
				clientAddress: "127.0.0.1",
				site: new URL(env.SITE_URL),
				generator: "Astro",
				routePattern: url.pathname,
				originPathname: url.pathname,
			};

			let response: Response;
			const isApi = url.pathname.startsWith("/api/");
			if (isApi && !sameOriginAllowed(request)) {
				response = json(
					{ success: false, message: "Origin not allowed." },
					403,
				);
			} else if (isApi) {
				const handler = apiRoutes[url.pathname]?.[request.method];
				response = handler
					? await handler(context)
					: json({ success: false, message: "Not found." }, 404);
			} else if (endpointRoutes[url.pathname]) {
				response = await endpointRoutes[url.pathname](context);
			} else {
				let matched:
					| { pattern: RegExp; component: any; params?: string[] }
					| undefined;
				let match: RegExpMatchArray | null = null;
				for (const route of pageRoutes) {
					match = url.pathname.match(route.pattern);
					if (match) {
						matched = route;
						break;
					}
				}
				const container = await containerPromise;
				if (matched && match) {
					const params: Record<string, string> = {};
					matched.params?.forEach((name, index) => {
						params[name] = match?.[index + 1] ?? "";
					});
					response = await container.renderToResponse(matched.component, {
						request,
						locals,
						params,
						partial: false,
					});
					response.headers.set("Content-Type", "text/html; charset=utf-8");
				} else {
					response = await container.renderToResponse(NotFound, {
						request,
						locals,
						partial: false,
					});
					response.headers.set("Content-Type", "text/html; charset=utf-8");
				}
			}

			const isLiveness =
				url.pathname === "/healthz" || url.pathname === "/readyz";
			if (!isLiveness) {
				for (const [key, value] of Object.entries(securityHeaders(nonce))) {
					response.headers.set(key, value);
				}
			}
			const localeParam = url.searchParams.get("lang");
			if (isLocale(localeParam)) {
				response.headers.append(
					"Set-Cookie",
					`locale=${localeParam}; Path=/; Max-Age=31536000; HttpOnly; SameSite=Lax`,
				);
			}

			nodeRes.statusCode = response.status;
			response.headers.forEach((value, key) => {
				if (key !== "set-cookie") nodeRes.setHeader(key, value);
			});
			const setCookie = response.headers.getSetCookie?.() ?? [];
			if (setCookie.length) nodeRes.setHeader("Set-Cookie", setCookie);
			nodeRes.end(Buffer.from(await response.arrayBuffer()));
		} catch (error) {
			nodeRes.statusCode = 500;
			nodeRes.setHeader("Content-Type", "application/json; charset=utf-8");
			nodeRes.end(JSON.stringify({ success: false, message: String(error) }));
		}
	});
}
