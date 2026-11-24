import { createHmac, randomUUID } from "node:crypto";
import { env } from "./config/env.js";
import { logger } from "./utils/logger.js";

const COOKIE_NAME = "sagita_visitor";
const BOT_USER_AGENT =
	/bot|crawler|spider|slurp|headless|lighthouse|pagespeed/i;
const UUID =
	/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;

export function shouldTrackVisit(request: Request, pathname: string): boolean {
	if (request.method !== "GET") return false;
	if (
		pathname.startsWith("/api") ||
		pathname === "/healthz" ||
		pathname === "/readyz"
	) {
		return false;
	}
	if (pathname === "/sitemap.xml" || pathname === "/robots.txt") return false;
	if (!request.headers.get("accept")?.includes("text/html")) return false;
	if (request.headers.get("dnt") === "1") return false;
	if (BOT_USER_AGENT.test(request.headers.get("user-agent") ?? ""))
		return false;
	return true;
}

export function visitorIdFromCookie(cookieHeader?: string): string | null {
	const entry = cookieHeader
		?.split(";")
		.map((part) => part.trim())
		.find((part) => part.startsWith(`${COOKIE_NAME}=`));
	const value = entry?.slice(COOKIE_NAME.length + 1);
	return value && UUID.test(value) ? value : null;
}

export function createVisitorCookie(visitorId: string): string {
	return [
		`${COOKIE_NAME}=${visitorId}`,
		"Path=/",
		"HttpOnly",
		"SameSite=Lax",
		...(env.IS_PROD ? ["Secure"] : []),
	].join("; ");
}

function deviceFromUserAgent(
	userAgent: string,
): "mobile" | "tablet" | "desktop" {
	if (/ipad|tablet|kindle|silk/i.test(userAgent)) return "tablet";
	if (/mobile|iphone|ipod|android/i.test(userAgent)) return "mobile";
	return "desktop";
}

export async function submitVisit(payload: {
	visitor_hash: string;
	path: string;
	device: "mobile" | "tablet" | "desktop";
}): Promise<void> {
	try {
		const response = await fetch(`${env.API_URL}/analytics/visits`, {
			method: "POST",
			headers: {
				Accept: "application/json",
				"Content-Type": "application/json",
				"X-Api-Key": env.API_KEY,
			},
			body: JSON.stringify(payload),
			signal: AbortSignal.timeout(3000),
		});
		if (!response.ok) {
			logger.warn("site analytics visit rejected", {
				path: payload.path,
				status: response.status,
			});
		}
	} catch (error) {
		logger.warn("site analytics visit could not be recorded", {
			path: payload.path,
			error: error instanceof Error ? error.message : "Unknown error",
		});
	}
}

export function buildVisitPayload(
	visitorId: string,
	path: string,
	userAgent: string,
) {
	return {
		visitor_hash: createHmac("sha256", env.API_KEY)
			.update(visitorId)
			.digest("hex"),
		path,
		device: deviceFromUserAgent(userAgent),
	};
}

export { randomUUID };
