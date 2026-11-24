import type { APIRoute } from "astro";
import { json } from "../../lib/http.js";
import { subscribeNewsletter } from "../../lib/services/newsletterService.js";
import { toApiError } from "../../lib/utils/errors.js";
import { logger } from "../../lib/utils/logger.js";
import { validateNewsletter } from "../../lib/utils/validate.js";
import { readRecord } from "./_shared.js";

export const POST: APIRoute = async ({ request }) => {
	try {
		const result = await subscribeNewsletter(
			validateNewsletter(await readRecord(request)),
		);
		return json({
			success: true,
			message: result.message || "Email subscribed successfully.",
		});
	} catch (e) {
		const err = toApiError(e);
		const status = Number(err.status) || 500;
		const isClientError = status >= 400 && status < 500;
		if (!isClientError) {
			logger.error("newsletter.failed", { error: err.message.slice(0, 200) });
		}
		return json(
			{
				success: false,
				message: isClientError
					? err.message || "Failed to subscribe."
					: "Failed to subscribe.",
				errors: isClientError ? err.errors || null : null,
			},
			status,
		);
	}
};
