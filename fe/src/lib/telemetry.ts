import { sanitizeSitaQuerySample } from "./utils/sitaQuery.js";

type SitaTopic = "packages" | "venues" | "vendors";
type SitaLocale = "id" | "en";

export type TelemetryPayload =
	| { kind: "error"; name: string; path: string; message: string }
	| { kind: "vital"; name: string; path: string; value: number }
	| {
			kind: "event";
			name: "sita.no_match";
			path: "/";
			topic?: SitaTopic;
			locale?: SitaLocale;
			queryHash?: string;
			sample?: string;
			fallback?: boolean;
	  }
	| {
			kind: "event";
			name: "sita.answer";
			path: "/";
			topic?: SitaTopic;
			locale?: SitaLocale;
			confidence: number;
			fallback: boolean;
			clarify: boolean;
			responseMs: number;
			queryHash?: string;
			turnId?: string;
	  }
	| {
			kind: "event";
			name: "sita.feedback";
			path: "/";
			helpful: boolean;
			turnId?: string;
			topic?: SitaTopic;
			locale?: SitaLocale;
	  };

function cleanSitaTopic(value: unknown): SitaTopic | undefined {
	return value === "packages" || value === "venues" || value === "vendors"
		? value
		: undefined;
}

function cleanSitaLocale(value: unknown): SitaLocale | undefined {
	return value === "id" || value === "en" ? value : undefined;
}

function cleanTurnId(value: unknown): string | undefined {
	return typeof value === "string"
		? value.replace(/[^a-zA-Z0-9_-]/g, "").slice(0, 80) || undefined
		: undefined;
}

function cleanHash(value: unknown): string | undefined {
	return typeof value === "string" && /^[a-z0-9]{1,32}$/.test(value)
		? value
		: undefined;
}

export function parseTelemetry(value: unknown): TelemetryPayload | null {
	if (!value || typeof value !== "object" || Array.isArray(value)) return null;
	const item = value as Record<string, unknown>;

	if (item.kind === "event") {
		const topic = cleanSitaTopic(item.topic);
		const locale = cleanSitaLocale(item.locale);
		const turnId = cleanTurnId(item.turnId);

		if (item.name === "sita.no_match") {
			const sample =
				typeof item.sample === "string"
					? sanitizeSitaQuerySample(item.sample)
					: "";
			return {
				kind: "event",
				name: "sita.no_match",
				path: "/",
				...(topic ? { topic } : {}),
				...(locale ? { locale } : {}),
				...(cleanHash(item.queryHash)
					? { queryHash: cleanHash(item.queryHash) }
					: {}),
				...(sample ? { sample } : {}),
				...(typeof item.fallback === "boolean"
					? { fallback: item.fallback }
					: {}),
			};
		}

		if (item.name === "sita.answer") {
			const confidence = Number(item.confidence);
			const responseMs = Number(item.responseMs);
			if (
				!Number.isFinite(confidence) ||
				confidence < 0 ||
				confidence > 1 ||
				!Number.isFinite(responseMs) ||
				responseMs < 0 ||
				responseMs > 600000 ||
				typeof item.fallback !== "boolean" ||
				typeof item.clarify !== "boolean"
			) {
				return null;
			}
			return {
				kind: "event",
				name: "sita.answer",
				path: "/",
				confidence,
				fallback: item.fallback,
				clarify: item.clarify,
				responseMs,
				...(topic ? { topic } : {}),
				...(locale ? { locale } : {}),
				...(cleanHash(item.queryHash)
					? { queryHash: cleanHash(item.queryHash) }
					: {}),
				...(turnId ? { turnId } : {}),
			};
		}

		if (item.name === "sita.feedback" && typeof item.helpful === "boolean") {
			return {
				kind: "event",
				name: "sita.feedback",
				path: "/",
				helpful: item.helpful,
				...(turnId ? { turnId } : {}),
				...(topic ? { topic } : {}),
				...(locale ? { locale } : {}),
			};
		}
		return null;
	}

	if (item.kind !== "error" && item.kind !== "vital") return null;
	const name =
		typeof item.name === "string"
			? item.name.replace(/[^a-zA-Z0-9_.:-]/g, "").slice(0, 80)
			: "";
	const path =
		typeof item.path === "string" && item.path.startsWith("/")
			? item.path.slice(0, 200)
			: "";
	if (!name || !path) return null;

	if (item.kind === "vital") {
		const metric = Number(item.value);
		if (!Number.isFinite(metric) || metric < 0 || metric > 600000) return null;
		return {
			kind: "vital",
			name,
			path,
			value: Math.round(metric * 100) / 100,
		};
	}

	const message =
		typeof item.message === "string"
			? item.message
					.replace(/\S+@\S+/g, "[email]")
					.replace(/[A-Za-z0-9_-]{32,}/g, "[token]")
					.slice(0, 200)
			: "";
	return { kind: "error", name, path, message };
}
