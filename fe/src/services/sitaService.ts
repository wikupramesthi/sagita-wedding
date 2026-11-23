import {
	cleanSlugLoose,
	type SitaChatContext,
	type SitaChatCriteria,
	type SitaChatTopic,
} from "../utils/validate.js";
import { getPageBatch } from "./batchService.js";
import {
	detectTopics,
	hasExplicitVendorCategory,
	hasFuzzyToken,
	matchesAnyTopicTerm,
	smallTalkKind,
	type TopicAnalysis,
} from "./sitaNlu.js";

export interface SitaCard {
	title: string;
	description: string;
	image?: string;
	actions?: {
		label: string;
		href: string;
	}[];
}

export interface SitaSuggestion {
	label: string;
	action?: "contact";
	/** Topik tujuan chip (mis. hasil deteksi multi-maksud) untuk tombol pilihan. */
	topic?: SitaChatTopic;
}

export interface SitaClarify {
	question: string;
	options: string[];
}

export interface SitaSource {
	label: string;
	href: string;
}

interface SitaServiceOptions {
	officeAddress?: string;
}

interface VenueRecord {
	name: string;
	slug: string;
	excerpt: string;
	address: string;
	image: string;
	mapsUrl: string;
}

export interface SitaAnswer {
	answer: string;
	cards: SitaCard[];
	offerContact: boolean;
	unanswered?: true;
	suggestions?: SitaSuggestion[];
	/** Pertanyaan lanjutan saat satu slot kriteria belum jelas. */
	clarify?: SitaClarify;
	/** Tautan ke data sumber yang dipakai jawaban. */
	source?: SitaSource;
	/**
	 * Seberapa yakin NLU memahami maksud pesan (0-1): 0.95 sapaan/kontak,
	 * 0.8-0.9 jawaban topik/FAQ, 0.15 fallback belum tertangkap.
	 */
	confidence?: number;
}

function venueRecords(payload: unknown): VenueRecord[] {
	return readArray(payload, "venues").flatMap((value) => {
		const item = asRecord(value);
		if (!item) return [];
		const name = plainText(item.name);
		const excerpt = plainText(item.excerpt);
		const address = plainText(item.address);
		return name
			? [
					{
						name,
						slug: asText(item.slug),
						excerpt,
						address,
						image: asText(item.image),
						mapsUrl: safeHttpsUrl(item.google_maps_url),
					},
				]
			: [];
	});
}

type Locale = "id" | "en";

interface PackageOption {
	quota?: unknown;
	final_price?: unknown;
	price?: unknown;
	discount_price?: unknown;
}

interface PackagePromo {
	code: string;
	type: string;
	value: number;
	minPurchase: number;
	maxDiscount: number | null;
}

interface BlackoutDate {
	date: string;
	reason: string;
}

interface PackageRecord {
	name: string;
	slug: string;
	description: string;
	image: string;
	guestCapacity: string;
	guestCapacityValue: number | null;
	popular: boolean;
	paymentTerms: string;
	dpPercentage: number | null;
	promos: PackagePromo[];
	blackoutDates: BlackoutDate[];
	options: PackageOption[];
}

interface TestimonialRecord {
	name: string;
	role: string;
	content: string;
}

interface VendorRecord {
	name: string;
	bio: string;
	image: string;
	categories: string[];
}

interface FaqRecord {
	question: string;
	answer: string;
	category: string;
}

const STOP_WORDS = new Set([
	"ada",
	"adalah",
	"akan",
	"and",
	"anda",
	"apa",
	"apakah",
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

const PACKAGE_NAME_TERMS = /\b(paket|package)\b/i;
const WEDDING_SERVICE_TERMS =
	/\b(layanan(?:\s+wo)?|services?|wedding\s+organizer|wedding\s+planner|jasa\s+wo)\b/i;
const VENUE_PRICE_TERMS = /\b(harga|biaya|tarif|price|pricing)\b/i;
const VENDOR_TERMS =
	/\b(vendors?|catering|katering|dekor|fotografi|fotografer|makeup|rias|mua|mc)\b/i;
const VENUE_TERMS = /\b(venue|venues|gedung|lokasi|tempat)\b/i;
const CONTACT_TERMS = /\b(admin|kontak|hubungi|whatsapp|wa)\b/i;
const UNRELATED_CONTENT =
	/\b(yoga(?:roots)?|wellness|meditation|asana|membership)\b/i;
const TOKEN_ALIASES: Record<string, string> = {
	anggaran: "biaya",
	brapa: "berapa",
	brp: "berapa",
	budget: "biaya",
	catring: "catering",
	cost: "biaya",
	costs: "biaya",
	fee: "biaya",
	fotografer: "fotografi",
	gedung: "venue",
	harga: "biaya",
	hrg: "biaya",
	hrga: "biaya",
	jasa: "layanan",
	lokasi: "venue",
	order: "booking",
	pack: "paket",
	package: "paket",
	packages: "paket",
	pemesanan: "booking",
	pesan: "booking",
	photographer: "fotografi",
	photography: "fotografi",
	pket: "paket",
	pkt: "paket",
	pkg: "paket",
	place: "venue",
	places: "venue",
	price: "biaya",
	prices: "biaya",
	pricing: "biaya",
	reservasi: "booking",
	service: "layanan",
	services: "layanan",
	tarif: "biaya",
	tempat: "venue",
	venu: "venue",
	venuez: "venue",
	venues: "venue",
	vendors: "vendor",
	wedding: "pernikahan",
};

function asRecord(value: unknown): Record<string, unknown> | null {
	if (!value || typeof value !== "object" || Array.isArray(value)) return null;
	return value as Record<string, unknown>;
}

function asText(value: unknown): string {
	return typeof value === "string" || typeof value === "number"
		? String(value).trim()
		: "";
}

function plainText(value: unknown): string {
	return asText(value)
		.replace(/<[^>]*>/g, " ")
		.replace(/&nbsp;/gi, " ")
		.replace(/&amp;/gi, "&")
		.replace(/&quot;/gi, '"')
		.replace(/&#39;|&apos;/gi, "'")
		.replace(/\s+/g, " ")
		.trim();
}

function safeHttpsUrl(value: unknown): string {
	const url = asText(value);
	try {
		return new URL(url).protocol === "https:" ? url : "";
	} catch {
		return "";
	}
}

function readArray(payload: unknown, key: string): unknown[] {
	const values = asRecord(payload)?.[key];
	if (!Array.isArray(values)) {
		throw new Error(`Sagita ${key} data is unavailable`);
	}
	return values;
}

function packageRecords(payload: unknown): PackageRecord[] {
	return readArray(payload, "packages").flatMap((value) => {
		const item = asRecord(value);
		if (!item) return [];
		const description = plainText(item.description);
		const guestCapacity = plainText(item.guest_capacity);
		const guestCapacityValues = guestCapacity.match(/\d+/g)?.map(Number) ?? [];
		const options = Array.isArray(item.options)
			? item.options.flatMap((option) => {
					const record = asRecord(option);
					return record ? [record as PackageOption] : [];
				})
			: [];
		const promos = Array.isArray(item.promos)
			? item.promos.flatMap((promo) => {
					const record = asRecord(promo);
					const code = asText(record?.code);
					const value = Number(record?.value);
					if (!code || !Number.isFinite(value)) return [];
					const minPurchase = Number(record?.min_purchase);
					const maxDiscount = Number(record?.max_discount);
					return [
						{
							code,
							type: asText(record?.type).toLowerCase(),
							value,
							minPurchase: Number.isFinite(minPurchase) ? minPurchase : 0,
							maxDiscount: Number.isFinite(maxDiscount) ? maxDiscount : null,
						},
					];
				})
			: [];
		const blackoutDates = Array.isArray(item.blackout_dates)
			? item.blackout_dates.flatMap((date) => {
					const record = asRecord(date);
					const value = asText(record?.date);
					return /^\d{4}-\d{2}-\d{2}$/.test(value)
						? [{ date: value, reason: plainText(record?.reason) }]
						: [];
				})
			: [];
		const dpPercentage = Number(item.dp_percentage);
		return [
			{
				name: plainText(item.name),
				slug: asText(item.slug),
				description,
				image: asText(item.cover_url),
				guestCapacity,
				guestCapacityValue: guestCapacityValues.length
					? Math.max(...guestCapacityValues)
					: null,
				popular:
					item.is_popular === true ||
					item.is_popular === 1 ||
					item.is_popular === "1",
				paymentTerms: plainText(item.payment_terms_note),
				dpPercentage: Number.isFinite(dpPercentage) ? dpPercentage : null,
				promos,
				blackoutDates,
				options,
			},
		].filter((record) => record.name.length > 0);
	});
}

function testimonialRecords(payload: unknown): TestimonialRecord[] {
	return readArray(payload, "testimonials").flatMap((value) => {
		const item = asRecord(value);
		if (!item) return [];
		if (/\b(inactive|pending)\b/i.test(asText(item.status))) return [];
		const name = plainText(item.nama);
		const content = plainText(item.isi_testimoni);
		if (!name || !content || UNRELATED_CONTENT.test(content)) return [];
		return [
			{
				name,
				role: plainText(item.jabatan),
				content,
			},
		];
	});
}

function vendorRecords(payload: unknown): VendorRecord[] {
	return readArray(payload, "vendors").flatMap((value) => {
		const item = asRecord(value);
		if (!item) return [];
		const bio = plainText(item.biografi);
		const categories = Array.isArray(item.vendors_categories)
			? item.vendors_categories.flatMap((category) => {
					const record = asRecord(category);
					const name = plainText(record?.name);
					return name ? [name] : [];
				})
			: [];
		const name = plainText(item.name);
		return name
			? [
					{
						name,
						bio,
						image: asText(item.avatar),
						categories,
					},
				]
			: [];
	});
}

function faqRecords(payload: unknown): FaqRecord[] {
	return readArray(payload, "faqs").flatMap((value) => {
		const item = asRecord(value);
		if (!item) return [];
		const question = plainText(item.question);
		const answer = plainText(item.answer);
		const category = plainText(item.kategori);
		if (UNRELATED_CONTENT.test(`${question} ${answer} ${category}`)) return [];
		return question && answer ? [{ question, answer, category }] : [];
	});
}

function tokenize(value: string): string[] {
	return [
		...new Set(
			value
				.normalize("NFKD")
				.replace(/[\u0300-\u036f]/g, "")
				.toLocaleLowerCase()
				.match(/[a-z0-9]+/g)
				?.map((word) => TOKEN_ALIASES[word] ?? word)
				.filter((word) => word.length > 1 && !STOP_WORDS.has(word)) ?? [],
		),
	];
}

function matchScore(queryTokens: string[], text: string): number {
	if (queryTokens.length === 0) return 0;
	const content = tokenize(text);
	const exact = new Set(content);
	return queryTokens.filter(
		(token) => exact.has(token) || hasFuzzyToken(token, content),
	).length;
}

function priceFor(option: PackageOption): number | null {
	for (const value of [
		option.final_price,
		option.discount_price,
		option.price,
	]) {
		if (value === null || value === undefined || value === "") continue;
		const price = Number(value);
		if (Number.isFinite(price) && price >= 0) return price;
	}
	return null;
}

function formatPrice(price: number, locale: Locale): string {
	return new Intl.NumberFormat(locale === "id" ? "id-ID" : "en-US", {
		style: "currency",
		currency: "IDR",
		maximumFractionDigits: 0,
	}).format(price);
}

function minimumPrice(record: PackageRecord): number | null {
	const prices = record.options
		.map(priceFor)
		.filter((price): price is number => price !== null);
	return prices.length ? Math.min(...prices) : null;
}

function parseLocalizedAmount(value: string): number | null {
	let normalized = value;
	if (normalized.includes(".") && normalized.includes(",")) {
		normalized =
			normalized.lastIndexOf(",") > normalized.lastIndexOf(".")
				? normalized.replace(/\./g, "").replace(",", ".")
				: normalized.replace(/,/g, "");
	} else if (normalized.includes(",")) {
		normalized = /,\d{3}$/.test(normalized)
			? normalized.replace(/,/g, "")
			: normalized.replace(",", ".");
	} else if (/\.\d{3}(?:\.\d{3})*$/.test(normalized)) {
		normalized = normalized.replace(/\./g, "");
	}
	const amount = Number(normalized);
	return Number.isFinite(amount) && amount >= 0 ? amount : null;
}

function withoutGuestCount(message: string): string {
	return message.replace(/\b\d[\d.,]*\s*(?:tamu|orang|pax|guests?)\b/gi, " ");
}

function packageBudgetLimit(message: string): number | null {
	const budgetText = withoutGuestCount(message);
	const match = budgetText.match(
		/(?:rp\s*)?(\d[\d.,]*)\s*(juta|jt|million|miliar|milyar|billion|ribu|rb|k)?/i,
	);
	if (!match) return null;

	const unit = match[2]?.toLocaleLowerCase();
	const hasCurrency = /\brp\s*\d/i.test(match[0]);
	if (!unit && !hasCurrency && !hasBudgetRequest(budgetText)) {
		return null;
	}
	const amount = parseLocalizedAmount(match[1]);
	if (amount === null) return null;
	if (!unit && !hasCurrency && amount < 100_000) return null;

	const multiplier =
		unit === "juta" || unit === "jt" || unit === "million"
			? 1_000_000
			: unit === "miliar" || unit === "milyar" || unit === "billion"
				? 1_000_000_000
				: unit === "ribu" || unit === "rb" || unit === "k"
					? 1_000
					: 1;
	return amount * multiplier;
}

function hasBudgetRequest(message: string): boolean {
	const budgetText = withoutGuestCount(message);
	return /\b(budget|anggaran|di bawah|dibawah|kurang dari|under|below|up to)\b|<=|\b(maksimal|max)\s*(?:rp\s*)?\d/i.test(
		budgetText,
	);
}

function hasAmbiguousBudget(message: string): boolean {
	const budgetText = withoutGuestCount(message);
	if (
		!hasBudgetRequest(budgetText) ||
		/\brp\s*\d|\b(juta|jt|million|miliar|milyar|billion|ribu|rb|k)\b/i.test(
			budgetText,
		)
	) {
		return false;
	}
	const match = budgetText.match(/\d[\d.,]*/);
	const amount = match ? parseLocalizedAmount(match[0]) : null;
	return amount !== null && amount < 100_000;
}

function normalizeEventDate(value: string): string {
	const iso = value.match(/^(20\d{2})-(\d{1,2})-(\d{1,2})$/);
	const local = value.match(/^(\d{1,2})\/(\d{1,2})\/(20\d{2})$/);
	if (!iso && !local) return value;
	const date = iso
		? `${iso[1]}-${iso[2].padStart(2, "0")}-${iso[3].padStart(2, "0")}`
		: `${local?.[3]}-${local?.[2].padStart(2, "0")}-${local?.[1].padStart(2, "0")}`;
	const parsed = new Date(`${date}T00:00:00Z`);
	return !Number.isNaN(parsed.getTime()) &&
		parsed.toISOString().slice(0, 10) === date
		? date
		: value;
}

function requestedGuestCount(message: string): number | null {
	const match = message.match(/\b(\d{1,6})\s*(?:tamu|orang|pax|guests?)\b/i);
	if (!match) return null;
	const count = Number(match[1]);
	return Number.isSafeInteger(count) && count > 0 ? count : null;
}

const PACKAGE_QUERY_NOISE = new Set([
	"akad",
	"bandingkan",
	"below",
	"biaya",
	"billion",
	"budget",
	"cheapest",
	"compare",
	"di",
	"cocok",
	"dibawah",
	"dong",
	"harga",
	"bawah",
	"jt",
	"juta",
	"kapasitas",
	"kurang",
	"lowest",
	"mahal",
	"many",
	"much",
	"maksimal",
	"max",
	"million",
	"milyar",
	"miliar",
	"maksimum",
	"most",
	"murah",
	"nih",
	"package",
	"paket",
	"paling",
	"perbandingan",
	"pernikahan",
	"lebih",
	"expensive",
	"ribu",
	"rb",
	"rp",
	"sih",
	"bgt",
	"banget",
	"jg",
	"lg",
	"sama",
	"serta",
	"atau",
	"any",
	"plus",
	"please",
	"pls",
	"some",
	"termurah",
	"termahal",
	"tamu",
	"orang",
	"pax",
	"guest",
	"guests",
	"up",
	"under",
	"versus",
	"vs",
	"wedding",
]);

function packageQueryTokens(message: string): string[] {
	return tokenize(message).filter(
		(token) =>
			!PACKAGE_QUERY_NOISE.has(token) &&
			!matchesAnyTopicTerm(token, ["packages", "venues", "vendors"]) &&
			!/^\d+$/.test(token) &&
			!/^(?:rp|idr)\d+$/.test(token),
	);
}

function isPackageComparison(message: string): boolean {
	return /\b(bandingkan|perbandingan|compare|versus|vs)\b/i.test(message);
}

function packageContactSuggestion(locale: Locale): SitaSuggestion {
	return {
		label: locale === "id" ? "Hubungi admin" : "Contact the team",
		action: "contact",
	};
}

/** Menu topik untuk sapaan & fallback; label sengaja sama dengan tombol pilihan. */
function topicMenuSuggestions(locale: Locale): SitaSuggestion[] {
	return locale === "id"
		? [
				{ label: "Paket & harga", topic: "packages" },
				{ label: "Venue", topic: "venues" },
				{ label: "Vendor", topic: "vendors" },
				{ label: "Cara booking" },
				{ label: "Hubungi admin", action: "contact" },
			]
		: [
				{ label: "Packages & pricing", topic: "packages" },
				{ label: "Venues", topic: "venues" },
				{ label: "Vendors", topic: "vendors" },
				{ label: "How to book" },
				{ label: "Contact the team", action: "contact" },
			];
}

function smallTalkAnswer(
	locale: Locale,
	kind: "greeting" | "thanks",
): SitaAnswer {
	return {
		answer:
			kind === "thanks"
				? locale === "id"
					? "Sama-sama, Kak! Ada lagi yang bisa aku bantu soal paket, venue, vendor, atau booking?"
					: "You're welcome! Anything else I can help with about packages, venues, vendors, or booking?"
				: locale === "id"
					? "Halo Kak! Aku Sita, asisten digital Sagita Wedding. Mau tanya soal paket, venue, vendor, atau cara booking?"
					: "Hi! I'm Sita, the Sagita Wedding digital assistant. Want to ask about packages, venues, vendors, or booking?",
		cards: [],
		offerContact: false,
		suggestions: topicMenuSuggestions(locale),
		confidence: 0.95,
	};
}

function clarifyPackageBudget(locale: Locale): SitaAnswer {
	return {
		answer:
			locale === "id"
				? "Boleh sebutkan batas anggarannya dengan satuan, misalnya Rp50.000.000 atau 50 juta?"
				: "Could you include a currency or unit for the budget, such as IDR 50,000,000 or 50 million?",
		cards: [],
		offerContact: false,
		clarify: {
			question:
				locale === "id"
					? "Batas anggaran berapa, Kak?"
					: "What is your budget limit?",
			options:
				locale === "id"
					? ["Rp50.000.000", "50 juta", "Hubungi admin"]
					: ["IDR 50,000,000", "50 million", "Contact the team"],
		},
	};
}

function futureEventDate(monthsAhead: number): string {
	const now = new Date();
	const date = new Date(
		Date.UTC(now.getUTCFullYear(), now.getUTCMonth() + monthsAhead, 15),
	);
	return date.toISOString().slice(0, 10);
}

function clarifyEventDate(locale: Locale): SitaAnswer {
	return {
		answer:
			locale === "id"
				? "Tanggal acara yang ingin dicek kapan, Kak? Ketersediaan perlu dikonfirmasi tim Sagita."
				: "Which event date should we check? The Sagita team needs to confirm availability.",
		cards: [],
		offerContact: false,
		confidence: 0.85,
		clarify: {
			question: locale === "id" ? "Tanggal berapa?" : "Which date?",
			options: [
				futureEventDate(3),
				futureEventDate(6),
				locale === "id" ? "Hubungi admin" : "Contact the team",
			],
		},
	};
}

function noPackageMatch(locale: Locale): SitaAnswer {
	return {
		answer:
			locale === "id"
				? "Aku belum menemukan paket dengan harga dan kapasitas yang tercatat sesuai kriteria itu. Coba ubah anggaran atau jumlah tamu, atau hubungi admin untuk memastikan pilihan lainnya."
				: "I couldn't find a package with recorded pricing and capacity that matches those criteria. Try changing the budget or guest count, or contact the team to check other options.",
		cards: [],
		offerContact: true,
		unanswered: true,
		suggestions: [packageContactSuggestion(locale)],
	};
}

function packageComparisonSummary(
	ranked: { record: PackageRecord; score: number; price: number | null }[],
	locale: Locale,
): string {
	const priced = ranked.filter((item) => item.price !== null);
	const lowest = priced.sort(
		(a, b) =>
			(a.price ?? Number.MAX_SAFE_INTEGER) -
			(b.price ?? Number.MAX_SAFE_INTEGER),
	)[0];
	const capacities = ranked.filter(
		(item) => item.record.guestCapacityValue !== null,
	);
	const largest = capacities.sort(
		(a, b) =>
			(b.record.guestCapacityValue ?? 0) - (a.record.guestCapacityValue ?? 0),
	)[0];
	return [
		lowest
			? locale === "id"
				? `Harga mulai terendah yang tercatat: ${lowest.record.name} (${formatPrice(lowest.price ?? 0, locale)})`
				: `Lowest recorded starting price: ${lowest.record.name} (${formatPrice(lowest.price ?? 0, locale)})`
			: "",
		largest
			? locale === "id"
				? `Kapasitas terbesar yang tercatat: ${largest.record.name} (${new Intl.NumberFormat("id-ID").format(largest.record.guestCapacityValue ?? 0)} tamu)`
				: `Largest recorded capacity: ${largest.record.name} (${new Intl.NumberFormat("en-US").format(largest.record.guestCapacityValue ?? 0)} guests)`
			: "",
	]
		.filter(Boolean)
		.join(". ");
}

function fallback(locale: Locale): SitaAnswer {
	return {
		answer:
			locale === "id"
				? "Maaf Kak, aku belum menangkap maksudnya. Kakak ingin tanya soal paket, venue, vendor, atau cara booking?"
				: "Sorry, I couldn't match that question. Are you asking about packages, venues, vendors, or booking?",
		cards: [],
		offerContact: true,
		unanswered: true,
		confidence: 0.15,
		suggestions: topicMenuSuggestions(locale),
	};
}

async function answerPackages(
	message: string,
	locale: Locale,
	criteria?: SitaChatCriteria,
): Promise<SitaAnswer> {
	const budget = packageBudgetLimit(message) ?? criteria?.budgetLimit ?? null;
	const guestCount =
		requestedGuestCount(message) ?? criteria?.guestCount ?? null;
	if (
		budget === null &&
		(hasAmbiguousBudget(message) ||
			(hasBudgetRequest(message) && budget === null))
	) {
		return clarifyPackageBudget(locale);
	}

	const payload = await getPageBatch("packages", {
		per_page: 50,
		sort: "lowest_price",
	});
	const records = packageRecords(payload);
	const queryTokens = packageQueryTokens(message);
	const generic = queryTokens.length === 0;
	const wantsCheapest = /\b(murah|termurah|cheapest|lowest)\b/i.test(message);
	const wantsMostExpensive = /\b(mahal|termahal|most expensive)\b/i.test(
		message,
	);
	const comparison = isPackageComparison(message);
	const ranked = records
		.map((record) => ({
			record,
			score: matchScore(
				queryTokens,
				`${record.name} ${record.description} ${record.guestCapacity}`,
			),
			price: minimumPrice(record),
		}))
		.filter(
			({ score, price, record }) =>
				(generic || score > 0) &&
				(budget === null || (price !== null && price <= budget)) &&
				(guestCount === null ||
					(record.guestCapacityValue !== null &&
						record.guestCapacityValue >= guestCount)),
		)
		.sort((a, b) => {
			if (wantsCheapest || budget !== null) {
				return (
					(a.price ?? Number.MAX_SAFE_INTEGER) -
						(b.price ?? Number.MAX_SAFE_INTEGER) || b.score - a.score
				);
			}
			if (wantsMostExpensive) {
				return (b.price ?? -1) - (a.price ?? -1) || b.score - a.score;
			}
			return (
				b.score - a.score ||
				Number(b.record.popular) - Number(a.record.popular) ||
				(a.price ?? Number.MAX_SAFE_INTEGER) -
					(b.price ?? Number.MAX_SAFE_INTEGER)
			);
		})
		.slice(0, 4);

	if (ranked.length === 0) {
		if (budget !== null || guestCount !== null) return noPackageMatch(locale);
		const faqAnswer = await answerFaq(message, locale);
		return faqAnswer
			? {
					...faqAnswer,
					offerContact: true,
					suggestions: [packageContactSuggestion(locale)],
				}
			: fallback(locale);
	}

	const comparisonDetails = comparison
		? packageComparisonSummary(ranked, locale)
		: "";
	const comparisonAnswer =
		comparison && ranked.length < 2
			? locale === "id"
				? "Aku hanya menemukan satu paket yang cocok untuk dibandingkan:"
				: "I found only one matching package to compare:"
			: comparisonDetails
				? `${comparisonDetails}. ${locale === "id" ? "Detail tiap paket ada di kartu berikut." : "See each card for package details."}`
				: comparison
					? locale === "id"
						? "Berikut paket yang bisa dibandingkan berdasarkan detail yang tersedia:"
						: "Here are the packages to compare using the available details:"
					: "";
	const filterReasons = [
		budget !== null
			? locale === "id"
				? `anggaran maksimal ${formatPrice(budget, locale)}`
				: `a budget up to ${formatPrice(budget, locale)}`
			: "",
		guestCount !== null
			? locale === "id"
				? `kapasitas minimal ${new Intl.NumberFormat("id-ID").format(guestCount)} tamu`
				: `capacity for at least ${new Intl.NumberFormat("en-US").format(guestCount)} guests`
			: "",
	].filter(Boolean);

	return {
		answer:
			comparisonAnswer ||
			(filterReasons.length
				? locale === "id"
					? `Paket di bawah ini disaring berdasarkan ${filterReasons.join(" dan ")}:`
					: `These packages are filtered by ${filterReasons.join(" and ")}:`
				: locale === "id"
					? "Berikut beberapa paket yang tercatat di katalog Sagita. Harga mengikuti opsi paket yang tersedia. Sebutkan anggaran maksimum atau jumlah tamu jika ingin hasilnya disaring lebih lanjut:"
					: "Here are some packages listed in Sagita's catalog. Prices reflect the available package options. Share a maximum budget or guest count to narrow the results:"),
		cards: ranked.map(({ record, price }) => {
			const quotaValues = record.options
				.map((option) => Number(option.quota))
				.filter((quota) => Number.isFinite(quota) && quota >= 0);
			const details = [
				record.description,
				record.guestCapacity
					? `${locale === "id" ? "Kapasitas" : "Capacity"}: ${record.guestCapacity}`
					: "",
				price !== null
					? `${locale === "id" ? "Mulai dari" : "Starting at"} ${formatPrice(price, locale)}`
					: "",
				record.promos.length
					? `Promo: ${record.promos.map((promo) => formatPromo(promo, locale)).join(", ")}`
					: "",
				quotaValues.length
					? `${locale === "id" ? "Kuota tercatat" : "Recorded quota"}: ${new Intl.NumberFormat(locale === "id" ? "id-ID" : "en-US").format(Math.max(...quotaValues))}`
					: "",
			].filter(Boolean);
			const slug = cleanSlugLoose(record.slug);
			return {
				title: record.name,
				description: details.join(" · "),
				...(record.image ? { image: record.image } : {}),
				actions: [
					...(slug
						? [
								{
									label: locale === "id" ? "Lihat paket" : "View package",
									href: `/packages/${slug}`,
								},
							]
						: []),
					{
						label: locale === "id" ? "Galeri" : "Gallery",
						href: "/portofolio",
					},
				],
			};
		}),
		offerContact: true,
		suggestions: [
			...(comparison
				? []
				: [
						{ label: locale === "id" ? "Paket termurah" : "Cheapest package" },
						{
							label: locale === "id" ? "Bandingkan paket" : "Compare packages",
						},
					]),
			packageContactSuggestion(locale),
		],
	};
}

async function answerVendors(
	message: string,
	locale: Locale,
	criteria?: SitaChatCriteria,
): Promise<SitaAnswer> {
	const payload = await getPageBatch("vendors");
	const records = vendorRecords(payload);
	const allTokens = tokenize(`${message} ${criteria?.vendorCategory ?? ""}`);
	const queryTokens = allTokens.filter(
		(token) =>
			token !== "vendor" &&
			token !== "vendors" &&
			!PACKAGE_QUERY_NOISE.has(token) &&
			!matchesAnyTopicTerm(token, ["packages", "venues"]) &&
			!/^\d+$/.test(token),
	);
	const generic = queryTokens.length === 0;
	const ranked = records
		.map((record) => ({
			record,
			score: matchScore(
				queryTokens,
				`${record.name} ${record.bio} ${record.categories.join(" ")}`,
			),
		}))
		.filter(({ score }) => generic || score > 0)
		.sort((a, b) => b.score - a.score)
		.slice(0, 4);

	if (ranked.length === 0) {
		const faqAnswer = await answerFaq(message, locale);
		return faqAnswer
			? {
					...faqAnswer,
					offerContact: true,
					suggestions: [
						{
							label: locale === "id" ? "Hubungi admin" : "Contact the team",
							action: "contact",
						},
					],
				}
			: fallback(locale);
	}

	return {
		answer: criteria?.vendorCategory
			? locale === "id"
				? `Berikut vendor kategori ${criteria.vendorCategory} yang tercatat di data Sagita:`
				: `Here are the ${criteria.vendorCategory} vendors listed in Sagita's data:`
			: locale === "id"
				? "Berikut beberapa vendor yang tercatat di data Sagita. Sebutkan kategori vendor yang kamu cari agar hasilnya lebih spesifik:"
				: "Here are some vendors listed in Sagita's data. Share the vendor category you need to narrow the results:",
		cards: ranked.map(({ record }) => ({
			title: record.name,
			description:
				[record.categories.join(", "), record.bio]
					.filter(Boolean)
					.join(" · ") ||
				(locale === "id"
					? "Informasi vendor di data Sagita."
					: "Vendor information in Sagita's data."),
			...(record.image ? { image: record.image } : {}),
			actions: [
				{
					label: locale === "id" ? "Lihat vendor" : "View vendor",
					href: "/vendors",
				},
			],
		})),
		offerContact: true,
		suggestions: [
			{
				label: locale === "id" ? "Hubungi admin" : "Contact the team",
				action: "contact",
			},
		],
	};
}

async function answerVenues(
	message: string,
	locale: Locale,
	criteria?: SitaChatCriteria,
): Promise<SitaAnswer> {
	const payload = await getPageBatch("venues");
	const records = venueRecords(payload);
	const queryTokens = tokenize(`${message} ${criteria?.city ?? ""}`).filter(
		(token) =>
			!PACKAGE_QUERY_NOISE.has(token) &&
			!matchesAnyTopicTerm(token, ["packages", "venues", "vendors"]) &&
			!/^\d+$/.test(token),
	);
	const generic = queryTokens.length === 0;
	const ranked = records
		.map((record) => ({
			record,
			score: matchScore(
				queryTokens,
				`${record.name} ${record.address} ${record.excerpt}`,
			),
		}))
		.filter(({ score }) => generic || score > 0)
		.sort((a, b) => b.score - a.score)
		.slice(0, 4);

	if (ranked.length === 0) {
		const faqAnswer = await answerFaq(message, locale);
		return faqAnswer
			? {
					...faqAnswer,
					offerContact: true,
					suggestions: [
						{
							label: locale === "id" ? "Hubungi admin" : "Contact the team",
							action: "contact",
						},
					],
				}
			: venueGuidance(locale);
	}

	return {
		answer: criteria?.city
			? locale === "id"
				? `Berikut venue dengan informasi lokasi yang cocok untuk ${criteria.city}. Ketersediaan tanggal perlu dikonfirmasi langsung:`
				: `Here are venues with location information matching ${criteria.city}. Date availability must be confirmed directly:`
			: locale === "id"
				? "Berikut beberapa venue yang tercatat di data Sagita. Sebutkan kota untuk menyaring lokasi; ketersediaan tanggal perlu dikonfirmasi langsung:"
				: "Here are some venues listed in Sagita's data. Share a city to filter by location; date availability must be confirmed directly:",
		cards: ranked.map(({ record }) => {
			const slug = cleanSlugLoose(record.slug);
			const mapQuery = [record.name, record.address].filter(Boolean).join(" ");
			return {
				title: record.name,
				description:
					[record.address, record.excerpt].filter(Boolean).join(" · ") ||
					(locale === "id"
						? "Informasi venue di data Sagita."
						: "Venue information in Sagita's data."),
				...(record.image ? { image: record.image } : {}),
				actions: [
					{
						label: locale === "id" ? "Lihat venue" : "View venue",
						href: slug ? `/venues#venue-${slug}` : "/venues",
					},
					{
						label: locale === "id" ? "Peta" : "Map",
						href:
							record.mapsUrl ||
							`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(mapQuery)}`,
					},
				],
			};
		}),
		offerContact: true,
		suggestions: [
			{
				label: locale === "id" ? "Hubungi admin" : "Contact the team",
				action: "contact",
			},
		],
	};
}

function venueGuidance(locale: Locale): SitaAnswer {
	return {
		answer:
			locale === "id"
				? "Sagita bisa membantu membahas kebutuhan venue sesuai konsep acara. Untuk rekomendasi yang tepat, sampaikan kota atau lokasi, tanggal, dan perkiraan jumlah tamu kepada admin. Ketersediaan serta biaya venue perlu dikonfirmasi langsung."
				: "Sagita can help discuss venue needs for your event concept. For a relevant recommendation, share the city or location, event date, and estimated guest count with the team. Venue availability and pricing must be confirmed directly.",
		cards: [
			{
				title: locale === "id" ? "Konsultasi venue" : "Venue consultation",
				description:
					locale === "id"
						? "Diskusikan lokasi, tanggal, dan jumlah tamu dengan tim Sagita."
						: "Discuss location, date, and guest count with the Sagita team.",
				actions: [
					{
						label: locale === "id" ? "Konsultasi venue" : "Venue consultation",
						href: "/formulir-konsultasi",
					},
				],
			},
		],
		offerContact: true,
		unanswered: true,
		confidence: 0.85,
		suggestions: [
			{
				label: locale === "id" ? "Hubungi admin" : "Contact the team",
				action: "contact",
			},
		],
	};
}

function weddingServiceGuidance(locale: Locale): SitaAnswer {
	return {
		answer:
			locale === "id"
				? "Untuk konsultasi layanan Wedding Organizer, silakan isi formulir konsultasi. Tim Sagita dapat membantu membahas kebutuhan acaramu."
				: "To discuss Wedding Organizer services, please fill out the consultation form. The Sagita team can help talk through your event needs.",
		cards: [
			{
				title: locale === "id" ? "Formulir konsultasi" : "Consultation form",
				description:
					locale === "id"
						? "Ceritakan kebutuhan acara dan layanan WO yang kamu cari."
						: "Tell us about your event and the Wedding Organizer services you need.",
				actions: [
					{
						label:
							locale === "id"
								? "Isi formulir konsultasi"
								: "Open consultation form",
						href: "/formulir-konsultasi",
					},
				],
			},
		],
		offerContact: false,
		confidence: 0.9,
	};
}

function formatPromo(promo: PackagePromo, locale: Locale): string {
	const discount =
		promo.type === "percent"
			? `${promo.value}%`
			: formatPrice(promo.value, locale);
	const minimum = promo.minPurchase
		? `, min. ${formatPrice(promo.minPurchase, locale)}`
		: "";
	return `${promo.code} (${discount}${minimum})`;
}

async function answerPromotions(locale: Locale): Promise<SitaAnswer> {
	const payload = await getPageBatch("packages", { per_page: 50 });
	const records = packageRecords(payload).filter(
		(record) => record.promos.length > 0,
	);
	if (!records.length) {
		return {
			answer:
				locale === "id"
					? "Saat ini belum ada promo aktif yang tercatat di katalog Sagita. Cek katalog lagi nanti atau hubungi admin untuk info terbaru."
					: "There are no active promotions recorded in Sagita's catalog right now. Check back later or contact the team for the latest offers.",
			cards: [],
			offerContact: true,
			confidence: 0.9,
			suggestions: [packageContactSuggestion(locale)],
		};
	}
	const promos = records
		.flatMap((record) =>
			record.promos.map((promo) => `${promo.code}: ${record.name}`),
		)
		.slice(0, 5);
	return {
		answer:
			locale === "id"
				? `Promo aktif yang tercatat di katalog Sagita: ${promos.join(", ")}. Syarat dan ketentuan mengikuti detail paket.`
				: `Active promotions recorded in Sagita's catalog: ${promos.join(", ")}. Terms follow each package detail.`,
		cards: records.slice(0, 4).map((record) => {
			const slug = cleanSlugLoose(record.slug);
			return {
				title: record.name,
				description: record.promos
					.map((promo) => formatPromo(promo, locale))
					.join(" · "),
				...(record.image ? { image: record.image } : {}),
				actions: [
					...(slug
						? [
								{
									label: locale === "id" ? "Lihat paket" : "View package",
									href: `/packages/${slug}`,
								},
							]
						: []),
				],
			};
		}),
		offerContact: true,
		confidence: 0.9,
		suggestions: [packageContactSuggestion(locale)],
	};
}

async function answerPayment(locale: Locale): Promise<SitaAnswer> {
	const payload = await getPageBatch("packages", { per_page: 50 });
	const records = packageRecords(payload).filter(
		(record) => record.paymentTerms || record.dpPercentage !== null,
	);
	if (!records.length) {
		const faqAnswer = await answerFaq("pembayaran dp", locale);
		return faqAnswer
			? {
					...faqAnswer,
					confidence: 0.85,
					suggestions: [packageContactSuggestion(locale)],
				}
			: {
					answer:
						locale === "id"
							? "Ketentuan pembayaran belum tercatat rinci di katalog Sagita. Hubungi admin untuk info DP dan termin yang tersedia."
							: "Detailed payment terms are not recorded in Sagita's catalog. Contact the team for available deposits and installments.",
					cards: [],
					offerContact: true,
					confidence: 0.85,
					suggestions: [packageContactSuggestion(locale)],
				};
	}
	const dp = records.find(
		(record) => record.dpPercentage !== null,
	)?.dpPercentage;
	const terms = records.find((record) => record.paymentTerms)?.paymentTerms;
	const dpText =
		dp !== null && dp !== undefined
			? locale === "id"
				? `DP yang tercatat ${dp}%. `
				: `Recorded deposit is ${dp}%. `
			: "";
	const answer = `${dpText}${terms}`.trim();
	return {
		answer:
			locale === "id"
				? `${answer} Detail skema pembayaran bisa dikonfirmasi langsung dengan admin.`
				: `${answer} Payment scheme details can be confirmed directly with the team.`,
		cards: [
			{
				title:
					locale === "id" ? "Konsultasi pembayaran" : "Payment consultation",
				description:
					locale === "id"
						? "Diskusikan DP, termin, dan metode pembayaran dengan tim Sagita."
						: "Discuss deposits, installments, and payment methods with the Sagita team.",
				actions: [
					{
						label:
							locale === "id" ? "Formulir konsultasi" : "Consultation form",
						href: "/formulir-konsultasi",
					},
				],
			},
		],
		offerContact: true,
		confidence: 0.9,
		suggestions: [packageContactSuggestion(locale)],
	};
}

async function answerAvailability(
	message: string,
	locale: Locale,
	criteria?: SitaChatCriteria,
): Promise<SitaAnswer> {
	const dateFromMessage = message.match(
		/\b(20\d{2}-\d{1,2}-\d{1,2}|\d{1,2}\/\d{1,2}\/20\d{2})\b/,
	)?.[1];
	const date =
		criteria?.eventDate ??
		(dateFromMessage ? normalizeEventDate(dateFromMessage) : undefined);
	if (!date) return clarifyEventDate(locale);

	const payload = await getPageBatch("packages", { per_page: 50 });
	const blocked = packageRecords(payload)
		.flatMap((record) => record.blackoutDates)
		.find((entry) => entry.date === date);
	const prefix = locale === "id" ? `Untuk tanggal ${date}, ` : `For ${date}, `;
	const answer = blocked
		? `${prefix}${locale === "id" ? "tanggal itu tercatat sebagai tanggal libur paket" : "that date is recorded as a package blackout date"}${blocked.reason ? ` (${blocked.reason})` : ""}. ${locale === "id" ? "Hubungi admin untuk alternatif tanggal." : "Contact the team for alternative dates."}`
		: `${prefix}${locale === "id" ? "data Sagita belum menyimpan status ketersediaan otomatis. Tim perlu mengonfirmasi ketersediaan venue, vendor, dan paket." : "Sagita data does not include automatic availability status. The team needs to confirm venue, vendor, and package availability."}`;
	return {
		answer,
		cards: [
			{
				title: locale === "id" ? "Cek ketersediaan" : "Check availability",
				description:
					locale === "id"
						? "Kirim tanggal dan detail acara untuk konfirmasi tim Sagita."
						: "Send the date and event details for Sagita team confirmation.",
				actions: [
					{
						label:
							locale === "id" ? "Formulir konsultasi" : "Consultation form",
						href: "/formulir-konsultasi",
					},
				],
			},
		],
		offerContact: true,
		confidence: 0.9,
		suggestions: [packageContactSuggestion(locale)],
	};
}

async function answerTestimonials(locale: Locale): Promise<SitaAnswer> {
	const payload = await getPageBatch("home");
	const records = testimonialRecords(payload);
	if (!records.length) {
		return {
			answer:
				locale === "id"
					? "Belum ada testimoni pernikahan yang bisa ditampilkan dari data Sagita saat ini. Untuk referensi pengalaman klien, silakan hubungi admin."
					: "There are no wedding testimonials available in Sagita's data right now. Contact the team for client references.",
			cards: [],
			offerContact: true,
			confidence: 0.9,
			suggestions: [packageContactSuggestion(locale)],
		};
	}
	return {
		answer:
			locale === "id"
				? "Berikut testimoni yang tercatat di data Sagita:"
				: "Here are testimonials recorded in Sagita's data:",
		cards: records.slice(0, 3).map((record) => ({
			title: record.name,
			description:
				[record.role, record.content.slice(0, 180)]
					.filter(Boolean)
					.join(" · ") || record.name,
			actions: [
				{
					label: locale === "id" ? "Lihat paket" : "View packages",
					href: "/packages",
				},
			],
		})),
		offerContact: true,
		confidence: 0.9,
		suggestions: [packageContactSuggestion(locale)],
	};
}

function answerOfficeAddress(
	locale: Locale,
	options?: SitaServiceOptions,
): SitaAnswer {
	const address = asText(options?.officeAddress);
	const base: SitaAnswer = {
		answer: address
			? locale === "id"
				? `Alamat kantor Sagita: ${address}. Untuk petunjuk arah, silakan hubungi admin.`
				: `Sagita office address: ${address}. Contact the team for directions.`
			: locale === "id"
				? "Alamat kantor belum tercatat di data situs Sagita. Silakan hubungi admin untuk alamat dan petunjuk arah."
				: "The office address is not listed in Sagita's site data. Contact the team for the address and directions.",
		cards: [],
		offerContact: true,
		confidence: 0.9,
		suggestions: [packageContactSuggestion(locale)],
	};
	if (!address) return base;
	const mapUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
	return {
		...base,
		cards: [
			{
				title: locale === "id" ? "Peta lokasi" : "Location map",
				description: address,
				actions: [
					{
						label: locale === "id" ? "Buka peta" : "Open map",
						href: mapUrl,
					},
				],
			},
		],
	};
}

async function answerFaq(
	message: string,
	locale: Locale,
): Promise<SitaAnswer | null> {
	const payload = await getPageBatch("faq");
	const records = faqRecords(payload);
	const queryTokens = tokenize(message);
	const ranked = records
		.map((record) => {
			const questionScore = matchScore(
				queryTokens,
				`${record.question} ${record.category}`,
			);
			const answerScore = matchScore(queryTokens, record.answer);
			return {
				record,
				score: questionScore * 2 + answerScore,
				coverage: questionScore / Math.max(queryTokens.length, 1),
			};
		})
		.filter(({ score, coverage }) => score > 0 && coverage >= 0.5)
		.sort((a, b) => b.score - a.score);
	const best = ranked[0];
	if (!best || queryTokens.length === 0) {
		return null;
	}

	const offerContact = /\b(whatsapp|hubungi|admin)\b/i.test(best.record.answer);
	return {
		answer: best.record.answer,
		cards: [],
		offerContact,
		confidence: 0.8,
		source: {
			label: locale === "id" ? "Lihat sumber FAQ" : "View FAQ source",
			href: "/faq",
		},
		...(offerContact
			? {
					suggestions: [
						{
							label: locale === "id" ? "Hubungi admin" : "Contact the team",
							action: "contact",
						},
					],
				}
			: {}),
	};
}

function secondaryChipLabel(topic: SitaChatTopic, locale: Locale): string {
	if (topic === "venues") {
		return locale === "id" ? "Lihat venue" : "View venues";
	}
	if (topic === "vendors") {
		return locale === "id" ? "Lihat vendor" : "View vendors";
	}
	return locale === "id" ? "Lihat paket & harga" : "View packages";
}

function sourceFor(
	analysis: TopicAnalysis,
	locale: Locale,
): SitaSource | undefined {
	const topic = analysis.primary ?? analysis.secondary;
	if (!topic) return undefined;
	if (topic === "packages") {
		return {
			label: locale === "id" ? "Lihat sumber paket" : "View package data",
			href: "/packages",
		};
	}
	if (topic === "venues") {
		return {
			label: locale === "id" ? "Lihat sumber venue" : "View venue data",
			href: "/venues",
		};
	}
	return {
		label: locale === "id" ? "Lihat sumber vendor" : "View vendor data",
		href: "/vendors",
	};
}

/**
 * Lengkapi jawaban dengan confidence (bila belum ada) dan chip topik kedua
 * ketika pesan memuat dua maksud kuat sekaligus (mis. "paket sama venue").
 */
function finalizeAnswer(
	answer: SitaAnswer,
	routeConfidence: number,
	analysis: TopicAnalysis,
	locale: Locale,
): SitaAnswer {
	let suggestions = answer.suggestions ? [...answer.suggestions] : [];
	if (analysis.secondary && !answer.unanswered) {
		const label = secondaryChipLabel(analysis.secondary, locale);
		if (!suggestions.some((suggestion) => suggestion.label === label)) {
			suggestions = [...suggestions, { label, topic: analysis.secondary }];
		}
	}
	const source = answer.source ?? sourceFor(analysis, locale);
	return {
		...answer,
		confidence: answer.confidence ?? routeConfidence,
		...(suggestions.length ? { suggestions } : {}),
		...(source ? { source } : {}),
	};
}

const PROMO_INTENT =
	/\b(promo|promosi|diskon|discounts?|potongan|deals?|voucher|kode\s+promo)\b/i;
const PAYMENT_INTENT =
	/\b(pembayaran|bayar|dp\b|down\s*payment|cicilan|installments?|termin|pelunasan|payment)\b/i;
const AVAILABILITY_INTENT =
	/\b(tersedia\w*|ketersediaan|available|availability|kosong|jadwal|schedule)\b/i;
const DATE_INTENT =
	/\b(tanggal\w*|date|20\d{2}-\d{1,2}-\d{1,2}|\d{1,2}\/\d{1,2}\/20\d{2})\b/i;
const TESTIMONIAL_INTENT =
	/\b(testimoni|testimonials?|review|ulasan|pengalaman\s+(?:klien|pelanggan)|kata\s+klien)\b/i;
const OFFICE_INTENT =
	/\b(alamat\s+(?:kantor|kami|sagita)|kantor\s+(?:sagita|kami|di\s+mana)|lokasi\s+kantor|office(?:\s+address|\s+location)?|where\s+(?:are\s+you|(?:is|are)\s+(?:your|the)\s+office)|dimana\s+kantor)\b/i;

export async function answerSitaQuestion(
	message: string,
	locale: Locale,
	context?: SitaChatContext,
	options?: SitaServiceOptions,
): Promise<SitaAnswer> {
	if (CONTACT_TERMS.test(message)) {
		return {
			answer:
				locale === "id"
					? "Untuk menghubungi admin, pilih tombol “Hubungi admin” agar formulir kontak terbuka."
					: "To contact the team, select “Contact the team” to open the contact form.",
			cards: [],
			offerContact: true,
			confidence: 0.95,
			suggestions: [
				{
					label: locale === "id" ? "Hubungi admin" : "Contact the team",
					action: "contact",
				},
			],
		};
	}

	const analysis = detectTopics(message);

	const kind = smallTalkKind(message);
	if (kind) return smallTalkAnswer(locale, kind);

	if (
		WEDDING_SERVICE_TERMS.test(message) &&
		!PACKAGE_NAME_TERMS.test(message) &&
		!VENUE_TERMS.test(message) &&
		!VENDOR_TERMS.test(message)
	) {
		return weddingServiceGuidance(locale);
	}

	if (PROMO_INTENT.test(message)) {
		return finalizeAnswer(
			await answerPromotions(locale),
			0.9,
			analysis,
			locale,
		);
	}
	if (PAYMENT_INTENT.test(message)) {
		return finalizeAnswer(await answerPayment(locale), 0.9, analysis, locale);
	}
	if (
		AVAILABILITY_INTENT.test(message) &&
		(DATE_INTENT.test(message) || Boolean(context?.criteria?.eventDate))
	) {
		return finalizeAnswer(
			await answerAvailability(message, locale, context?.criteria),
			0.9,
			analysis,
			locale,
		);
	}
	if (TESTIMONIAL_INTENT.test(message)) {
		return finalizeAnswer(
			await answerTestimonials(locale),
			0.9,
			analysis,
			locale,
		);
	}
	if (OFFICE_INTENT.test(message) && !VENUE_TERMS.test(message)) {
		return finalizeAnswer(
			answerOfficeAddress(locale, options),
			0.9,
			analysis,
			locale,
		);
	}

	const forcedVendor =
		hasExplicitVendorCategory(message) &&
		!PACKAGE_NAME_TERMS.test(message) &&
		!VENUE_TERMS.test(message);
	const explicitTopic: SitaChatTopic | null = forcedVendor
		? "vendors"
		: (analysis.primary ?? null);
	const topic = explicitTopic ?? context?.topic;
	const routeConfidence = explicitTopic ? 0.9 : topic ? 0.85 : 0.5;
	const routedAnalysis: TopicAnalysis = forcedVendor
		? { ...analysis, secondary: undefined }
		: analysis;

	let answer: SitaAnswer;
	if (topic === "packages") {
		answer = await answerPackages(message, locale, context?.criteria);
	} else if (topic === "venues") {
		if (!VENUE_PRICE_TERMS.test(message)) {
			answer = await answerVenues(message, locale, context?.criteria);
		} else {
			const faqAnswer = await answerFaq(message, locale);
			answer = faqAnswer
				? { ...faqAnswer, offerContact: true }
				: venueGuidance(locale);
		}
	} else if (topic === "vendors") {
		answer = await answerVendors(message, locale, context?.criteria);
	} else {
		answer = (await answerFaq(message, locale)) ?? fallback(locale);
	}
	return finalizeAnswer(answer, routeConfidence, routedAnalysis, locale);
}
