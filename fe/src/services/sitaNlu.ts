import type { SitaChatTopic } from "../utils/validate.js";

/**
 * NLU lokal untuk Sita (tanpa LLM/API pihak ketiga):
 * 1. Normalisasi bahasa gaul + typo (levenshtein <= 1) + akhiran Indonesia.
 * 2. Deteksi topik bertingkat (skor strong/weak) untuk tahu maksud user.
 * 3. Klasifikasi small talk (sapaan/terima kasih) supaya tidak dianggap
 *    pertanyaan yang gagal dipahami.
 *
 * Murni fungsi tanpa I/O sehingga dipakai dari sumber yang sama oleh
 * server (sitaService.ts) dan browser (browser/lib/sita-chat.ts).
 */

export type NluTopic = SitaChatTopic;

/** Singkatan umum yang sering diketik user di chat. */
const SLANG_TERMS: Record<string, string> = {
	brapa: "berapa",
	brp: "berapa",
	catring: "catering",
	cost: "biaya",
	fotografer: "fotografi",
	costs: "biaya",
	hrg: "harga",
	hrga: "harga",
	pack: "paket",
	package: "paket",
	packages: "paket",
	pkg: "paket",
	photographer: "fotografi",
	photography: "fotografi",
	place: "venue",
	places: "venue",
	pket: "paket",
	pkt: "paket",
	price: "harga",
	prices: "harga",
	pricing: "harga",
	thnkz: "thanks",
	tq: "thanks",
	ty: "thanks",
	venu: "venue",
	venuez: "venue",
	venues: "venue",
	vendors: "vendor",
};

/** Akhiran khas Indonesia: "paketnya" -> "paket", "harganya" -> "harga". */
const COMMON_SUFFIXES = [
	"nya",
	"kan",
	"lah",
	"kah",
	"pun",
	"ku",
	"mu",
	"an",
	"i",
	"s",
];

const GREETING_START = new Set([
	"halo",
	"hai",
	"hi",
	"hello",
	"hey",
	"pagi",
	"siang",
	"sore",
	"malam",
	"assalamualaikum",
	"morning",
	"afternoon",
	"evening",
]);

const THANKS_START = new Set([
	"makasih",
	"terima",
	"thanks",
	"thank",
	"thx",
	"ty",
	"tq",
	"thnkz",
]);

/**
 * Kata-kata sapaan/kelaziman yang boleh muncul di small talk.
 * Bila ada satu kata di luar daftar ini, pesan dianggap pertanyaan nyata
 * dan diteruskan ke alur jawaban biasa (mis. "halo cara booking").
 */
const SMALL_TALK_VOCAB = new Set([
	...GREETING_START,
	...THANKS_START,
	"aku",
	"banyak",
	"bgt",
	"boleh",
	"bisa",
	"bunda",
	"banget",
	"dong",
	"halo",
	"info",
	"informasi",
	"iya",
	"ka",
	"kak",
	"mau",
	"nanya",
	"nih",
	"oke",
	"saya",
	"sip",
	"sista",
	"tanya",
	"tanyakan",
	"tolong",
	"ya",
	"yah",
	"a",
	"lot",
	"please",
	"pls",
	"plz",
	"okay",
	"ok",
	"how",
	"are",
	"you",
	"doing",
	"good",
	"morning",
	"afternoon",
	"evening",
	"welcome",
]);

interface TopicPattern {
	strong: readonly string[];
	weak: readonly string[];
}

/**
 * strong = kata kunci inti topik (skor 2), weak = kata pendukung (skor 1).
 * Skor dipakai untuk memilih topik utama dan mendeteksi multi-maksud.
 */
const TOPIC_PATTERNS: Record<NluTopic, TopicPattern> = {
	packages: {
		strong: ["paket", "package"],
		weak: [
			"harga",
			"biaya",
			"tarif",
			"budget",
			"bujet",
			"anggaran",
			"murah",
			"termurah",
			"mahal",
			"termahal",
			"maksimal",
			"maksimum",
			"max",
			"dibawah",
			"bawah",
			"under",
			"below",
			"up",
			"kurang",
			"price",
			"pricing",
			"cost",
			"cheap",
			"expensive",
			"affordable",
			"pernikahan",
			"akad",
			"wedding",
		],
	},
	venues: {
		strong: ["venue", "gedung"],
		weak: ["lokasi", "tempat", "place", "location"],
	},
	vendors: {
		strong: ["vendor"],
		weak: [
			"catering",
			"katering",
			"dekor",
			"dekorasi",
			"fotografi",
			"fotografer",
			"photographer",
			"decorator",
			"makeup",
			"rias",
			"mua",
			"mc",
			"dj",
			"musik",
			"hiburan",
			"entertainment",
		],
	},
};

export function normalizeNluText(raw: string): string {
	return raw
		.normalize("NFKD")
		.replace(/[\u0300-\u036f]/g, "")
		.toLowerCase()
		.replace(/[^a-z0-9\s]/g, " ")
		.replace(/\s+/g, " ")
		.trim();
}

/** Bentuk alternatif sebuah token: hasil slang map + maksimal 2 akhiran dilepas. */
export function tokenVariants(token: string): string[] {
	const variants = new Set<string>([token]);
	let current = token;
	for (let round = 0; round < 3; round += 1) {
		const slanged = SLANG_TERMS[current];
		if (slanged) {
			variants.add(slanged);
			current = slanged;
		}
		const suffix = COMMON_SUFFIXES.find(
			(candidate) =>
				current.length - candidate.length >= 3 && current.endsWith(candidate),
		);
		if (!suffix) break;
		current = current.slice(0, -suffix.length);
		variants.add(current);
	}
	return [...variants];
}

/** Levenshtein distance dengan batas `max` (berhenti lebih awal bila melebar). */
export function withinEditDistance(a: string, b: string, max: number): boolean {
	if (a === b) return true;
	if (Math.abs(a.length - b.length) > max) return false;
	let previous = Array.from({ length: b.length + 1 }, (_, index) => index);
	for (let i = 1; i <= a.length; i += 1) {
		const current = [i];
		let rowMin = i;
		for (let j = 1; j <= b.length; j += 1) {
			const cost = a[i - 1] === b[j - 1] ? 0 : 1;
			current[j] = Math.min(
				previous[j] + 1,
				current[j - 1] + 1,
				previous[j - 1] + cost,
			);
			if (current[j] < rowMin) rowMin = current[j];
		}
		if (rowMin > max) return false;
		previous = current;
	}
	return previous[b.length] <= max;
}

function tokenMatches(queryToken: string, candidateToken: string): boolean {
	const queryVariants = tokenVariants(queryToken);
	const candidateVariants = tokenVariants(candidateToken);
	if (queryVariants.some((query) => candidateVariants.includes(query))) {
		return true;
	}
	if (queryToken.length >= 4 && candidateToken.length >= 4) {
		return (
			Math.abs(queryToken.length - candidateToken.length) <= 1 &&
			withinEditDistance(queryToken, candidateToken, 1)
		);
	}
	return false;
}

/**
 * Apakah satu token query ketemu pada kandidat (exact/akhiran/slang/typo)?
 * Dipakai matchScore di sitaService supaya FAQ tetap cocok walau user salah ketik.
 */
export function hasFuzzyToken(
	queryToken: string,
	candidates: readonly string[],
): boolean {
	return candidates.some((candidate) => tokenMatches(queryToken, candidate));
}

/**
 * Apakah token termasuk kata kunci suatu topik (strong/weak)?
 * Dipakai service untuk membuang kata intent dari kata kunci pencarian
 * (mis. "venue" pada query paket) supaya tidak mematikan hasil pencarian.
 */
export function matchesAnyTopicTerm(
	token: string,
	topics: readonly NluTopic[],
): boolean {
	return topics.some((topic) => {
		const pattern = TOPIC_PATTERNS[topic];
		return [...pattern.strong, ...pattern.weak].some((term) =>
			tokenMatches(token, term),
		);
	});
}

/**
 * Kategori vendor bersifat eksplisit: "photographer for the wedding" harus tetap
 * dibaca sebagai vendor, bukan kena kata "wedding" dari topik paket.
 */
export function hasExplicitVendorCategory(raw: string): boolean {
	const tokens = normalizeNluText(raw).split(" ").filter(Boolean);
	return tokens.some((token) =>
		TOPIC_PATTERNS.vendors.weak.some((term) => tokenMatches(token, term)),
	);
}

export interface TopicSignal {
	topic: NluTopic;
	score: number;
	firstIndex: number;
	/** Indeks token strong pertama (Infinity bila hanya kata pendukung). */
	firstStrongIndex: number;
}

export interface TopicAnalysis {
	/** Diurutkan dari skor tertinggi; kosong bila tidak ada sinyal topik. */
	topics: TopicSignal[];
	primary?: NluTopic;
	/** Topik kedua dengan skor strong (>= 2) untuk deteksi multi-maksud. */
	secondary?: NluTopic;
}

export function detectTopics(raw: string): TopicAnalysis {
	const tokens = normalizeNluText(raw).split(" ").filter(Boolean);
	const scores: Record<NluTopic, number> = {
		packages: 0,
		venues: 0,
		vendors: 0,
	};
	const firstIndex: Record<NluTopic, number> = {
		packages: Number.POSITIVE_INFINITY,
		venues: Number.POSITIVE_INFINITY,
		vendors: Number.POSITIVE_INFINITY,
	};
	const firstStrongIndex: Record<NluTopic, number> = {
		packages: Number.POSITIVE_INFINITY,
		venues: Number.POSITIVE_INFINITY,
		vendors: Number.POSITIVE_INFINITY,
	};

	tokens.forEach((token, index) => {
		(Object.keys(TOPIC_PATTERNS) as NluTopic[]).forEach((topic) => {
			const pattern = TOPIC_PATTERNS[topic];
			if (pattern.strong.some((term) => tokenMatches(token, term))) {
				scores[topic] += 2;
				if (index < firstStrongIndex[topic]) firstStrongIndex[topic] = index;
			} else if (pattern.weak.some((term) => tokenMatches(token, term))) {
				scores[topic] += 1;
			} else {
				return;
			}
			if (index < firstIndex[topic]) firstIndex[topic] = index;
		});
	});

	const topics = (Object.keys(scores) as NluTopic[])
		.filter((topic) => scores[topic] > 0)
		.map((topic) => ({
			topic,
			score: scores[topic],
			firstIndex: firstIndex[topic],
			firstStrongIndex: firstStrongIndex[topic],
		}))
		.sort(
			(a, b) =>
				b.score - a.score ||
				a.firstStrongIndex - b.firstStrongIndex ||
				a.firstIndex - b.firstIndex,
		);

	return {
		topics,
		primary: topics[0]?.topic,
		secondary: topics.find(
			(signal) => signal.topic !== topics[0]?.topic && signal.score >= 2,
		)?.topic,
	};
}

export type SmallTalkKind = "greeting" | "thanks" | null;

/**
 * Sapaan/ucapan singkat tanpa sinyal topik, angka, atau kata nyata lain:
 * harus dijawab dengan sapaan + menu, bukan "belum menangkap maksudnya".
 * Kalau ada kata di luar daftar small talk, pesan diteruskan ke alur biasa.
 */
export function smallTalkKind(raw: string): SmallTalkKind {
	const tokens = normalizeNluText(raw).split(" ").filter(Boolean);
	if (tokens.length === 0 || tokens.length > 5) return null;
	if (detectTopics(raw).topics.length > 0) return null;
	if (!tokens.every((token) => SMALL_TALK_VOCAB.has(token))) return null;
	return THANKS_START.has(tokens[0]) ? "thanks" : "greeting";
}
