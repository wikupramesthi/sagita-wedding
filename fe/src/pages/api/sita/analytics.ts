import type { APIRoute } from "astro";
import { clientIp, json, takeRateLimit } from "../../../lib/http.js";
import {
	fetchSitaAnalytics,
	getSitaAnalytics,
} from "../../../lib/services/sitaAnalytics.js";
import { limitHeaders } from "../_shared.js";

export const GET: APIRoute = async ({ request, locals, clientAddress }) => {
	const limit = 30;
	const hit = takeRateLimit(
		`sita-analytics:${clientIp(request, clientAddress)}`,
		limit,
	);
	const headers = limitHeaders(hit, limit);
	if (!hit.ok) {
		return json(
			{
				success: false,
				message: "Too many requests, please try again later.",
			},
			429,
			headers,
		);
	}

	const roles = locals.user?.roles ?? [];
	if (!roles.some((role) => role === "admin" || role === "super-admin")) {
		return json(
			{
				success: false,
				message: "Sita analytics is available for administrators only.",
			},
			403,
			headers,
		);
	}

	const analytics = await fetchSitaAnalytics().catch(() => null);
	return json(
		analytics ?? { success: true, ...getSitaAnalytics() },
		200,
		headers,
	);
};
