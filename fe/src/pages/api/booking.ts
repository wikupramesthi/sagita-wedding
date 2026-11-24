import type { APIRoute } from "astro";
import { json } from "../../lib/http.js";
import { sendBooking } from "../../lib/services/contactService.js";
import { toApiError } from "../../lib/utils/errors.js";
import { logger } from "../../lib/utils/logger.js";
import { validateBooking } from "../../lib/utils/validate.js";
import { readRecord } from "./_shared.js";

export const POST: APIRoute = async ({ request }) => {
	try {
		const result = await sendBooking(
			validateBooking(await readRecord(request)),
		);
		return json({
			success: true,
			message: result.message || "Permintaan booking berhasil dikirim.",
			duplicate: Boolean(result.duplicate),
		});
	} catch (e) {
		const err = toApiError(e);
		const status = Number(err.status) || 500;
		const isClientError = status >= 400 && status < 500;
		if (!isClientError) {
			logger.error("booking.failed", { error: err.message.slice(0, 200) });
		}
		return json(
			{
				success: false,
				message: isClientError
					? err.message || "Failed to send booking request."
					: "Failed to send booking request.",
				errors: isClientError ? err.errors || null : null,
			},
			status,
		);
	}
};
