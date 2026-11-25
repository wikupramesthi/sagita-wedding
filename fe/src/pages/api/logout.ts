import type { APIRoute } from "astro";
import { json } from "../../lib/http.js";
import { logoutSession } from "../../lib/services/authService.js";
import { invalidateSessionCache } from "../../lib/session.js";

export const POST: APIRoute = async ({ request }) => {
	const cookie = request.headers.get("cookie") ?? undefined;
	const ok = await logoutSession(cookie);
	if (!ok) {
		return json(
			{
				success: false,
				message: "Could not reach the sign-in service. Please try again.",
			},
			502,
		);
	}
	invalidateSessionCache(cookie);
	return json({ success: true, authenticated: false });
};
