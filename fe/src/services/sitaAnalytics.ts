import { env } from "../config/env.js";
import type { TelemetryPayload } from "../routes/api.js";
import type { SitaChatCriteria } from "../utils/validate.js";

type SitaTopic = "packages" | "venues" | "vendors";
type SitaLeadTopic = SitaTopic | "services" | "other";

export interface SitaLeadPayload {
	name: string;
	phone: string;
	topic?: SitaLeadTopic;
	criteria?: SitaChatCriteria;
	score: number;
	message?: string;
	website?: string;
}

interface StoredSitaEvent {
	at: string;
	name: "sita.answer" | "sita.feedback" | "sita.no_match";
	topic?: SitaTopic;
	confidence?: number;
	fallback?: boolean;
	clarify?: boolean;
	helpful?: boolean;
	responseMs?: number;
	queryHash?: string;
}

interface QueryHashStat {
	queryHash: string;
	count: number;
	topic?: SitaTopic;
	lastAt: string;
}

const MAX_EVENTS = 500;
const BACKEND_TIMEOUT_MS = 2500;
const events: StoredSitaEvent[] = [];
const queryHashes = new Map<string, QueryHashStat>();
const TOPICS: SitaTopic[] = ["packages", "venues", "vendors"];

async function backendFetch(
	path: string,
	init: RequestInit = {},
): Promise<Response> {
	const controller = new AbortController();
	const timer = setTimeout(() => controller.abort(), BACKEND_TIMEOUT_MS);
	try {
		return await fetch(`${env.API_URL}${path}`, {
			...init,
			headers: {
				Accept: "application/json",
				"X-Api-Key": env.API_KEY,
				...(init.body ? { "Content-Type": "application/json" } : {}),
			},
			signal: controller.signal,
		});
	} finally {
		clearTimeout(timer);
	}
}

export async function forwardSitaTelemetry(
	payload: TelemetryPayload,
): Promise<void> {
	if (payload.kind !== "event") return;
	await backendFetch("/sita/events", {
		method: "POST",
		body: JSON.stringify(payload),
	});
}

export async function fetchSitaAnalytics(): Promise<Record<
	string,
	unknown
> | null> {
	const response = await backendFetch("/sita/analytics");
	if (!response.ok) return null;
	const body: unknown = await response.json();
	if (!body || typeof body !== "object" || Array.isArray(body)) return null;
	const data = body as Record<string, unknown>;
	return data.success === false ? null : data;
}

export async function fetchSitaSynonyms(): Promise<unknown[]> {
	const response = await backendFetch("/sita/synonyms");
	if (!response.ok) return [];
	const body: unknown = await response.json();
	if (!body || typeof body !== "object" || Array.isArray(body)) return [];
	const data = (body as Record<string, unknown>).data;
	return Array.isArray(data) ? data : [];
}

export async function createSitaLead(
	payload: SitaLeadPayload,
): Promise<string | null> {
	const response = await backendFetch("/sita/leads", {
		method: "POST",
		body: JSON.stringify(payload),
	});
	if (!response.ok) return null;
	const body: unknown = await response.json();
	if (!body || typeof body !== "object" || Array.isArray(body)) return null;
	const data = (body as Record<string, unknown>).data;
	if (!data || typeof data !== "object" || Array.isArray(data)) return null;
	const uuid = (data as Record<string, unknown>).uuid;
	return typeof uuid === "string" ? uuid : null;
}

function isTopic(value: unknown): value is SitaTopic {
	return TOPICS.includes(value as SitaTopic);
}

function percent(part: number, total: number): number {
	return total ? Math.round((part / total) * 1000) / 10 : 0;
}

function average(values: number[]): number {
	if (!values.length) return 0;
	return Math.round(
		values.reduce((sum, value) => sum + value, 0) / values.length,
	);
}

export function recordSitaTelemetry(payload: TelemetryPayload): void {
	if (payload.kind !== "event") return;
	if (
		payload.name !== "sita.answer" &&
		payload.name !== "sita.feedback" &&
		payload.name !== "sita.no_match"
	) {
		return;
	}

	const stored: StoredSitaEvent = {
		at: new Date().toISOString(),
		name: payload.name,
	};
	if ("topic" in payload && isTopic(payload.topic))
		stored.topic = payload.topic;
	if ("confidence" in payload && typeof payload.confidence === "number") {
		stored.confidence = payload.confidence;
	}
	if ("fallback" in payload && typeof payload.fallback === "boolean") {
		stored.fallback = payload.fallback;
	}
	if ("clarify" in payload && typeof payload.clarify === "boolean") {
		stored.clarify = payload.clarify;
	}
	if ("helpful" in payload && typeof payload.helpful === "boolean") {
		stored.helpful = payload.helpful;
	}
	if ("responseMs" in payload && typeof payload.responseMs === "number") {
		stored.responseMs = payload.responseMs;
	}
	if ("queryHash" in payload && typeof payload.queryHash === "string") {
		stored.queryHash = payload.queryHash;
	}

	events.push(stored);
	if (events.length > MAX_EVENTS) events.shift();

	if (payload.name === "sita.no_match" && stored.queryHash) {
		const previous = queryHashes.get(stored.queryHash);
		queryHashes.set(stored.queryHash, {
			queryHash: stored.queryHash,
			count: (previous?.count ?? 0) + 1,
			...(stored.topic
				? { topic: stored.topic }
				: previous?.topic
					? { topic: previous.topic }
					: {}),
			lastAt: stored.at,
		});
	}
}

export function getSitaAnalytics() {
	const answers = events.filter((event) => event.name === "sita.answer");
	const feedback = events.filter((event) => event.name === "sita.feedback");
	const noMatch = events.filter((event) => event.name === "sita.no_match");
	const helpful = feedback.filter((event) => event.helpful).length;
	const fallback = answers.filter((event) => event.fallback).length;
	const clarify = answers.filter((event) => event.clarify).length;
	const responseTimes = answers.flatMap((event) =>
		typeof event.responseMs === "number" ? [event.responseMs] : [],
	);

	return {
		generatedAt: new Date().toISOString(),
		window: { events: events.length, maxEvents: MAX_EVENTS },
		totals: {
			answers: answers.length,
			feedback: feedback.length,
			helpful,
			unhelpful: feedback.length - helpful,
			noMatch: noMatch.length,
			fallback,
			clarify,
			lowConfidence: answers.filter(
				(event) =>
					typeof event.confidence === "number" && event.confidence < 0.6,
			).length,
		},
		rates: {
			helpful: percent(helpful, feedback.length),
			fallback: percent(fallback, answers.length),
			noMatch: percent(noMatch.length, answers.length),
			clarify: percent(clarify, answers.length),
		},
		performance: { avgResponseMs: average(responseTimes) },
		topics: TOPICS.map((topic) => {
			const topicAnswers = answers.filter((event) => event.topic === topic);
			const topicFeedback = feedback.filter((event) => event.topic === topic);
			const topicHelpful = topicFeedback.filter(
				(event) => event.helpful,
			).length;
			return {
				topic,
				answers: topicAnswers.length,
				helpful: topicHelpful,
				unhelpful: topicFeedback.length - topicHelpful,
			};
		}),
		noMatchPatterns: [...queryHashes.values()]
			.sort((a, b) => b.count - a.count || b.lastAt.localeCompare(a.lastAt))
			.slice(0, 10),
		recent: events.slice(-20).reverse(),
	};
}

export function resetSitaAnalytics(): void {
	events.length = 0;
	queryHashes.clear();
}
