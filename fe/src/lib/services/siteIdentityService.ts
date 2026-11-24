import { env } from "../config/env.js";
import { sagitaData } from "../data/sagitaData.js";
import type { BackendPayload, SiteIdentity } from "../types/index.js";

/**
 * Identitas website dari backend (dikelola via /backend/website-identity).
 * - Timeout pendek (3,5 dtk): identitas tidak boleh menghambat render halaman.
 * - ETag + no-cache: setiap request direvalidasi, perubahan admin langsung dipakai.
 * - Selalu ada fallback (data statis) supaya halaman tetap render saat BE mati.
 */

const FALLBACK: SiteIdentity = {
	name: sagitaData.site.name,
	title: sagitaData.site.name,
	tagline: sagitaData.site.tagline,
	description: sagitaData.site.description,
	phone: sagitaData.site.phone,
	phoneTel: sagitaData.site.phone,
	email: sagitaData.site.email,
	address: sagitaData.site.address,
	instagramHandle: sagitaData.site.instagram,
	instagramHref: sagitaData.site.instagram
		? `https://www.instagram.com/${sagitaData.site.instagram}/`
		: "",
	facebookHref: "",
	youtubeHref: "",
	tiktokHref: "",
	metaTitle: "",
	metaDescription: "",
	metaKeywords: "",
	gaId: "",
	siteVerification: "",
	logoUrl: "/img/logo.WebP",
	logoWhiteUrl: "/img/logo.WebP",
	faviconUrl: "/img/fav.png",
	ogImage: "/img/logo.WebP",
	year: new Date().getFullYear(),
};

let cache: { etag?: string; data: SiteIdentity | null } = { data: null };
let inflight: Promise<SiteIdentity> | null = null;

type IdentityFetchResult =
	| { notModified: true; etag?: string }
	| { notModified: false; etag?: string; payload: unknown };

function text(value: unknown, fallback = ""): string {
	const s = String(value ?? "").trim();
	return s || fallback;
}

function instagramHandleFrom(url: string, fallback: string): string {
	const m = String(url || "").match(/instagram\.com\/([A-Za-z0-9._]+)/i);
	return m ? m[1] : fallback;
}

export function normalizeIdentity(
	api: Record<string, unknown> = {},
): SiteIdentity {
	const fallback = FALLBACK;
	const instagramHref = text(api.instagram_url);
	const phone = text(api.phone, fallback.phone);
	const logoUrl = text(api.logo_url, fallback.logoUrl);
	const yearValue = Number(api.year);

	return {
		name: text(api.site_name, fallback.name),
		title: text(api.site_title, fallback.title),
		tagline: text(api.tagline, fallback.tagline),
		description: text(api.short_description, fallback.description),
		phone,
		phoneTel: phone.replace(/[^\d+]/g, "") || fallback.phoneTel,
		email: text(api.email, fallback.email),
		address: text(api.address, fallback.address),
		instagramHandle: instagramHref
			? instagramHandleFrom(instagramHref, fallback.instagramHandle)
			: fallback.instagramHandle,
		instagramHref: instagramHref || fallback.instagramHref,
		facebookHref: text(api.facebook_url),
		youtubeHref: text(api.youtube_url),
		tiktokHref: text(api.tiktok_url),
		metaTitle: text(api.meta_title),
		metaDescription: text(api.meta_description),
		metaKeywords: text(api.meta_keywords),
		gaId: text(api.google_analytics_id),
		siteVerification: text(api.google_site_verification),
		logoUrl,
		logoWhiteUrl: text(
			api.logo_white_url,
			text(api.logo_url, fallback.logoWhiteUrl),
		),
		faviconUrl: text(api.favicon_url, fallback.faviconUrl),
		ogImage: text(api.og_image_url, fallback.ogImage),
		year:
			Number.isInteger(yearValue) && yearValue > 0 ? yearValue : fallback.year,
	};
}

export function defaultSite(): SiteIdentity {
	return { ...FALLBACK };
}

export async function getSiteIdentity(): Promise<SiteIdentity> {
	if (!inflight) {
		inflight = fetchIdentity(cache.etag)
			.then((result): SiteIdentity => {
				if (result.notModified && cache.data) return cache.data;
				if (result.notModified) return defaultSite();

				const normalized = normalizeIdentity(
					(result.payload ?? {}) as Record<string, unknown>,
				);
				cache = { etag: result.etag, data: normalized };
				return normalized;
			})
			.catch((error): SiteIdentity => {
				if (cache.data) return cache.data;
				throw error;
			})
			.finally(() => {
				inflight = null;
			});
	}
	return inflight;
}

async function fetchIdentity(etag?: string): Promise<IdentityFetchResult> {
	const controller = new AbortController();
	const timer = setTimeout(() => controller.abort(), 3_500);
	const headers: Record<string, string> = {
		Accept: "application/json",
		"X-Api-Key": env.API_KEY,
		Authorization: `Bearer ${env.API_KEY}`,
	};
	if (etag) headers["If-None-Match"] = etag;

	try {
		const res = await fetch(`${env.API_URL}/website-identity`, {
			signal: controller.signal,
			headers,
		});
		if (res.status === 304) return { notModified: true, etag };
		if (!res.ok) throw new Error(`Identity API: ${res.status}`);
		const json: BackendPayload = await res.json();
		return {
			notModified: false,
			etag: res.headers.get("etag") ?? etag,
			payload: json.data ?? json,
		};
	} finally {
		clearTimeout(timer);
	}
}

export async function resolveSite(): Promise<SiteIdentity> {
	try {
		return await getSiteIdentity();
	} catch {
		return defaultSite();
	}
}
