import type { APIRoute } from "astro";
import { json } from "../../lib/http.js";
import { logoutSession } from "../../lib/services/authService.js";
import { clearSessionCache } from "../../lib/session.js";

export const POST: APIRoute = async ({ request }) => {
	const ok = await logoutSession(request.headers.get("cookie") ?? undefined);
	if (!ok) {
		return json(
			{
				success: false,
				message: "Could not reach the sign-in service. Please try again.",
			},
			502,
		);
	}
	clearSessionCache();
	return json({ success: true, authenticated: false });
};
