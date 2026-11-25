import type { APIRoute } from "astro";
import { clientIp, json, takeRateLimit } from "../../../lib/http.js";
import { fetchSitaSynonyms } from "../../../lib/services/sitaAnalytics.js";
import { limitHeaders } from "../_shared.js";

export const GET: APIRoute = async ({ request, clientAddress }) => {
	const limit = 30;
	const hit = takeRateLimit(
		`sita-knowledge:${clientIp(request, clientAddress)}`,
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

	const synonyms = await fetchSitaSynonyms().catch(() => []);
	return json({ success: true, data: synonyms }, 200, headers);
};
