import type { APIRoute } from "astro";
import {
	json,
	type RateLimitResult,
	rateLimitHeaders,
	readJson,
} from "../../lib/http.js";
import { getContactCaptcha } from "../../lib/services/contactService.js";

export async function readRecord(
	request: Request,
): Promise<Record<string, unknown>> {
	const value = await readJson(request);
	if (!value || typeof value !== "object" || Array.isArray(value)) return {};
	return value as Record<string, unknown>;
}

export function limitHeaders(
	result: RateLimitResult,
	limit: number,
): Record<string, string> {
	return { ...rateLimitHeaders(result), "RateLimit-Limit": String(limit) };
}

export const gone: APIRoute = () =>
	json(
		{
			success: false,
			message: "Class and schedule endpoints are no longer available.",
		},
		410,
	);

export const captcha: APIRoute = async ({ url }) => {
	try {
		const captcha = await getContactCaptcha(
			url.searchParams.get("lang") === "en" ? "en" : "id",
		);
		return json({
			success: true,
			captcha_id: String(captcha?.captcha_id ?? ""),
			question: String(captcha?.question ?? ""),
			image: String(captcha?.image ?? ""),
		});
	} catch {
		return json(
			{
				success: false,
				message: "Verification unavailable, please reload the page.",
			},
			503,
		);
	}
};
