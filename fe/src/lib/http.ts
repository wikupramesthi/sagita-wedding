import { env } from "./config/env.js";

const GA_SCRIPT = "https://www.googletagmanager.com";
const GA_CONNECT = [
	"https://www.google-analytics.com",
	"https://*.google-analytics.com",
	"https://*.analytics.google.com",
	"https://www.googletagmanager.com",
];

const NO_STORE = "no-store";

export function json(
	data: unknown,
	status = 200,
	headers?: HeadersInit,
): Response {
	return new Response(JSON.stringify(data), {
		status,
		headers: {
			"Content-Type": "application/json; charset=utf-8",
			"Cache-Control": NO_STORE,
			...Object.fromEntries(new Headers(headers).entries()),
		},
	});
}

export function empty(status = 204, headers?: HeadersInit): Response {
	return new Response(null, {
		status,
		headers: {
			"Cache-Control": NO_STORE,
			...Object.fromEntries(new Headers(headers).entries()),
		},
	});
}

export async function readJson(request: Request): Promise<unknown> {
	try {
		return await request.json();
	} catch {
		return null;
	}
}

export function clientIp(request: Request): string {
	const forwarded = request.headers.get("x-forwarded-for");
	if (forwarded) return forwarded.split(",")[0].trim();
	return request.headers.get("x-real-ip") || "unknown";
}

const globalBucket = new Map<string, { count: number; resetAt: number }>();

export interface RateLimitResult {
	ok: boolean;
	remaining: number;
	resetAt: number;
}

export function takeRateLimit(
	key: string,
	limit: number,
	windowMs = 60_000,
	store = globalBucket,
): RateLimitResult {
	const now = Date.now();
	const entry = store.get(key);
	if (!entry || entry.resetAt <= now) {
		const resetAt = now + windowMs;
		store.set(key, { count: 1, resetAt });
		if (store.size > 2000) {
			for (const [k, v] of store) if (v.resetAt <= now) store.delete(k);
		}
		return { ok: true, remaining: limit - 1, resetAt };
	}
	entry.count += 1;
	return {
		ok: entry.count <= limit,
		remaining: Math.max(0, limit - entry.count),
		resetAt: entry.resetAt,
	};
}

export function rateLimitHeaders(
	result: RateLimitResult,
): Record<string, string> {
	return {
		"RateLimit-Limit": "20",
		"RateLimit-Remaining": String(result.remaining),
		"RateLimit-Reset": String(Math.ceil(result.resetAt / 1000)),
		"Retry-After": String(
			Math.max(1, Math.ceil((result.resetAt - Date.now()) / 1000)),
		),
	};
}

export function sameOriginAllowed(request: Request): boolean {
	if (!["POST", "PUT", "PATCH", "DELETE"].includes(request.method)) return true;
	const origin = request.headers.get("origin");
	if (!origin) return true;
	const host = request.headers.get("host");
	try {
		return new URL(origin).host === host;
	} catch {
		return false;
	}
}

export function securityHeaders(nonce: string): Record<string, string> {
	const csp = [
		"default-src 'self'",
		"base-uri 'self'",
		"frame-ancestors 'none'",
		"object-src 'none'",
		`script-src 'self' 'nonce-${nonce}' ${GA_SCRIPT}`,
		"script-src-attr 'none'",
		"style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
		"font-src 'self' https://fonts.gstatic.com",
		`img-src 'self' data: https: ${new URL(env.API_URL).origin}${env.IS_PROD ? "" : " http:"}`,
		"frame-src 'self' https://www.google.com https://maps.google.com https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com",
		`connect-src 'self' ${GA_CONNECT.join(" ")}`,
		"form-action 'self'",
		...(env.IS_PROD ? ["upgrade-insecure-requests"] : []),
	].join("; ");

	return {
		"Content-Security-Policy": csp,
		"Cross-Origin-Opener-Policy": "same-origin",
		"Cross-Origin-Resource-Policy": "same-origin",
		"Referrer-Policy": "strict-origin-when-cross-origin",
		"X-Content-Type-Options": "nosniff",
		"X-Frame-Options": "DENY",
		"Permissions-Policy": "camera=(), geolocation=(), microphone=()",
	};
}
