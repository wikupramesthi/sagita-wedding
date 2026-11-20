const searchInput = document.querySelector<HTMLInputElement>("#faqSearch");
const resetButton = document.querySelector<HTMLButtonElement>("#faqReset");
const resultCount = document.querySelector<HTMLElement>("#faqResultCount");
const emptyState = document.querySelector<HTMLElement>("#faqEmpty");

const filters = Array.from(
	document.querySelectorAll<HTMLButtonElement>(".faq-filter"),
);
const items = Array.from(
	document.querySelectorAll<HTMLElement>("[data-faq-item]"),
);
const groups = Array.from(
	document.querySelectorAll<HTMLElement>("[data-faq-group]"),
);

const activeChipClasses =
	"bg-sand-900 text-sand-50 shadow-sm ring-1 ring-sand-900";
const idleChipClasses =
	"bg-white text-sand-700 ring-1 ring-sand-200 hover:bg-sand-100";
const activeCountClasses = "bg-white/15 text-sand-50";
const idleCountClasses = "bg-sand-100 text-sand-700";
const openCardClasses = "border-sage-300 shadow-md";
const idleCardClasses = "border-sand-200 shadow-sm";
const openToggleClasses = "bg-sage-700 text-white";
const idleToggleClasses = "bg-sand-100 text-sage-700";

const countLabel =
	resultCount?.textContent?.replace(/^\d+\s*/, "").trim() || "questions";
let activeCategory = "";

function normalize(value: string): string {
	return value
		.normalize("NFD")
		.replace(/[\u0300-\u036f]/g, "")
		.toLowerCase()
		.trim();
}

function toggleClasses(
	element: HTMLElement,
	classes: string,
	enabled: boolean,
): void {
	const tokens = classes.split(" ");
	if (enabled) element.classList.add(...tokens);
	else element.classList.remove(...tokens);
}

function closeItem(item: HTMLElement): void {
	const button = item.querySelector<HTMLButtonElement>("[data-faq-button]");
	const panel = item.querySelector<HTMLElement>("[data-faq-panel]");
	const toggle = item.querySelector<HTMLElement>(".faq-toggle");

	panel?.classList.add("hidden");
	button?.setAttribute("aria-expanded", "false");
	toggleClasses(item, openCardClasses, false);
	toggleClasses(item, idleCardClasses, true);
	if (toggle) {
		toggle.textContent = "+";
		toggleClasses(toggle, openToggleClasses, false);
		toggleClasses(toggle, idleToggleClasses, true);
	}
}

function openItem(item: HTMLElement): void {
	const button = item.querySelector<HTMLButtonElement>("[data-faq-button]");
	const panel = item.querySelector<HTMLElement>("[data-faq-panel]");
	const toggle = item.querySelector<HTMLElement>(".faq-toggle");

	panel?.classList.remove("hidden");
	button?.setAttribute("aria-expanded", "true");
	toggleClasses(item, idleCardClasses, false);
	toggleClasses(item, openCardClasses, true);
	if (toggle) {
		toggle.textContent = "−";
		toggleClasses(toggle, idleToggleClasses, false);
		toggleClasses(toggle, openToggleClasses, true);
	}
}

function applyFilters(): void {
	const query = normalize(searchInput?.value || "");
	let visible = 0;

	items.forEach((item) => {
		const category = item.dataset.category || "";
		const haystack = normalize(item.dataset.search || "");
		const matches =
			(!activeCategory || category === activeCategory) &&
			(!query || haystack.includes(query));

		item.classList.toggle("hidden", !matches);
		if (matches) visible += 1;
		else closeItem(item);
	});

	groups.forEach((group) => {
		const hasVisibleItems = Array.from(
			group.querySelectorAll<HTMLElement>("[data-faq-item]"),
		).some((item) => !item.classList.contains("hidden"));
		group.classList.toggle("hidden", !hasVisibleItems);
	});

	emptyState?.classList.toggle("hidden", visible !== 0);
	if (resultCount) resultCount.textContent = `${visible} ${countLabel}`;
}

function setCategory(category: string): void {
	activeCategory = category;

	filters.forEach((filter) => {
		const isActive = (filter.dataset.category || "") === category;
		const count = filter.querySelector<HTMLElement>("span");

		toggleClasses(filter, activeChipClasses, isActive);
		toggleClasses(filter, idleChipClasses, !isActive);
		if (count) {
			toggleClasses(count, activeCountClasses, isActive);
			toggleClasses(count, idleCountClasses, !isActive);
		}
		filter.setAttribute("aria-pressed", String(isActive));
	});

	applyFilters();
}

items.forEach((item) => {
	item
		.querySelector<HTMLButtonElement>("[data-faq-button]")
		?.addEventListener("click", () => {
			const panel = item.querySelector<HTMLElement>("[data-faq-panel]");
			const willOpen = panel?.classList.contains("hidden") ?? false;

			items.forEach(closeItem);
			if (willOpen) openItem(item);
		});
});

filters.forEach((filter) => {
	filter.addEventListener("click", () => {
		setCategory(filter.dataset.category || "");
	});
});

searchInput?.addEventListener("input", applyFilters);
resetButton?.addEventListener("click", () => {
	if (searchInput) searchInput.value = "";
	setCategory("");
	searchInput?.focus();
});

const firstVisible = items.find((item) => !item.classList.contains("hidden"));
if (firstVisible) openItem(firstVisible);
