import { createHmac, randomUUID } from "node:crypto";
import { env } from "../config/env.js";
import type { NextFunction, Request, Response } from "../types/index.js";
import { logger } from "../utils/logger.js";

const COOKIE_NAME = "sagita_visitor";
const BOT_USER_AGENT =
	/bot|crawler|spider|slurp|headless|lighthouse|pagespeed/i;
const UUID =
	/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;

function visitorIdFromCookie(cookieHeader?: string): string | null {
	const entry = cookieHeader
		?.split(";")
		.map((part) => part.trim())
		.find((part) => part.startsWith(`${COOKIE_NAME}=`));
	const value = entry?.slice(COOKIE_NAME.length + 1);
	return value && UUID.test(value) ? value : null;
}

function deviceFromUserAgent(
	userAgent: string,
): "mobile" | "tablet" | "desktop" {
	if (/ipad|tablet|kindle|silk/i.test(userAgent)) return "tablet";
	if (/mobile|iphone|ipod|android/i.test(userAgent)) return "mobile";
	return "desktop";
}

async function submitVisit(payload: {
	visitor_hash: string;
	path: string;
	device: "mobile" | "tablet" | "desktop";
}): Promise<void> {
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
}

export function trackSiteVisit(
	req: Request,
	res: Response,
	next: NextFunction,
): void {
	const userAgent = req.get("user-agent") ?? "";
	if (
		req.method !== "GET" ||
		!req.accepts("html") ||
		req.get("DNT") === "1" ||
		BOT_USER_AGENT.test(userAgent)
	) {
		next();
		return;
	}

	const existingVisitorId = visitorIdFromCookie(req.headers.cookie);
	const visitorId = existingVisitorId ?? randomUUID();
	if (!existingVisitorId) {
		const cookie = [
			`${COOKIE_NAME}=${visitorId}`,
			"Path=/",
			"HttpOnly",
			"SameSite=Lax",
			...(env.IS_PROD ? ["Secure"] : []),
		].join("; ");
		res.append("Set-Cookie", cookie);
	}

	const visitorHash = createHmac("sha256", env.API_KEY)
		.update(visitorId)
		.digest("hex");
	const payload = {
		visitor_hash: visitorHash,
		path: req.path,
		device: deviceFromUserAgent(userAgent),
	};

	res.once("finish", () => {
		if (res.statusCode !== 200) return;
		void submitVisit(payload).catch((error: unknown) => {
			logger.warn("site analytics visit could not be recorded", {
				path: payload.path,
				error: error instanceof Error ? error.message : "Unknown error",
			});
		});
	});

	next();
}
