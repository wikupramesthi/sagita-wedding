import type { APIRoute } from "astro";
import { clientIp, json, takeRateLimit } from "../../../lib/http.js";
import { getBatchContentUpdatedAt } from "../../../lib/services/batchService.js";
import { answerSitaQuestion } from "../../../lib/services/sitaService.js";
import { toApiError } from "../../../lib/utils/errors.js";
import { logger } from "../../../lib/utils/logger.js";
import { validateSitaChat } from "../../../lib/utils/validate.js";
import { limitHeaders, readRecord } from "../_shared.js";

export const POST: APIRoute = async ({ request, locals }) => {
	const limit = 15;
	const hit = takeRateLimit(`sita-chat:${clientIp(request)}`, limit);
	const headers = limitHeaders(hit, limit);
	if (!hit.ok) {
		return json(
			{
				success: false,
				message: "Too many chat messages, please try again shortly.",
			},
			429,
			headers,
		);
	}

	try {
		const { message, locale, context } = validateSitaChat(
			await readRecord(request),
		);
		const officeAddress = (locals.site as { address?: string } | undefined)
			?.address;
		const result = await answerSitaQuestion(message, locale, context, {
			officeAddress,
		});
		const contentUpdatedAt = getBatchContentUpdatedAt();
		const answer =
			result.source && contentUpdatedAt
				? {
						...result,
						source: { ...result.source, updatedAt: contentUpdatedAt },
					}
				: result;
		return json({ success: true, ...answer }, 200, headers);
	} catch (e) {
		const err = toApiError(e);
		const status = Number(err.status) || 503;
		const isClientError = status >= 400 && status < 500;
		if (!isClientError) {
			logger.error("sita.chat.failed", {
				error: err.message.slice(0, 200),
			});
		}
		return json(
			{
				success: false,
				message: isClientError
					? err.message || "Invalid chat question."
					: "Sagita data is temporarily unavailable. Please try again or contact the team.",
			},
			status,
			headers,
		);
	}
};
