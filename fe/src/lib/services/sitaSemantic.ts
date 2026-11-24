export interface SemanticMatch {
	index: number;
	score: number;
}

export const SEMANTIC_MATCH_THRESHOLD = 0.34;

type AliasInput =
	| Readonly<Record<string, readonly string[]>>
	| ReadonlyMap<string, string>;

const STOP_WORDS = new Set([
	"ada",
	"adalah",
	"akan",
	"anda",
	"apakah",
	"apa",
	"bagaimana",
	"berapa",
	"bisa",
	"buat",
	"can",
	"could",
	"dan",
	"dapat",
	"dari",
	"di",
	"dengan",
	"do",
	"does",
	"for",
	"from",
	"gimana",
	"how",
	"i",
	"ini",
	"itu",
	"is",
	"ke",
	"kami",
	"mau",
	"of",
	"oleh",
	"on",
	"saja",
	"saya",
	"sebaiknya",
	"sebuah",
	"sebelum",
	"sesuai",
	"sudah",
	"telah",
	"tersebut",
	"the",
	"to",
	"untuk",
	"we",
	"what",
	"when",
	"where",
	"which",
	"with",
	"ya",
	"yang",
	"you",
	"your",
]);

const DEFAULT_ALIASES: Readonly<Record<string, readonly string[]>> = {
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
		"reservasi",
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

function normalizeToken(value: string): string {
	return (
		value
			.normalize("NFKD")
			.replace(/[\u0300-\u036f]/g, "")
			.toLocaleLowerCase()
			.match(/[a-z0-9]+/g)
			?.join("") ?? ""
	);
}

function addAliases(
	resolved: Map<string, string>,
	source: Readonly<Record<string, readonly string[]>>,
): void {
	for (const [key, values] of Object.entries(source)) {
		const canonical = normalizeToken(key);
		if (!canonical) continue;
		if (!resolved.has(canonical)) resolved.set(canonical, canonical);
		for (const value of values) {
			const alias = normalizeToken(value);
			if (alias && !resolved.has(alias)) resolved.set(alias, canonical);
		}
	}
}

function resolveAliases(aliases?: AliasInput): ReadonlyMap<string, string> {
	const resolved = new Map<string, string>();
	addAliases(resolved, DEFAULT_ALIASES);
	if (aliases instanceof Map) {
		for (const [key, value] of aliases) {
			const canonical = normalizeToken(key);
			const alias = normalizeToken(value);
			if (canonical && alias) resolved.set(alias, canonical);
		}
	} else if (aliases) {
		addAliases(
			resolved,
			aliases as Readonly<Record<string, readonly string[]>>,
		);
	}
	return resolved;
}

function semanticTokens(
	value: string,
	aliases: ReadonlyMap<string, string>,
): string[] {
	return (
		value
			.normalize("NFKD")
			.replace(/[\u0300-\u036f]/g, "")
			.toLocaleLowerCase()
			.match(/[a-z0-9]+/g)
			?.map((word) => aliases?.get(word) ?? word)
			.filter((word) => word.length > 1 && !STOP_WORDS.has(word)) ?? []
	);
}

function invertedFrequency(tokens: string[]): Map<string, number> {
	const counts = new Map<string, number>();
	for (const token of tokens) counts.set(token, (counts.get(token) ?? 0) + 1);
	return counts;
}

function termVector(
	tokens: string[],
	documentFrequency: ReadonlyMap<string, number>,
	documentCount: number,
): Map<string, number> {
	const vector = new Map<string, number>();
	for (const [term, count] of invertedFrequency(tokens)) {
		const frequency = 1 + Math.log(count);
		const inverse =
			Math.log((documentCount + 1) / ((documentFrequency.get(term) ?? 0) + 1)) +
			1;
		vector.set(term, frequency * inverse);
	}
	return vector;
}

function cosineSimilarity(
	left: ReadonlyMap<string, number>,
	right: ReadonlyMap<string, number>,
): number {
	let dot = 0;
	let leftNorm = 0;
	for (const [term, weight] of left) {
		leftNorm += weight * weight;
		dot += weight * (right.get(term) ?? 0);
	}
	let rightNorm = 0;
	for (const weight of right.values()) rightNorm += weight * weight;
	if (leftNorm === 0 || rightNorm === 0) return 0;
	return dot / Math.sqrt(leftNorm * rightNorm);
}

export function semanticScores(
	query: string,
	candidates: readonly string[],
	aliases?: AliasInput,
): number[] {
	const resolved = resolveAliases(aliases);
	const queryTokens = semanticTokens(query, resolved);
	if (!queryTokens.length || !candidates.length) {
		return candidates.map(() => 0);
	}

	const documents = candidates.map((candidate) =>
		semanticTokens(candidate, resolved),
	);
	const documentFrequency = new Map<string, number>();
	for (const document of documents) {
		for (const term of new Set(document)) {
			documentFrequency.set(term, (documentFrequency.get(term) ?? 0) + 1);
		}
	}

	const queryVector = termVector(
		queryTokens,
		documentFrequency,
		documents.length,
	);
	return documents.map((document) =>
		cosineSimilarity(
			queryVector,
			termVector(document, documentFrequency, documents.length),
		),
	);
}

export function semanticScore(
	query: string,
	candidates: readonly string[],
	aliases?: AliasInput,
): SemanticMatch | null {
	let best: SemanticMatch | null = null;
	const scores = semanticScores(query, candidates, aliases);
	scores.forEach((score, index) => {
		if (score > 0 && (!best || score > best.score)) best = { index, score };
	});
	return best;
}
