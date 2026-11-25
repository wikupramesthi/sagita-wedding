import type { APIRoute } from "astro";
import { clientIp, empty, json, takeRateLimit } from "../../lib/http.js";
import {
	forwardSitaTelemetry,
	recordSitaTelemetry,
} from "../../lib/services/sitaAnalytics.js";
import { parseTelemetry } from "../../lib/telemetry.js";
import { logger } from "../../lib/utils/logger.js";
import { limitHeaders, readRecord } from "./_shared.js";

export const POST: APIRoute = async ({ request, clientAddress }) => {
	const limit = 40;
	const hit = takeRateLimit(
		`telemetry:${clientIp(request, clientAddress)}`,
		limit,
	);
	const headers = limitHeaders(hit, limit);
	if (!hit.ok) {
		return json(
			{
				success: false,
				message: "Too many telemetry events, please try again shortly.",
			},
			429,
			headers,
		);
	}

	const payload = parseTelemetry(await readRecord(request));
	if (!payload) {
		return json(
			{ success: false, message: "Invalid telemetry payload." },
			400,
			headers,
		);
	}
	if (payload.kind === "error") logger.error("client.error", payload);
	else if (payload.kind === "vital") logger.info("client.vital", payload);
	else {
		recordSitaTelemetry(payload);
		void forwardSitaTelemetry(payload).catch(() => {});
		logger.info("client.event", payload);
	}
	return empty(204, headers);
};
