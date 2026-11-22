import type {
	BackendPayload,
	BookingPayload,
	ContactPayload,
} from "../types/index.js";
import apiRequest from "./apiClient.js";

export async function getContactCaptcha(lang = "id"): Promise<BackendPayload> {
	return await apiRequest(`/contact/captcha?lang=${encodeURIComponent(lang)}`);
}

export async function sendContact(
	data: ContactPayload,
): Promise<BackendPayload> {
	return await apiRequest("/contact", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify(data),
	});
}

export async function sendBooking(
	data: BookingPayload,
): Promise<BackendPayload> {
	const payload: ContactPayload = {
		nama: data.nama,
		email: data.email,
		no_telp: data.no_telp,
		isi: data.isi,
		captcha_id: data.captcha_id,
		captcha_answer: data.captcha_answer,
	};

	return await sendContact(payload);
}
