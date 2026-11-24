import type { APIRoute } from "astro";
import { clientIp, json, takeRateLimit } from "../../../lib/http.js";
import { createSitaLead } from "../../../lib/services/sitaAnalytics.js";
import { toApiError } from "../../../lib/utils/errors.js";
import { logger } from "../../../lib/utils/logger.js";
import { validateSitaLead } from "../../../lib/utils/validate.js";
import { limitHeaders, readRecord } from "../_shared.js";

export const POST: APIRoute = async ({ request }) => {
	const limit = 10;
	const hit = takeRateLimit(`sita-leads:${clientIp(request)}`, limit);
	const headers = limitHeaders(hit, limit);
	if (!hit.ok) {
		return json(
			{
				success: false,
				message: "Too many contact requests, please try again shortly.",
			},
			429,
			headers,
		);
	}

	try {
		const lead = validateSitaLead(await readRecord(request));
		if (lead.website) {
			return json(
				{ success: false, message: "Invalid lead payload." },
				400,
				headers,
			);
		}
		const uuid = await createSitaLead(lead);
		if (!uuid) {
			return json(
				{
					success: false,
					message: "Could not save your contact request. Please try WhatsApp.",
				},
				502,
				headers,
			);
		}
		return json({ success: true, uuid }, 201, headers);
	} catch (e) {
		const err = toApiError(e);
		const status = err.status ?? 400;
		if (status >= 500) {
			logger.error("sita.leads.failed", {
				error: err.message.slice(0, 200),
			});
		}
		return json(
			{
				success: false,
				message: err.message || "Invalid lead payload.",
			},
			status,
			headers,
		);
	}
};
