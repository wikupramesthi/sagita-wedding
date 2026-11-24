import { createHash } from "node:crypto";
import { getSessionUser, hasSessionCookie } from "./services/authService.js";
import type { SessionUser } from "./types/index.js";

const SESSION_TTL_MS = 30_000;
const SESSION_CACHE_MAX = 500;

type SessionCacheEntry = { user: SessionUser | null; expiresAt: number };
const sessionCache = new Map<string, SessionCacheEntry>();

export function clearSessionCache(): void {
	sessionCache.clear();
}

export async function resolveUser(
	cookieHeader?: string,
): Promise<SessionUser | null> {
	if (!hasSessionCookie(cookieHeader)) return null;
	const cookie = cookieHeader as string;
	const key = createHash("sha256").update(cookie).digest("base64url");
	const hit = sessionCache.get(key);
	if (hit && hit.expiresAt > Date.now()) return hit.user;

	const user = await getSessionUser(cookie);
	if (sessionCache.size >= SESSION_CACHE_MAX) sessionCache.clear();
	sessionCache.set(key, { user, expiresAt: Date.now() + SESSION_TTL_MS });
	return user;
}
