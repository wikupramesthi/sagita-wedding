import { Buffer } from "node:buffer";
import { isIP } from "node:net";
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

const MAX_JSON_BYTES = 64 * 1024;

export async function readJson(request: Request): Promise<unknown> {
	const declared = Number(request.headers.get("content-length"));
	if (Number.isFinite(declared) && declared > MAX_JSON_BYTES) return null;
	const reader = request.body?.getReader();
	if (!reader) return null;
	const chunks: Buffer[] = [];
	let size = 0;
	try {
		while (true) {
			const { done, value } = await reader.read();
			if (done) break;
			size += value.byteLength;
			if (size > MAX_JSON_BYTES) {
				await reader.cancel();
				return null;
			}
			chunks.push(Buffer.from(value));
		}
	} catch {
		return null;
	}
	try {
		return JSON.parse(Buffer.concat(chunks).toString("utf8"));
	} catch {
		return null;
	}
}

function isLoopback(ip: string): boolean {
	return ip === "::1" || ip === "::ffff:127.0.0.1" || ip.startsWith("127.");
}

function ipHeaders(value: string | null): string[] {
	return (value ?? "")
		.split(",")
		.map((item) => item.trim())
		.filter((item) => isIP(item));
}

export function clientIp(request: Request, directIp?: string): string {
	const direct = directIp && isIP(directIp) ? directIp : "";
	const forwarded = ipHeaders(request.headers.get("x-forwarded-for"));
	const real = ipHeaders(request.headers.get("x-real-ip"));
	if (direct && isLoopback(direct)) {
		return forwarded.at(-1) ?? real.at(-1) ?? direct;
	}
	return direct || real.at(-1) || "unknown";
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
			const excess = store.size - 2000;
			if (excess > 0) {
				const keys = store.keys();
				for (let i = 0; i < excess; i += 1) {
					const key = keys.next().value;
					if (key === undefined) break;
					store.delete(key);
				}
			}
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
	limit = 20,
): Record<string, string> {
	const retryAfter = Math.max(
		1,
		Math.ceil((result.resetAt - Date.now()) / 1000),
	);
	return {
		"RateLimit-Limit": String(limit),
		"RateLimit-Remaining": String(result.remaining),
		"RateLimit-Reset": String(retryAfter),
		"Retry-After": String(retryAfter),
	};
}

export function sameOriginAllowed(request: Request): boolean {
	if (!["POST", "PUT", "PATCH", "DELETE"].includes(request.method)) return true;
	const origin = request.headers.get("origin");
	if (!origin) return true;
	try {
		const originUrl = new URL(origin);
		if (env.IS_PROD) return originUrl.origin === new URL(env.SITE_URL).origin;
		return originUrl.host === request.headers.get("host");
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
		`script-src 'self' 'nonce-${nonce}' ${GA_SCRIPT} https://www.tiktok.com https://*.ttwstatic.com`,
		"script-src-attr 'none'",
		"style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
		"font-src 'self' https://fonts.gstatic.com",
		`img-src 'self' data: https: ${new URL(env.API_URL).origin}${env.IS_PROD ? "" : " http:"}`,
		"frame-src 'self' https://www.google.com https://maps.google.com https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com https://www.tiktok.com",
		`connect-src 'self' ${GA_CONNECT.join(" ")} https://www.tiktok.com`,
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
