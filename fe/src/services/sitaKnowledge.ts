export type SitaKnowledgeLocale = "id" | "en";
export type SitaAliases = Record<string, string[]>;

interface RuntimeGlobal {
	location?: { origin?: string };
	process?: { env?: Record<string, string | undefined> };
}

interface KnowledgeCacheEntry {
	aliases: SitaAliases;
	expiresAt: number;
}

const KNOWLEDGE_PATH = "/api/sita/knowledge";
const CACHE_TTL_MS = 5 * 60 * 1000;
const FETCH_TIMEOUT_MS = 750;
const MAX_ALIAS_KEYS = 160;
const MAX_ALIASES_PER_KEY = 12;

const BASE_ALIASES: SitaAliases = {
	biaya: [
		"anggaran",
		"biaya",
		"budget",
		"cost",
		"costs",
		"fee",
		"harga",
		"hrg",
		"hrga",
		"price",
		"prices",
		"pricing",
		"tarif",
	],
	booking: [
		"book",
		"booking",
		"daftar",
		"mendaftar",
		"order",
		"pemesanan",
		"pesan",
		"register",
		"reservasi",
		"signup",
	],
	catering: ["catring", "catering", "katering"],
	fotografi: ["fotografer", "fotografi", "photographer", "photography"],
	layanan: ["jasa", "layanan", "service", "services"],
	paket: ["pack", "package", "packages", "paket", "pket", "pkg", "pkt"],
	pernikahan: ["pernikahan", "wedding"],
	vendor: ["vendor", "vendors"],
	venue: [
		"gedung",
		"lokasi",
		"place",
		"places",
		"tempat",
		"venu",
		"venue",
		"venuez",
		"venues",
	],
};

const PROTECTED_TERMS = new Set([
	"admin",
	"alamat",
	"contact",
	"diskon",
	"hubungi",
	"kantor",
	"membership",
	"meditation",
	"office",
	"promo",
	"promosi",
	"review",
	"testimoni",
	"testimonial",
	"ulasan",
	"voucher",
	"whatsapp",
	"yoga",
	"wellness",
]);

const BUILT_IN_ALIASES: Record<SitaKnowledgeLocale, SitaAliases> = {
	en: cloneAliases(BASE_ALIASES),
	id: cloneAliases(BASE_ALIASES),
};

const cache = new Map<SitaKnowledgeLocale, KnowledgeCacheEntry>();
const inflight = new Map<SitaKnowledgeLocale, Promise<SitaAliases>>();

function cloneAliases(
	source: Readonly<Record<string, readonly string[]>>,
): SitaAliases {
	return Object.fromEntries(
		Object.entries(source).map(([key, values]) => [key, [...values]]),
	);
}

function asRecord(value: unknown): Record<string, unknown> | null {
	if (!value || typeof value !== "object" || Array.isArray(value)) return null;
	return value as Record<string, unknown>;
}

function normalizeKnowledgeToken(value: unknown): string {
	if (typeof value !== "string" && typeof value !== "number") return "";
	const token =
		String(value)
			.normalize("NFKD")
			.replace(/[\u0300-\u036f]/g, "")
			.toLocaleLowerCase()
			.match(/[a-z0-9]+/g)
			?.join("") ?? "";
	return token.length >= 2 &&
		token.length <= 32 &&
		!/^\d+$/.test(token) &&
		!PROTECTED_TERMS.has(token)
		? token
		: "";
}

function aliasesFromRecords(
	records: readonly unknown[],
	locale: SitaKnowledgeLocale,
): Record<string, unknown> {
	const aliases: Record<string, string[]> = {};
	for (const entry of records) {
		const record = asRecord(entry);
		if (!record) continue;
		const recordLocale =
			typeof record.locale === "string"
				? record.locale.toLocaleLowerCase()
				: "";
		if (recordLocale && recordLocale !== locale && recordLocale !== "all") {
			continue;
		}
		if (
			record.is_active === false ||
			record.is_active === 0 ||
			record.is_active === "0"
		) {
			continue;
		}
		const term = normalizeKnowledgeToken(
			record.term ?? record.alias ?? record.value,
		);
		if (!term) continue;
		const canonical =
			normalizeKnowledgeToken(record.canonical ?? record.key) ?? term;
		aliases[canonical] = [...(aliases[canonical] ?? []), term];
	}
	return aliases;
}

function asAliasMap(
	value: unknown,
	locale: SitaKnowledgeLocale,
): Record<string, unknown> | null {
	if (Array.isArray(value)) return aliasesFromRecords(value, locale);
	const record = asRecord(value);
	if (!record) return null;
	for (const key of [locale, "all", "aliases", "synonyms", "knowledge"]) {
		const nested = record[key];
		if (Array.isArray(nested)) return aliasesFromRecords(nested, locale);
		const nestedMap = asRecord(nested);
		if (nestedMap) {
			return asAliasMap(nestedMap, locale) ?? nestedMap;
		}
	}
	return record;
}

function backendAliases(
	payload: unknown,
	locale: SitaKnowledgeLocale,
): Record<string, unknown> {
	const root = asRecord(payload) ?? {};
	return asAliasMap(root.data ?? root, locale) ?? {};
}

function valuesFrom(value: unknown): string[] {
	if (typeof value === "string") {
		return value
			.split(/[,|;]/)
			.map((part) => normalizeKnowledgeToken(part))
			.filter(Boolean);
	}
	if (!Array.isArray(value)) return [];
	return value.flatMap((entry) => {
		if (typeof entry === "string" || typeof entry === "number") {
			const token = normalizeKnowledgeToken(entry);
			return token ? [token] : [];
		}
		const record = asRecord(entry);
		const token = normalizeKnowledgeToken(
			record?.alias ?? record?.name ?? record?.value ?? record?.term,
		);
		return token ? [token] : [];
	});
}

function canonicalLookup(source: SitaAliases): Map<string, string> {
	const lookup = new Map<string, string>();
	for (const [canonical, aliases] of Object.entries(source)) {
		if (!lookup.has(canonical)) lookup.set(canonical, canonical);
		for (const alias of aliases) {
			if (!lookup.has(alias)) lookup.set(alias, canonical);
		}
	}
	return lookup;
}

function mergeBackendAliases(
	base: SitaAliases,
	backend: Record<string, unknown>,
): SitaAliases {
	const merged = cloneAliases(base);
	const lookup = canonicalLookup(base);
	for (const [rawKey, rawValues] of Object.entries(backend)) {
		const key = normalizeKnowledgeToken(rawKey);
		if (!key) continue;
		const canonical = lookup.get(key) ?? key;
		const values = valuesFrom(rawValues);
		if (!values.length) continue;
		if (!merged[canonical] && Object.keys(merged).length >= MAX_ALIAS_KEYS) {
			continue;
		}
		merged[canonical] = [
			...new Set([...(merged[canonical] ?? []), ...values]),
		].slice(0, MAX_ALIASES_PER_KEY);
	}
	return merged;
}

function knowledgeEndpoint(): string {
	const runtime = globalThis as RuntimeGlobal;
	const origin =
		runtime.location?.origin || runtime.process?.env?.SITE_URL || "";
	if (!origin) return KNOWLEDGE_PATH;
	try {
		return new URL(KNOWLEDGE_PATH, origin).toString();
	} catch {
		return KNOWLEDGE_PATH;
	}
}

export function builtInAliases(locale: SitaKnowledgeLocale): SitaAliases {
	return cloneAliases(BUILT_IN_ALIASES[locale]);
}

export async function getDynamicAliases(
	locale: SitaKnowledgeLocale,
): Promise<SitaAliases> {
	const cached = cache.get(locale);
	if (cached && cached.expiresAt > Date.now()) {
		return cloneAliases(cached.aliases);
	}
	const pending = inflight.get(locale);
	if (pending) return pending;

	const task = (async () => {
		let aliases = builtInAliases(locale);
		const controller = new AbortController();
		const timer = setTimeout(() => controller.abort(), FETCH_TIMEOUT_MS);
		try {
			const response = await fetch(knowledgeEndpoint(), {
				headers: { Accept: "application/json" },
				signal: controller.signal,
			});
			if (response.ok) {
				const payload: unknown = await response.json();
				aliases = mergeBackendAliases(aliases, backendAliases(payload, locale));
			}
		} catch {
			aliases = builtInAliases(locale);
		} finally {
			clearTimeout(timer);
		}
		cache.set(locale, { aliases, expiresAt: Date.now() + CACHE_TTL_MS });
		return cloneAliases(aliases);
	})();

	inflight.set(locale, task);
	try {
		return await task;
	} finally {
		inflight.delete(locale);
	}
}
