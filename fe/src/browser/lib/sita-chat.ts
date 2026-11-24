import { detectTopics, type TopicAliases } from "../../services/sitaNlu.js";
import { topicAliasesFromPayload } from "../../services/sitaTopicAliases.js";
import { sanitizeSitaQuerySample } from "../../utils/sitaQuery.js";
import { hashSitaQuestion } from "../telemetry.js";

(() => {
	interface ChatCard {
		title: string;
		description: string;
		image?: string;
		actions?: {
			label: string;
			href: string;
		}[];
	}

	function isChatCardAction(
		value: unknown,
	): value is NonNullable<ChatCard["actions"]>[number] {
		if (!value || typeof value !== "object" || Array.isArray(value)) {
			return false;
		}
		const action = value as Record<string, unknown>;
		return typeof action.label === "string" && typeof action.href === "string";
	}

	function isChatCard(value: unknown): value is ChatCard {
		if (!value || typeof value !== "object" || Array.isArray(value)) {
			return false;
		}
		const card = value as Record<string, unknown>;
		return (
			typeof card.title === "string" &&
			typeof card.description === "string" &&
			(card.image === undefined || typeof card.image === "string") &&
			(card.actions === undefined ||
				(Array.isArray(card.actions) && card.actions.every(isChatCardAction)))
		);
	}

	interface ChatSuggestion {
		label: string;
		action?: "contact";
		topic?: ChatTopic;
	}

	interface ChatClarify {
		question: string;
		options: string[];
	}

	interface ChatSource {
		label: string;
		href: string;
	}

	interface ChatResult {
		success: boolean;
		answer: string;
		cards: ChatCard[];
		offerContact: boolean;
		unanswered?: boolean;
		confidence?: number;
		suggestions?: ChatSuggestion[];
		clarify?: ChatClarify;
		source?: ChatSource;
		topic?: ChatTopic;
	}

	function isChatTopic(value: unknown): value is ChatTopic {
		return value === "packages" || value === "venues" || value === "vendors";
	}

	function isChatClarify(value: unknown): value is ChatClarify {
		if (!value || typeof value !== "object" || Array.isArray(value)) {
			return false;
		}
		const clarify = value as Record<string, unknown>;
		return (
			typeof clarify.question === "string" &&
			Array.isArray(clarify.options) &&
			clarify.options.length > 0 &&
			clarify.options.length <= 6 &&
			clarify.options.every(
				(option) => typeof option === "string" && option.length <= 120,
			)
		);
	}

	function isChatSource(value: unknown): value is ChatSource {
		return isChatCardAction(value);
	}

	function isChatSuggestion(value: unknown): value is ChatSuggestion {
		if (!value || typeof value !== "object" || Array.isArray(value)) {
			return false;
		}
		const suggestion = value as Record<string, unknown>;
		return (
			typeof suggestion.label === "string" &&
			(suggestion.action === undefined || suggestion.action === "contact") &&
			(suggestion.topic === undefined || isChatTopic(suggestion.topic))
		);
	}

	const widget = document.getElementById("sitaChatWidget");
	const toggle = document.getElementById("sitaChatToggle");
	const panel = document.getElementById("sitaChatPanel");
	const closeButton = document.getElementById("sitaChatClose");
	const resetButton = document.getElementById("sitaChatReset");
	const onboarding = document.getElementById("sitaChatOnboarding");
	const choices = document.getElementById("sitaChatChoices");
	const leadForm = document.getElementById("sitaChatLead");
	const composer = document.getElementById("sitaChatComposer");
	const input = document.getElementById("sitaChatInput");
	const messages = document.getElementById("sitaChatMessages");

	if (!(widget instanceof HTMLElement)) return;
	if (!(toggle instanceof HTMLButtonElement)) return;
	if (!panel) return;
	if (!(closeButton instanceof HTMLButtonElement)) return;
	if (!choices) return;
	if (!(leadForm instanceof HTMLFormElement)) return;
	if (!(composer instanceof HTMLFormElement)) return;
	if (!(input instanceof HTMLInputElement)) return;
	if (!messages) return;

	const chatWidget = widget;
	const chatToggle = toggle;
	const chatPanel = panel;
	const chatCloseButton = closeButton;
	const chatChoices = choices;
	const chatLeadForm = leadForm;
	const chatComposer = composer;
	const chatInput = input;
	const chatMessages = messages;
	const visibleChoiceLabels = new Set(
		[...chatChoices.querySelectorAll<HTMLButtonElement>("button")].map(
			(button) => button.textContent?.trim().toLocaleLowerCase() || "",
		),
	);

	const locale = chatWidget.dataset.locale === "en" ? "en" : "id";
	type ChatTopic = "packages" | "venues" | "vendors";
	type GuideStep = "date" | "city" | "budget" | "guests";
	interface ChatCriteria {
		budgetLimit?: number;
		guestCount?: number;
		city?: string;
		eventDate?: string;
		vendorCategory?: string;
	}
	const copy = {
		id: {
			you: "Kamu",
			sita: "Sita",
			contact:
				"Silakan isi nama dan nomor WhatsApp untuk menyiapkan pesan ke admin Sagita.",
			whatsapp: "Lanjut ke WhatsApp",
			handoff:
				"Pesan sudah disiapkan. Periksa kembali sebelum mengirimnya melalui WhatsApp.",
			invalidContact:
				"Periksa nama, nomor WhatsApp, dan persetujuan kamu terlebih dahulu.",
			invalidLink:
				"Link WhatsApp belum tersedia. Silakan gunakan tombol WhatsApp di halaman.",
			empty: "Tulis pertanyaan terlebih dahulu.",
			loading: "Sedang mencari informasi di data Sagita…",
			unavailable:
				"Data Sagita sedang tidak dapat diakses. Coba lagi sebentar atau hubungi admin melalui WhatsApp.",
			rateLimited:
				"Terlalu banyak pertanyaan dalam satu menit. Aku jeda sebentar, ya.",
			rateLimitedRetry: (seconds: number) =>
				`Bisa kirim lagi dalam ${seconds} detik.`,
			skipped: "Baik, kamu bisa melanjutkan pertanyaan kapan saja.",
			reset: "Riwayat dan kriteria sudah dihapus.",
			retry: "Coba lagi",
			guideStart:
				"Aku bantu isi kebutuhanmu dulu, ya. Ini pertanyaan pertamaku.",
			guideDate: "Kapan rencana acaranya? Tulis tanggal seperti 2026-12-10.",
			guideCity: "Di kota mana acaranya?",
			guideBudget: "Berapa perkiraan anggaran maksimalmu? Contoh: 100 juta.",
			guideGuests: "Berapa perkiraan jumlah tamu? Contoh: 150 tamu.",
			guideDone:
				"Siap, ini ringkasan kebutuhanmu. Aku carikan pilihan yang paling relevan.",
			guideInvalid:
				"Belum terbaca, ya. Coba jawab dengan contoh format yang aku berikan.",
		},
		en: {
			you: "You",
			sita: "Sita",
			contact:
				"Enter your name and WhatsApp number to prepare a message for the Sagita team.",
			whatsapp: "Continue to WhatsApp",
			handoff:
				"Your message is ready. Review it before sending it through WhatsApp.",
			invalidContact:
				"Check your name, WhatsApp number, and consent before continuing.",
			invalidLink:
				"The WhatsApp link is unavailable. Please use the WhatsApp button on the page.",
			empty: "Please enter a question first.",
			loading: "Searching Sagita data…",
			unavailable:
				"Sagita data is temporarily unavailable. Try again or contact the team on WhatsApp.",
			rateLimited: "Too many questions in one minute. I'll take a short break.",
			rateLimitedRetry: (seconds: number) =>
				`You can send again in ${seconds} seconds.`,
			skipped: "No problem. You can continue asking questions anytime.",
			reset: "History and criteria have been cleared.",
			retry: "Try again",
			guideStart:
				"Let me collect a few details first. Here's my first question.",
			guideDate: "When is the event? Please use a date like 2026-12-10.",
			guideCity: "Which city will the event be in?",
			guideBudget:
				"What is your approximate maximum budget? For example: 100 million.",
			guideGuests: "How many guests do you expect? For example: 150 guests.",
			guideDone:
				"Great, here's your requirements summary. I'll find the most relevant options.",
			guideInvalid: "I couldn't read that. Please use the example format.",
		},
	}[locale];

	let returnFocus: HTMLElement = chatToggle;
	let selectedQuestion = "";
	let lastFailedQuestion = "";
	let activeTopic: ChatTopic | undefined;
	let guideStep: GuideStep | null = null;
	const activeCriteria: ChatCriteria = {};
	let isSending = false;
	let rateLimitedUntil = 0;
	const minimumTypingDuration = 1200;
	const historyKey = "sita-chat-history-v1";
	const maxHistoryMessages = 20;
	let turnCounter = 0;

	function createTurnId(): string {
		turnCounter += 1;
		if (
			typeof crypto !== "undefined" &&
			typeof crypto.randomUUID === "function"
		) {
			return crypto.randomUUID();
		}
		return `turn-${Date.now()}-${turnCounter}`;
	}

	interface StoredChatMessage {
		sender: "you" | "sita";
		text: string;
	}

	function readHistory(): StoredChatMessage[] {
		try {
			const raw = sessionStorage.getItem(historyKey);
			if (!raw) return [];
			const parsed: unknown = JSON.parse(raw);
			if (!Array.isArray(parsed)) return [];
			return parsed.flatMap((item) => {
				if (!item || typeof item !== "object" || Array.isArray(item)) {
					return [];
				}
				const record = item as Record<string, unknown>;
				if (
					(record.sender !== "you" && record.sender !== "sita") ||
					typeof record.text !== "string" ||
					!record.text.trim()
				) {
					return [];
				}
				return [{ sender: record.sender, text: record.text.slice(0, 5000) }];
			});
		} catch {
			return [];
		}
	}

	function appendHistory(message: StoredChatMessage) {
		const history = readHistory();
		history.push(message);
		try {
			sessionStorage.setItem(
				historyKey,
				JSON.stringify(history.slice(-maxHistoryMessages)),
			);
		} catch {
			return;
		}
	}

	let topicAliasesCache: { aliases: TopicAliases; expiresAt: number } | null =
		null;

	async function dynamicTopicAliases(): Promise<TopicAliases> {
		if (topicAliasesCache && topicAliasesCache.expiresAt > Date.now()) {
			return topicAliasesCache.aliases;
		}
		try {
			const response = await fetch("/api/sita/knowledge", {
				headers: { Accept: "application/json" },
				cache: "no-store",
			});
			if (!response.ok) return {};
			const aliases = topicAliasesFromPayload(await response.json(), locale);
			topicAliasesCache = { aliases, expiresAt: Date.now() + 60_000 };
			return aliases;
		} catch {
			return {};
		}
	}

	async function topicFromQuestion(
		question: string,
	): Promise<ChatTopic | undefined> {
		return detectTopics(question, await dynamicTopicAliases()).primary;
	}

	function isContextualFollowUp(question: string): boolean {
		if (/^\s*(halo|hai|hi|makasih|terima kasih|thank you)\b/i.test(question)) {
			return false;
		}
		return (
			/\b(yang|itu|tersebut|di|buat|untuk|dengan|lebih|murah|mahal|termurah|termahal|budget|anggaran|tamu|orang|pax|bandingkan|kapasitas|maksimal|dibawah|bawah|under|below)\b|\d/i.test(
				question,
			) ||
			(Boolean(activeTopic) && question.trim().split(/\s+/).length <= 3)
		);
	}

	function parseAmount(value: string): number | undefined {
		let normalized = value.replace(/\s/g, "");
		const lastDot = normalized.lastIndexOf(".");
		const lastComma = normalized.lastIndexOf(",");
		if (lastDot >= 0 && lastComma >= 0) {
			normalized =
				lastComma > lastDot
					? normalized.replace(/\./g, "").replace(",", ".")
					: normalized.replace(/,/g, "");
		} else if (lastDot >= 0 || lastComma >= 0) {
			const separator = Math.max(lastDot, lastComma);
			const digitsAfter = normalized.length - separator - 1;
			if (
				normalized.indexOf(normalized[separator]) !== separator ||
				digitsAfter === 3
			) {
				normalized = normalized.replace(/[.,]/g, "");
			} else {
				normalized = normalized.replace(",", ".");
			}
		}
		const amount = Number(normalized);
		return Number.isFinite(amount) ? amount : undefined;
	}

	function parseBudgetLimit(question: string): number | undefined {
		const unitPattern = "(miliar|milyar|juta|jt|million|billion|ribu|rb|k)";
		const match =
			question.match(
				new RegExp(
					`\\b(?:rp|idr)\\.?\\s*([\\d][\\d.,]*)\\s*${unitPattern}?`,
					"i",
				),
			) ||
			question.match(
				new RegExp(
					`\\b(?:budget|anggaran|bujet|maksimal|max(?:imum)?|di\\s*bawah|bawah|under|below)\\b[^0-9]{0,20}([\\d][\\d.,]*)\\s*${unitPattern}?`,
					"i",
				),
			) ||
			question.match(new RegExp(`([\\d][\\d.,]*)\\s*${unitPattern}\\b`, "i"));
		if (!match) return undefined;
		const amount = parseAmount(match[1]);
		if (amount === undefined) return undefined;
		const unit = match[2]?.toLowerCase();
		const multiplier =
			unit === "miliar" || unit === "milyar" || unit === "billion"
				? 1_000_000_000
				: unit === "juta" || unit === "jt" || unit === "million"
					? 1_000_000
					: unit === "ribu" || unit === "rb" || unit === "k"
						? 1_000
						: 1;
		const budgetLimit = amount * multiplier;
		return Number.isFinite(budgetLimit) && budgetLimit <= 1_000_000_000_000
			? budgetLimit
			: undefined;
	}

	function parseGuestCount(question: string): number | undefined {
		const match =
			question.match(/\b([\d][\d.,]*)\s*(?:orang|tamu|guests?|pax)\b/i) ||
			question.match(/\b(?:tamu|guests?|pax)\s*([\d][\d.,]*)\b/i);
		if (!match) return undefined;
		const count = Number(match[1].replace(/\D/g, ""));
		return Number.isSafeInteger(count) && count > 0 && count <= 100_000
			? count
			: undefined;
	}

	function parseCity(question: string): string | undefined {
		const match = question.match(
			/\b(?:di|kota)\s+([A-Za-z][A-Za-z\s-]{1,60})/i,
		);
		let city = match?.[1];
		if (!city && activeTopic === "venues") {
			city = question
				.replace(/^\s*(?:venue|venues|lokasi|tempat|kota|yang)\b/i, "")
				.trim();
		}
		if (!city) return undefined;
		const candidate = city
			.split(
				/[,;.!?]|\b(?:untuk|tanggal|tamu|orang|pax|anggaran|budget|dengan|date|guests?)\b|(?=\d)/i,
			)[0]
			.trim()
			.split(/\s+/)
			.slice(0, 3)
			.join(" ");
		if (
			candidate.length < 3 ||
			/\b(ini|itu|tersebut|lebih|murah|mahal|termurah|termahal|besok|hari|layanan|wo|organizer|booking|paket|vendor|admin|harga|biaya|konsultasi|cara|halo|hai|makasih|terima|kasih|iya|oke|cocok|rekomendasi|gedung|bawah|atas|depan|belakang|sini|sana|situ|bulan|minggu|tahun)\b/i.test(
				candidate,
			)
		) {
			return undefined;
		}
		return candidate;
	}

	function parseEventDate(question: string): string | undefined {
		const isoDate = question.match(/\b(20\d{2}-\d{2}-\d{2})\b/)?.[1];
		if (isoDate && isValidDate(isoDate)) return isoDate;
		const localDate = question.match(/\b(\d{1,2})\/(\d{1,2})\/(20\d{2})\b/);
		if (!localDate) return undefined;
		const date = `${localDate[3]}-${localDate[2].padStart(2, "0")}-${localDate[1].padStart(2, "0")}`;
		return isValidDate(date) ? date : undefined;
	}

	function isValidDate(value: string): boolean {
		const date = new Date(`${value}T00:00:00Z`);
		return (
			!Number.isNaN(date.getTime()) && date.toISOString().slice(0, 10) === value
		);
	}

	function parseVendorCategory(question: string): string | undefined {
		if (/\b(catering|katering)\b/i.test(question)) return "Catering";
		if (/\b(dekor|dekorasi|decoration)\b/i.test(question)) return "Dekorasi";
		if (/\b(fotografi|fotografer|photography|photographer)\b/i.test(question)) {
			return "Fotografi";
		}
		if (/\b(makeup|mua|rias)\b/i.test(question)) return "Makeup dan rias";
		if (/\b(dj|band|musik|music|hiburan|entertainment)\b/i.test(question)) {
			return "Hiburan";
		}
		return undefined;
	}

	function criteriaSummary(): string[] {
		const labels =
			locale === "id"
				? {
						summary: "Ringkasan kebutuhan:",
						topic: "Topik",
						budget: "Anggaran maksimal",
						guests: "Jumlah tamu",
						city: "Kota",
						date: "Tanggal acara",
						vendor: "Kategori vendor",
					}
				: {
						summary: "Requirements summary:",
						topic: "Topic",
						budget: "Maximum budget",
						guests: "Guest count",
						city: "City",
						date: "Event date",
						vendor: "Vendor category",
					};
		const lines = [labels.summary];
		if (activeTopic) {
			lines.push(`${labels.topic}: ${activeTopic}`);
		}
		if (activeCriteria.budgetLimit !== undefined) {
			lines.push(
				`${labels.budget}: ${new Intl.NumberFormat(locale === "id" ? "id-ID" : "en-US").format(activeCriteria.budgetLimit)}`,
			);
		}
		if (activeCriteria.guestCount !== undefined) {
			lines.push(`${labels.guests}: ${activeCriteria.guestCount}`);
		}
		if (activeCriteria.city)
			lines.push(`${labels.city}: ${activeCriteria.city}`);
		if (activeCriteria.eventDate) {
			lines.push(`${labels.date}: ${activeCriteria.eventDate}`);
		}
		if (activeCriteria.vendorCategory) {
			lines.push(`${labels.vendor}: ${activeCriteria.vendorCategory}`);
		}
		return lines.length > 1 ? lines : [];
	}

	function leadScore(): number {
		let score = 10;
		if (activeTopic) score += 5;
		if (activeCriteria.eventDate) {
			score += 20;
			const eventDate = new Date(`${activeCriteria.eventDate}T00:00:00Z`);
			const days = (eventDate.getTime() - Date.now()) / 86_400_000;
			if (Number.isFinite(days) && days >= 0 && days <= 365) score += 10;
		}
		if (activeCriteria.budgetLimit !== undefined) score += 20;
		if (activeCriteria.guestCount !== undefined) score += 15;
		if (activeCriteria.city) score += 15;
		if (activeCriteria.vendorCategory) score += 5;
		return Math.min(score, 100);
	}

	function guidePrompt(step: GuideStep): string {
		if (step === "date") return copy.guideDate;
		if (step === "city") return copy.guideCity;
		if (step === "budget") return copy.guideBudget;
		return copy.guideGuests;
	}

	function isBookingIntent(value: string): boolean {
		return /^(?:cara booking|bagaimana cara booking|booking|how to book|booking process)\??$/i.test(
			value.trim(),
		);
	}

	function startGuide(): void {
		guideStep = "date";
		activeTopic = "packages";
		selectedQuestion =
			locale === "id"
				? "Saya ingin booking paket pernikahan"
				: "I want to book a wedding package";
		hideLeadForm();
		onboarding?.classList.add("hidden");
		addMessage(copy.you, selectedQuestion);
		addMessage(copy.sita, `${copy.guideStart}\n${copy.guideDate}`);
		chatInput.focus();
	}

	function finishGuide(): void {
		guideStep = null;
		const parts = [
			locale === "id"
				? "Rekomendasikan paket pernikahan"
				: "Recommend wedding packages",
		];
		if (activeCriteria.eventDate) {
			parts.push(
				locale === "id"
					? `untuk tanggal ${activeCriteria.eventDate}`
					: `for ${activeCriteria.eventDate}`,
			);
		}
		if (activeCriteria.city) {
			parts.push(
				locale === "id"
					? `di ${activeCriteria.city}`
					: `in ${activeCriteria.city}`,
			);
		}
		if (activeCriteria.guestCount !== undefined) {
			parts.push(
				`${activeCriteria.guestCount} ${locale === "id" ? "tamu" : "guests"}`,
			);
		}
		if (activeCriteria.budgetLimit !== undefined) {
			const budget = new Intl.NumberFormat(
				locale === "id" ? "id-ID" : "en-US",
			).format(activeCriteria.budgetLimit);
			parts.push(
				locale === "id"
					? `dengan budget maksimal ${budget}`
					: `with a maximum budget of ${budget}`,
			);
		}
		const summary = criteriaSummary();
		addMessage(
			copy.sita,
			summary.length
				? `${copy.guideDone}\n${summary.join("\n")}`
				: copy.guideDone,
		);
		void askSita(parts.join(" "), "packages", false);
	}

	function handleGuideAnswer(answer: string): void {
		if (!guideStep) return;
		const value = answer.trim();
		if (guideStep === "date") {
			const date = parseEventDate(value);
			if (!date) {
				askAgain();
				return;
			}
			activeCriteria.eventDate = date;
			guideStep = "city";
		} else if (guideStep === "city") {
			const cityInput = /^(?:di|kota|in|at)\s+/i.test(value)
				? value
				: `di ${value}`;
			const city = parseCity(cityInput);
			if (!city) {
				askAgain();
				return;
			}
			activeCriteria.city = city;
			guideStep = "budget";
		} else if (guideStep === "budget") {
			const budget =
				parseBudgetLimit(value) ??
				(/^\s*[\d.,]+\s*$/.test(value) ? parseAmount(value) : undefined);
			if (
				budget === undefined ||
				!Number.isFinite(budget) ||
				budget <= 0 ||
				budget > 1_000_000_000_000
			) {
				askAgain();
				return;
			}
			activeCriteria.budgetLimit = budget;
			guideStep = "guests";
		} else {
			const guestInput =
				/\d/.test(value) && !/\b(?:tamu|guests?|orang|pax)\b/i.test(value)
					? `${value} tamu`
					: value;
			const guests = parseGuestCount(guestInput);
			if (!guests) {
				askAgain();
				return;
			}
			activeCriteria.guestCount = guests;
			finishGuide();
			return;
		}
		addMessage(copy.sita, guidePrompt(guideStep));
	}

	function askAgain(): void {
		if (!guideStep) return;
		addMessage(copy.sita, `${copy.guideInvalid}\n${guidePrompt(guideStep)}`);
	}

	function isSafeCardUrl(value: string): boolean {
		if (value.startsWith("/") && !value.startsWith("//")) return true;
		try {
			return ["http:", "https:"].includes(new URL(value).protocol);
		} catch {
			return false;
		}
	}

	function addMessage(
		sender: string,
		text: string,
		cards: ChatCard[] = [],
		link?: string,
		suggestions: ChatSuggestion[] = [],
		loading = false,
		clarify?: ChatClarify,
		persist = true,
		source?: ChatSource,
	): HTMLElement {
		const bubble = document.createElement("article");
		bubble.className = "sita-chat-message";
		if (sender === copy.sita && !loading) {
			bubble.dataset.sitaTurnId = createTurnId();
			if (activeTopic) bubble.dataset.sitaTopic = activeTopic;
		}
		bubble.classList.add(
			sender === copy.you
				? "sita-chat-message--user"
				: "sita-chat-message--assistant",
		);

		const label = document.createElement("span");
		label.className = "sita-chat-message__sender sr-only";
		label.textContent = sender;
		bubble.append(label);

		const content = document.createElement("p");
		content.className = "sita-chat-message__text";
		if (loading) {
			bubble.classList.add("sita-chat-message--loading");
			content.classList.add("sita-chat-message__text--loading");
			const accessibleText = document.createElement("span");
			accessibleText.className = "sr-only";
			accessibleText.textContent = text;
			content.append(accessibleText);
			for (let index = 0; index < 3; index += 1) {
				const dot = document.createElement("span");
				dot.className = "sita-chat-loading-dot";
				dot.setAttribute("aria-hidden", "true");
				content.append(dot);
			}
		} else {
			content.textContent = text;
		}
		bubble.append(content);

		if (clarify && !loading) {
			const clarifyGroup = document.createElement("div");
			clarifyGroup.className = "sita-chat-clarify";
			clarifyGroup.setAttribute("role", "group");
			clarifyGroup.setAttribute(
				"aria-label",
				locale === "id" ? "Jawaban singkat" : "Quick answers",
			);
			const question = document.createElement("p");
			question.className = "sita-chat-clarify__question";
			question.textContent = clarify.question;
			clarifyGroup.append(question);
			for (const option of clarify.options) {
				const button = document.createElement("button");
				button.type = "button";
				button.dataset.sitaClarify = option;
				button.textContent = option;
				clarifyGroup.append(button);
			}
			bubble.append(clarifyGroup);
		}

		if (cards.length) {
			bubble.classList.add("sita-chat-message--with-cards");
			const cardList = document.createElement("div");
			cardList.className = "sita-chat-cards";
			for (const card of cards) {
				const cardElement = document.createElement("article");
				cardElement.className = "sita-chat-card";

				if (card.image && isSafeCardUrl(card.image)) {
					const image = document.createElement("img");
					image.src = card.image;
					image.alt = card.title;
					image.loading = "lazy";
					image.decoding = "async";
					image.addEventListener("error", () => image.remove(), {
						once: true,
					});
					cardElement.append(image);
				}

				const cardContent = document.createElement("div");
				cardContent.className = "sita-chat-card__content";
				const title = document.createElement("h3");
				title.textContent = card.title;
				cardContent.append(title);

				if (card.description) {
					const description = document.createElement("p");
					description.textContent = card.description;
					cardContent.append(description);
				}

				if (card.actions?.length) {
					const actions = document.createElement("div");
					actions.className = "sita-chat-card__actions";
					for (const [index, action] of card.actions.entries()) {
						if (!isSafeCardUrl(action.href)) continue;
						const cardLink = document.createElement("a");
						cardLink.className = "sita-chat-card__action";
						if (index > 0) {
							cardLink.classList.add("sita-chat-card__action--secondary");
						}
						cardLink.href = action.href;
						cardLink.textContent = action.label;
						if (action.href === "/formulir-konsultasi") {
							cardLink.addEventListener("click", () => {
								const topic = /\b(wo|wedding organizer|layanan)\b/i.test(
									selectedQuestion,
								)
									? "services"
									: activeTopic;
								try {
									sessionStorage.setItem(
										"sita-consultation-prefill",
										JSON.stringify({
											...(topic ? { topic } : {}),
											criteria: activeCriteria,
										}),
									);
								} catch (error) {
									console.error(
										"Could not save Sita consultation criteria",
										error,
									);
								}
							});
						}
						if (/^https?:\/\//i.test(action.href)) {
							cardLink.target = "_blank";
							cardLink.rel = "noopener noreferrer";
						}
						actions.append(cardLink);
					}
					if (actions.childElementCount) cardContent.append(actions);
				}
				cardElement.append(cardContent);
				cardList.append(cardElement);
			}
			bubble.append(cardList);
		}

		if (link) {
			const handoff = document.createElement("a");
			handoff.className = "sita-chat-handoff";
			handoff.href = link;
			handoff.target = "_blank";
			handoff.rel = "noopener noreferrer";
			handoff.textContent = copy.whatsapp;
			bubble.append(handoff);
		}

		if (source && isSafeCardUrl(source.href)) {
			const sourceLink = document.createElement("a");
			sourceLink.className = "sita-chat-source";
			sourceLink.href = source.href;
			sourceLink.textContent = source.label;
			if (/^https?:\/\//i.test(source.href)) {
				sourceLink.target = "_blank";
				sourceLink.rel = "noopener noreferrer";
			}
			bubble.append(sourceLink);
		}

		if (!loading && sender === copy.sita) {
			const feedback = document.createElement("div");
			feedback.className = "sita-chat-feedback";
			feedback.setAttribute("role", "group");
			feedback.setAttribute(
				"aria-label",
				locale === "id" ? "Penilaian jawaban" : "Answer feedback",
			);
			const helpful = document.createElement("button");
			helpful.type = "button";
			helpful.dataset.sitaFeedback = "true";
			helpful.setAttribute("aria-pressed", "false");
			helpful.setAttribute(
				"aria-label",
				locale === "id" ? "Jawaban membantu" : "Helpful answer",
			);
			helpful.textContent = "👍";
			const unhelpful = document.createElement("button");
			unhelpful.type = "button";
			unhelpful.dataset.sitaFeedback = "false";
			unhelpful.setAttribute("aria-pressed", "false");
			unhelpful.setAttribute(
				"aria-label",
				locale === "id" ? "Jawaban tidak membantu" : "Unhelpful answer",
			);
			unhelpful.textContent = "👎";
			feedback.append(helpful, unhelpful);
			bubble.append(feedback);
		}

		if (!loading && persist) {
			appendHistory({
				sender: sender === copy.you ? "you" : "sita",
				text,
			});
		}

		chatMessages.append(bubble);
		if (suggestions.length) {
			const suggestionGroup = document.createElement("div");
			suggestionGroup.className = "sita-chat-suggestions";
			suggestionGroup.setAttribute("role", "group");
			suggestionGroup.setAttribute(
				"aria-label",
				locale === "id" ? "Saran pertanyaan" : "Suggested questions",
			);
			for (const suggestion of suggestions) {
				const button = document.createElement("button");
				button.type = "button";
				button.dataset.sitaSuggestion = suggestion.action || "ask";
				if (suggestion.topic) button.dataset.sitaTopic = suggestion.topic;
				button.textContent = suggestion.label;
				suggestionGroup.append(button);
			}
			chatMessages.append(suggestionGroup);
		}
		chatMessages.scrollTop = chatMessages.scrollHeight;
		return bubble;
	}

	function setOpen(open: boolean) {
		chatPanel.classList.toggle("hidden", !open);
		chatWidget.classList.toggle("sita-chat--open", open);
		chatToggle.setAttribute("aria-expanded", String(open));
		if (open) {
			returnFocus =
				document.activeElement instanceof HTMLElement
					? document.activeElement
					: chatToggle;
			chatInput.focus();
		} else {
			returnFocus.focus();
		}
	}

	function hideLeadForm() {
		chatLeadForm.classList.add("hidden");
		chatLeadForm.reset();
		chatPanel.classList.remove("sita-chat-panel--lead-open");
	}

	function resetSession() {
		for (const key of Object.keys(activeCriteria)) {
			delete activeCriteria[key as keyof ChatCriteria];
		}
		activeTopic = undefined;
		guideStep = null;
		selectedQuestion = "";
		try {
			sessionStorage.removeItem(historyKey);
			sessionStorage.removeItem("sita-consultation-prefill");
		} catch {
			return;
		}
		for (const child of [...chatMessages.children]) {
			if (!(child instanceof HTMLElement)) continue;
			if (
				child.classList.contains("sita-chat-message--user") ||
				child.classList.contains("sita-chat-message--assistant") ||
				child.classList.contains("sita-chat-suggestions")
			) {
				child.remove();
			}
		}
		hideLeadForm();
		onboarding?.classList.remove("hidden");
		addMessage(copy.sita, copy.reset, [], undefined, [], false);
		chatInput.focus();
	}

	function showContactForm(label: string) {
		guideStep = null;
		addMessage(copy.you, label);
		if (!selectedQuestion) selectedQuestion = label;
		addMessage(copy.sita, copy.contact);
		chatLeadForm.classList.remove("hidden");
		chatPanel.classList.add("sita-chat-panel--lead-open");
		chatLeadForm.querySelector<HTMLInputElement>("input[name='name']")?.focus();
	}

	async function askSita(
		question: string,
		topicHint?: string,
		echoQuestion = true,
	) {
		if (isSending) return;
		if (echoQuestion) guideStep = null;
		isSending = true;
		const requestStartedAt = Date.now();
		const hintedTopic: ChatTopic | undefined =
			topicHint === "packages" ||
			topicHint === "venues" ||
			topicHint === "vendors"
				? topicHint
				: undefined;
		const explicitTopic = (await topicFromQuestion(question)) || hintedTopic;
		const isFollowUp = isContextualFollowUp(question);
		if (explicitTopic) activeTopic = explicitTopic;
		else if (!isFollowUp) activeTopic = undefined;
		const contextTopic =
			explicitTopic || (isFollowUp ? activeTopic : undefined);
		activeCriteria.budgetLimit =
			parseBudgetLimit(question) ?? activeCriteria.budgetLimit;
		activeCriteria.guestCount =
			parseGuestCount(question) ?? activeCriteria.guestCount;
		activeCriteria.city = parseCity(question) ?? activeCriteria.city;
		activeCriteria.eventDate =
			parseEventDate(question) ?? activeCriteria.eventDate;
		activeCriteria.vendorCategory =
			parseVendorCategory(question) ?? activeCriteria.vendorCategory;
		hideLeadForm();
		onboarding?.classList.add("hidden");
		selectedQuestion = question;
		if (echoQuestion) addMessage(copy.you, question);
		const loadingMessage = addMessage(
			copy.sita,
			copy.loading,
			[],
			undefined,
			[],
			true,
		);
		chatMessages.setAttribute("aria-busy", "true");
		const sendButton = chatComposer.querySelector<HTMLButtonElement>(
			"button[type='submit']",
		);
		if (sendButton) sendButton.disabled = true;
		chatInput.disabled = true;
		const choiceButtons = [
			...chatChoices.querySelectorAll<HTMLButtonElement>("button"),
		];
		for (const button of choiceButtons) button.disabled = true;

		try {
			const response = await fetch("/api/sita/chat", {
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify({
					message: question,
					locale,
					...(contextTopic
						? {
								context: {
									topic: contextTopic,
									...(Object.keys(activeCriteria).length
										? { criteria: { ...activeCriteria } }
										: {}),
								},
							}
						: {}),
				}),
			});
			const result: unknown = await response.json();
			if (response.status === 429) {
				const retryHeader =
					response.headers.get("Retry-After") ??
					response.headers.get("RateLimit-Reset") ??
					"60";
				const parsedRetry = Number(retryHeader);
				const seconds = Math.min(
					120,
					Math.max(
						1,
						Number.isFinite(parsedRetry) ? Math.ceil(parsedRetry) : 60,
					),
				);
				loadingMessage.remove();
				rateLimitedUntil = Date.now() + seconds * 1000;
				const bubble = addMessage(
					copy.sita,
					copy.rateLimited,
					[],
					undefined,
					[],
					false,
					undefined,
					false,
				);
				const note = document.createElement("span");
				note.className = "sita-chat-rate-note";
				bubble.append(note);
				let remaining = seconds;
				const renderNote = () => {
					note.textContent = copy.rateLimitedRetry(remaining);
				};
				renderNote();
				const timer = window.setInterval(() => {
					remaining -= 1;
					if (remaining <= 0) {
						window.clearInterval(timer);
						note.remove();
						rateLimitedUntil = 0;
						if (sendButton) sendButton.disabled = false;
						chatInput.disabled = false;
						for (const button of choiceButtons) button.disabled = false;
						return;
					}
					renderNote();
				}, 1000);
				return;
			}
			if (!response.ok) throw new Error("Sita request failed");
			const data =
				result && typeof result === "object"
					? (result as Partial<ChatResult>)
					: null;
			if (
				!data?.success ||
				typeof data.answer !== "string" ||
				!Array.isArray(data.cards) ||
				!data.cards.every(isChatCard) ||
				typeof data.offerContact !== "boolean" ||
				(data.unanswered !== undefined &&
					typeof data.unanswered !== "boolean") ||
				(data.confidence !== undefined &&
					typeof data.confidence !== "number") ||
				(data.suggestions !== undefined &&
					(!Array.isArray(data.suggestions) ||
						!data.suggestions.every(isChatSuggestion))) ||
				(data.clarify !== undefined && !isChatClarify(data.clarify)) ||
				(data.source !== undefined && !isChatSource(data.source)) ||
				(data.topic !== undefined && !isChatTopic(data.topic))
			) {
				throw new Error("Invalid Sita response");
			}

			const typingRemaining =
				minimumTypingDuration - (Date.now() - requestStartedAt);
			if (typingRemaining > 0) {
				await new Promise<void>((resolve) => {
					window.setTimeout(resolve, typingRemaining);
				});
			}

			loadingMessage.remove();
			const lowConfidence =
				typeof data.confidence === "number" && data.confidence < 0.6;
			if (data.unanswered || lowConfidence) {
				window.dispatchEvent(
					new CustomEvent("sita:no-match", {
						detail: {
							topic: data.topic ?? contextTopic,
							queryHash: hashSitaQuestion(question),
							sample: sanitizeSitaQuerySample(question),
							fallback: Boolean(data.unanswered),
						},
					}),
				);
			}
			const suggestions = (data.suggestions || []).filter(
				(suggestion) =>
					!visibleChoiceLabels.has(suggestion.label.trim().toLocaleLowerCase()),
			);
			const answerBubble = addMessage(
				copy.sita,
				data.answer,
				data.cards,
				undefined,
				suggestions,
				false,
				data.clarify,
				true,
				data.source,
			);
			window.dispatchEvent(
				new CustomEvent("sita:answer", {
					detail: {
						topic: data.topic ?? contextTopic,
						confidence: data.confidence ?? 0,
						fallback: Boolean(data.unanswered),
						clarify: Boolean(data.clarify),
						responseMs: Date.now() - requestStartedAt,
						queryHash: hashSitaQuestion(question),
						turnId: answerBubble.dataset.sitaTurnId,
					},
				}),
			);
		} catch {
			loadingMessage.remove();
			hideLeadForm();
			chatChoices.classList.remove("hidden");
			lastFailedQuestion = question;
			const errorBubble = addMessage(copy.sita, copy.unavailable);
			const retryButton = document.createElement("button");
			retryButton.type = "button";
			retryButton.className = "sita-chat-retry";
			retryButton.dataset.sitaRetry = "true";
			retryButton.textContent = copy.retry;
			errorBubble.append(retryButton);
		} finally {
			chatMessages.setAttribute("aria-busy", "false");
			const stillRateLimited = rateLimitedUntil > Date.now();
			if (sendButton && !stillRateLimited) sendButton.disabled = false;
			if (!stillRateLimited) chatInput.disabled = false;
			for (const button of choiceButtons) button.disabled = stillRateLimited;
			isSending = false;
		}
	}

	chatToggle.addEventListener("click", () => {
		setOpen(chatPanel.classList.contains("hidden"));
	});
	chatCloseButton.addEventListener("click", () => setOpen(false));
	resetButton?.addEventListener("click", resetSession);
	document.addEventListener("keydown", (event) => {
		if (event.key === "Escape" && !chatPanel.classList.contains("hidden")) {
			setOpen(false);
		}
	});

	onboarding?.addEventListener("click", (event) => {
		const target = event.target;
		if (!(target instanceof Element)) return;
		const example = target.closest<HTMLButtonElement>("[data-sita-example]");
		if (!example) return;
		const exampleQuestion =
			example.dataset.sitaExample || example.textContent?.trim() || "";
		if (isBookingIntent(exampleQuestion)) {
			startGuide();
			return;
		}
		void askSita(exampleQuestion);
	});

	chatChoices.addEventListener("click", (event) => {
		const target = event.target;
		if (!(target instanceof Element)) return;
		const choice = target.closest<HTMLButtonElement>("[data-sita-topic]");
		if (!choice) return;
		const label = choice.textContent?.trim() || "";
		if (choice.dataset.sitaTopic === "contact") {
			showContactForm(label);
			return;
		}
		if (choice.dataset.sitaTopic === "booking") {
			startGuide();
			return;
		}
		void askSita(label, choice.dataset.sitaTopic);
	});

	chatMessages.addEventListener("click", (event) => {
		const target = event.target;
		if (!(target instanceof Element)) return;
		const retryButton = target.closest<HTMLButtonElement>("[data-sita-retry]");
		if (retryButton) {
			if (lastFailedQuestion) void askSita(lastFailedQuestion);
			return;
		}
		const feedbackButton = target.closest<HTMLButtonElement>(
			"[data-sita-feedback]",
		);
		if (feedbackButton) {
			const group = feedbackButton.parentElement;
			if (
				feedbackButton.disabled ||
				group?.dataset.sitaFeedbackVoted === "true"
			) {
				return;
			}
			if (group) group.dataset.sitaFeedbackVoted = "true";
			for (const button of group?.querySelectorAll<HTMLButtonElement>(
				"[data-sita-feedback]",
			) ?? []) {
				button.setAttribute("aria-pressed", String(button === feedbackButton));
				button.disabled = true;
			}
			const bubble = feedbackButton.closest<HTMLElement>(".sita-chat-message");
			window.dispatchEvent(
				new CustomEvent("sita:feedback", {
					detail: {
						helpful: feedbackButton.dataset.sitaFeedback === "true",
						turnId: bubble?.dataset.sitaTurnId,
						topic: bubble?.dataset.sitaTopic,
					},
				}),
			);
			return;
		}
		const clarifyButton = target.closest<HTMLButtonElement>(
			"[data-sita-clarify]",
		);
		if (clarifyButton) {
			const option = clarifyButton.dataset.sitaClarify || "";
			if (!option) return;
			if (/^(hubungi admin|contact the team)$/i.test(option)) {
				showContactForm(option);
				return;
			}
			void askSita(option);
			return;
		}
		const suggestion = target.closest<HTMLButtonElement>(
			"[data-sita-suggestion]",
		);
		if (!suggestion) return;
		const label = suggestion.textContent?.trim() || "";
		if (suggestion.dataset.sitaSuggestion === "contact") {
			showContactForm(label);
			return;
		}
		void askSita(label, suggestion.dataset.sitaTopic);
	});

	chatComposer.addEventListener("submit", (event) => {
		event.preventDefault();
		const question = chatInput.value.trim();
		if (!question) {
			addMessage(copy.sita, copy.empty);
			chatInput.focus();
			return;
		}
		chatInput.value = "";
		if (guideStep) {
			addMessage(copy.you, question);
			handleGuideAnswer(question);
			return;
		}
		if (isBookingIntent(question)) {
			startGuide();
			return;
		}
		void askSita(question);
	});

	async function submitSitaLead(
		name: string,
		phone: string,
		message: string,
		website: string,
	): Promise<void> {
		try {
			await fetch("/api/sita/leads", {
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify({
					name,
					phone,
					topic: activeTopic ?? "other",
					...(Object.keys(activeCriteria).length
						? { criteria: { ...activeCriteria } }
						: {}),
					score: leadScore(),
					message: message.slice(0, 1000),
					website,
				}),
			});
		} catch {
			return;
		}
	}

	chatLeadForm.addEventListener("submit", async (event) => {
		event.preventDefault();
		const formData = new FormData(chatLeadForm);
		const name = String(formData.get("name") || "").trim();
		const phone = String(formData.get("phone") || "").trim();
		const consent = formData.get("consent") === "on";
		const website = String(formData.get("website") || "").trim();
		const whatsappLink = chatWidget.dataset.whatsappLink;

		if (!name || !phone || !consent) {
			addMessage(copy.sita, copy.invalidContact);
			return;
		}
		if (!whatsappLink) {
			addMessage(copy.sita, copy.invalidLink);
			return;
		}

		const baseMessage =
			locale === "id"
				? `Halo admin Sagita, nama saya ${name}. Saya ingin bertanya: ${selectedQuestion}. Nomor WhatsApp saya ${phone}.`
				: `Hello Sagita team, my name is ${name}. I have a question: ${selectedQuestion}. My WhatsApp number is ${phone}.`;
		const summary = criteriaSummary();
		const message = summary.length
			? `${baseMessage}\n${summary.join("\n")}`
			: baseMessage;

		await submitSitaLead(name, phone, message, website);

		try {
			const destination = new URL(whatsappLink);
			destination.searchParams.set("text", message);
			addMessage(copy.sita, copy.handoff, [], destination.toString());
			hideLeadForm();
		} catch {
			addMessage(copy.sita, copy.invalidLink);
		}
	});

	chatLeadForm
		.querySelector<HTMLButtonElement>("[data-sita-skip]")
		?.addEventListener("click", () => {
			hideLeadForm();
			addMessage(copy.sita, copy.skipped);
			chatInput.focus();
		});

	for (const item of readHistory()) {
		addMessage(
			item.sender === "you" ? copy.you : copy.sita,
			item.text,
			[],
			undefined,
			[],
			false,
			undefined,
			false,
		);
	}
})();
