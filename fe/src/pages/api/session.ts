import type { APIRoute } from "astro";
import { json } from "../../lib/http.js";

export const GET: APIRoute = async ({ locals }) => {
	const user = locals.user;
	return json({ authenticated: !!user, user }, user ? 200 : 401);
};
