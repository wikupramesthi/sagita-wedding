import type { BackendPayload, NewsletterPayload } from "../types/index.js";
import apiRequest from "./apiClient.js";

export async function subscribeNewsletter(
	data: NewsletterPayload,
): Promise<BackendPayload> {
	return await apiRequest("/newsletter", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify(data),
	});
}
