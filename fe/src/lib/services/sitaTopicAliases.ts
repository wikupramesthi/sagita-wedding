import type { TopicAliases } from "./sitaNlu.js";

export type KnowledgeLocale = "id" | "en";

const MAX_ALIASES_PER_TOPIC = 96;

function asRecord(value: unknown): Record<string, unknown> | null {
	if (!value || typeof value !== "object" || Array.isArray(value)) return null;
	return value as Record<string, unknown>;
}

function normalizeToken(value: unknown): string {
	if (typeof value !== "string" && typeof value !== "number") return "";
	const token = String(value)
		.normalize("NFKD")
		.replace(/[\u0300-\u036f]/g, "")
		.toLocaleLowerCase()
		.match(/[a-z0-9]+/g)
		?.join("");
	return token &&
		token.length >= 2 &&
		token.length <= 32 &&
		!/^\d+$/.test(token)
		? token
		: "";
}

export function isUsableKnowledgeRecord(
	record: Record<string, unknown>,
	locale: KnowledgeLocale,
): boolean {
	const recordLocale =
		typeof record.locale === "string" ? record.locale.toLocaleLowerCase() : "";
	if (recordLocale && recordLocale !== locale && recordLocale !== "all") {
		return false;
	}
	return !(
		record.is_active === false ||
		record.is_active === 0 ||
		record.is_active === "0"
	);
}

export function topicAliasesFromRecords(
	records: readonly unknown[],
	locale: KnowledgeLocale,
): TopicAliases {
	const topics: Record<string, string[]> = {};
	for (const entry of records) {
		const record = asRecord(entry);
		if (!record || !isUsableKnowledgeRecord(record, locale)) continue;
		const topic =
			typeof record.topic === "string" ? record.topic.toLocaleLowerCase() : "";
		if (topic !== "packages" && topic !== "venues" && topic !== "vendors") {
			continue;
		}
		const tokens = [
			normalizeToken(record.term ?? record.alias ?? record.value),
			normalizeToken(record.canonical ?? record.key),
		].filter(Boolean);
		if (!tokens.length) continue;
		topics[topic] = [...new Set([...(topics[topic] ?? []), ...tokens])].slice(
			0,
			MAX_ALIASES_PER_TOPIC,
		);
	}
	return topics;
}

export function topicAliasesFromPayload(
	payload: unknown,
	locale: KnowledgeLocale,
): TopicAliases {
	const root = asRecord(payload) ?? {};
	const data = root.data ?? root;
	if (Array.isArray(data)) return topicAliasesFromRecords(data, locale);
	const nested = asRecord(data);
	if (!nested) return {};
	for (const key of [locale, "all", "aliases", "synonyms", "knowledge"]) {
		if (Array.isArray(nested[key])) {
			return topicAliasesFromRecords(nested[key] as unknown[], locale);
		}
	}
	return {};
}
