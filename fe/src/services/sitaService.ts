import {
	cleanSlugLoose,
	type SitaChatContext,
	type SitaChatCriteria,
	type SitaChatTopic,
} from "../utils/validate.js";
import { getPageBatch } from "./batchService.js";
import {
	detectTopics,
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
	final_price?: unknown;
	price?: unknown;
	discount_price?: unknown;
}

interface PackageRecord {
	name: string;
	slug: string;
	description: string;
	image: string;
	guestCapacity: string;
	guestCapacityValue: number | null;
	popular: boolean;
	options: PackageOption[];
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
	fee: "biaya",
	gedung: "venue",
	harga: "biaya",
	hrg: "biaya",
	hrga: "biaya",
	jasa: "layanan",
	lokasi: "venue",
	order: "booking",
	pemesanan: "booking",
	pesan: "booking",
	pket: "paket",
	pkt: "paket",
	place: "venue",
	price: "biaya",
	pricing: "biaya",
	reservasi: "booking",
	service: "layanan",
	services: "layanan",
	tarif: "biaya",
	tempat: "venue",
	venu: "venue",
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
				options,
			},
		].filter((record) => record.name.length > 0);
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
	"plus",
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
			!/^\d+$/.test(token),
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
					? "Halo Kak! Aku Sita AI, asisten digital Sagita Wedding. Mau tanya soal paket, venue, vendor, atau cara booking?"
					: "Hi! I'm Sita AI, the Sagita Wedding digital assistant. Want to ask about packages, venues, vendors, or booking?",
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
			const details = [
				record.description,
				record.guestCapacity
					? `${locale === "id" ? "Kapasitas" : "Capacity"}: ${record.guestCapacity}`
					: "",
				price !== null
					? `${locale === "id" ? "Mulai dari" : "Starting at"} ${formatPrice(price, locale)}`
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
			!matchesAnyTopicTerm(token, ["packages", "venues"]),
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
			!matchesAnyTopicTerm(token, ["packages", "venues", "vendors"]),
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
	return {
		...answer,
		confidence: answer.confidence ?? routeConfidence,
		...(suggestions.length ? { suggestions } : {}),
	};
}

export async function answerSitaQuestion(
	message: string,
	locale: Locale,
	context?: SitaChatContext,
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

	const explicitTopic: SitaChatTopic | null = analysis.primary ?? null;
	const topic = explicitTopic ?? context?.topic;
	const routeConfidence = explicitTopic ? 0.9 : topic ? 0.85 : 0.5;

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
	return finalizeAnswer(answer, routeConfidence, analysis, locale);
}
