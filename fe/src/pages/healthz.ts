import type { APIRoute } from "astro";
import { json } from "../lib/http.js";

const startedAt = Date.now();

export const GET: APIRoute = () =>
	json({
		status: "ok",
		uptime: Math.round((Date.now() - startedAt) / 1000),
	});
