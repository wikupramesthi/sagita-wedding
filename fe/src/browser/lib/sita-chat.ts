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
	}

	interface ChatResult {
		success: boolean;
		answer: string;
		cards: ChatCard[];
		offerContact: boolean;
		unanswered?: boolean;
		suggestions?: ChatSuggestion[];
	}

	function isChatSuggestion(value: unknown): value is ChatSuggestion {
		if (!value || typeof value !== "object" || Array.isArray(value)) {
			return false;
		}
		const suggestion = value as Record<string, unknown>;
		return (
			typeof suggestion.label === "string" &&
			(suggestion.action === undefined || suggestion.action === "contact")
		);
	}

	const widget = document.getElementById("sitaChatWidget");
	const toggle = document.getElementById("sitaChatToggle");
	const panel = document.getElementById("sitaChatPanel");
	const closeButton = document.getElementById("sitaChatClose");
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
			skipped: "Baik, kamu bisa melanjutkan pertanyaan kapan saja.",
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
			skipped: "No problem. You can continue asking questions anytime.",
		},
	}[locale];

	let returnFocus: HTMLElement = chatToggle;
	let selectedQuestion = "";
	let activeTopic: ChatTopic | undefined;
	let activeCriteria: ChatCriteria = {};
	let isSending = false;
	const minimumTypingDuration = 1200;

	function topicFromQuestion(question: string): ChatTopic | undefined {
		if (/\b(paket|packages?|harga paket|package)\b/i.test(question)) {
			return "packages";
		}
		if (/\b(venue|gedung|lokasi|tempat)\b/i.test(question)) return "venues";
		if (
			/\b(vendors?|catering|katering|dekor|fotografi|fotografer|makeup|rias|mua)\b/i.test(
				question,
			)
		) {
			return "vendors";
		}
		if (
			/\b(harga|biaya|tarif|budget|anggaran|maksimal|max|dibawah|bawah|murah|termurah|mahal|price|pricing)\b/i.test(
				question,
			)
		) {
			return "packages";
		}
		return undefined;
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
			/\b(ini|itu|tersebut|lebih|murah|mahal|termurah|termahal|besok|hari|layanan|wo|organizer|booking|paket|vendor|admin|harga|biaya|konsultasi|cara|halo|hai|makasih|terima|kasih|iya|oke|cocok|rekomendasi)\b/i.test(
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
	): HTMLElement {
		const bubble = document.createElement("article");
		bubble.className = "sita-chat-message";
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
								const topic =
									activeTopic ||
									(/\b(wo|wedding organizer|layanan)\b/i.test(selectedQuestion)
										? "services"
										: undefined);
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
	}

	function showContactForm(label: string) {
		addMessage(copy.you, label);
		if (!selectedQuestion) selectedQuestion = label;
		addMessage(copy.sita, copy.contact);
		chatLeadForm.classList.remove("hidden");
		chatLeadForm.querySelector<HTMLInputElement>("input[name='name']")?.focus();
	}

	async function askSita(question: string, topicHint?: string) {
		if (isSending) return;
		isSending = true;
		const requestStartedAt = Date.now();
		const hintedTopic: ChatTopic | undefined =
			topicHint === "packages" ||
			topicHint === "venues" ||
			topicHint === "vendors"
				? topicHint
				: undefined;
		const explicitTopic = topicFromQuestion(question) || hintedTopic;
		const isFollowUp = isContextualFollowUp(question);
		if (explicitTopic && explicitTopic !== activeTopic) activeCriteria = {};
		if (explicitTopic) activeTopic = explicitTopic;
		else if (!isFollowUp) {
			activeTopic = undefined;
			activeCriteria = {};
		}
		const contextTopic =
			explicitTopic || (isFollowUp ? activeTopic : undefined);
		if (contextTopic === "packages") {
			activeCriteria.budgetLimit =
				parseBudgetLimit(question) ?? activeCriteria.budgetLimit;
			activeCriteria.guestCount =
				parseGuestCount(question) ?? activeCriteria.guestCount;
		} else if (contextTopic === "venues") {
			activeCriteria.city = parseCity(question) ?? activeCriteria.city;
			activeCriteria.eventDate =
				parseEventDate(question) ?? activeCriteria.eventDate;
			activeCriteria.guestCount =
				parseGuestCount(question) ?? activeCriteria.guestCount;
		} else if (contextTopic === "vendors") {
			activeCriteria.vendorCategory =
				parseVendorCategory(question) ?? activeCriteria.vendorCategory;
		}
		hideLeadForm();
		selectedQuestion = question;
		addMessage(copy.you, question);
		const loadingMessage = addMessage(
			copy.sita,
			copy.loading,
			[],
			undefined,
			[],
			true,
		);
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
				(data.suggestions !== undefined &&
					(!Array.isArray(data.suggestions) ||
						!data.suggestions.every(isChatSuggestion)))
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
			if (data.unanswered) {
				window.dispatchEvent(new Event("sita:no-match"));
			}
			const suggestions = (data.suggestions || []).filter(
				(suggestion) =>
					!visibleChoiceLabels.has(suggestion.label.trim().toLocaleLowerCase()),
			);
			addMessage(copy.sita, data.answer, data.cards, undefined, suggestions);
		} catch {
			loadingMessage.remove();
			hideLeadForm();
			chatChoices.classList.remove("hidden");
			addMessage(copy.sita, copy.unavailable);
		} finally {
			if (sendButton) sendButton.disabled = false;
			chatInput.disabled = false;
			for (const button of choiceButtons) button.disabled = false;
			isSending = false;
		}
	}

	chatToggle.addEventListener("click", () => {
		setOpen(chatPanel.classList.contains("hidden"));
	});
	chatCloseButton.addEventListener("click", () => setOpen(false));
	document.addEventListener("keydown", (event) => {
		if (event.key === "Escape" && !chatPanel.classList.contains("hidden")) {
			setOpen(false);
		}
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
		void askSita(label, choice.dataset.sitaTopic);
	});

	chatMessages.addEventListener("click", (event) => {
		const target = event.target;
		if (!(target instanceof Element)) return;
		const suggestion = target.closest<HTMLButtonElement>(
			"[data-sita-suggestion]",
		);
		if (!suggestion) return;
		const label = suggestion.textContent?.trim() || "";
		if (suggestion.dataset.sitaSuggestion === "contact") {
			showContactForm(label);
			return;
		}
		void askSita(label);
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
		void askSita(question);
	});

	chatLeadForm.addEventListener("submit", (event) => {
		event.preventDefault();
		const formData = new FormData(chatLeadForm);
		const name = String(formData.get("name") || "").trim();
		const phone = String(formData.get("phone") || "").trim();
		const consent = formData.get("consent") === "on";
		const whatsappLink = chatWidget.dataset.whatsappLink;

		if (!name || !phone || !consent) {
			addMessage(copy.sita, copy.invalidContact);
			return;
		}
		if (!whatsappLink) {
			addMessage(copy.sita, copy.invalidLink);
			return;
		}

		const message =
			locale === "id"
				? `Halo admin Sagita, nama saya ${name}. Saya ingin bertanya: ${selectedQuestion}. Nomor WhatsApp saya ${phone}.`
				: `Hello Sagita team, my name is ${name}. I have a question: ${selectedQuestion}. My WhatsApp number is ${phone}.`;

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
})();

export {};
