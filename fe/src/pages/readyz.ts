import type { APIRoute } from "astro";
import { env } from "../lib/config/env.js";
import { json } from "../lib/http.js";
import { toApiError } from "../lib/utils/errors.js";
import { logger } from "../lib/utils/logger.js";

export const GET: APIRoute = async () => {
	const controller = new AbortController();
	const timer = setTimeout(() => controller.abort(), 3_000);
	try {
		const r = await fetch(`${env.API_URL}/site-stats`, {
			signal: controller.signal,
			headers: {
				Accept: "application/json",
				"X-Api-Key": env.API_KEY,
				Authorization: `Bearer ${env.API_KEY}`,
			},
		});
		return json(
			{ status: r.ok ? "ok" : "degraded", backend: r.ok },
			r.ok ? 200 : 503,
		);
	} catch (e) {
		logger.error("readyz.failed", {
			error: toApiError(e).message.slice(0, 200),
		});
		return json({ status: "degraded", backend: false }, 503);
	} finally {
		clearTimeout(timer);
	}
};
