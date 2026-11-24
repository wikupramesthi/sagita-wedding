export {};

const filters = Array.from(
	document.querySelectorAll<HTMLButtonElement>(".vendor-filter"),
);
const cards = Array.from(
	document.querySelectorAll<HTMLElement>("[data-vendor-card]"),
);
const grid = document.getElementById("vendorGrid");
const emptyState = document.getElementById("vendorEmpty");
const resultCount = document.getElementById("vendorResultCount");
const resetButton = document.querySelector<HTMLButtonElement>(
	"[data-vendor-reset]",
);

const cardCategories = new Map(
	cards.map((card) => {
		const parsed: unknown = JSON.parse(card.dataset.categories || "[]");
		const categories = Array.isArray(parsed)
			? parsed.filter(
					(category): category is string => typeof category === "string",
				)
			: [];
		return [card, new Set(categories)];
	}),
);

function setCategory(category: string): void {
	let visibleCount = 0;

	filters.forEach((filter) => {
		const isActive = (filter.dataset.category || "") === category;
		filter.classList.toggle("shadow-sm", isActive);
		filter.classList.toggle("bg-white", !isActive);
		filter.classList.toggle("bg-sage-800", isActive);
		filter.classList.toggle("text-white", isActive);
		filter.classList.toggle("text-sage-800", !isActive);
		filter.classList.toggle("ring-sage-800", isActive);
		filter.classList.toggle("ring-sand-200", !isActive);
		filter.classList.toggle("hover:bg-sand-100", !isActive);
		filter.style.backgroundColor = "";
		filter.style.color = "";
		filter.style.borderColor = "";
		filter.setAttribute("aria-pressed", String(isActive));

		const count = filter.querySelector("span");
		count?.classList.toggle("bg-sand-100", !isActive);
		count?.classList.toggle("bg-white/20", isActive);
		if (count) {
			count.style.backgroundColor = "";
			count.style.color = "";
			count.classList.toggle("text-white", isActive);
			count.classList.toggle("text-sage-800", !isActive);
		}
	});

	cards.forEach((card) => {
		const matches =
			!category || (cardCategories.get(card)?.has(category) ?? false);
		card.classList.toggle("hidden", !matches);
		if (matches) visibleCount += 1;
	});

	if (resultCount) resultCount.textContent = String(visibleCount);
	grid?.classList.toggle("hidden", visibleCount === 0);
	emptyState?.classList.toggle("hidden", visibleCount !== 0);
}

filters.forEach((filter) => {
	filter.addEventListener("click", () => {
		setCategory(filter.dataset.category || "");
	});
});

resetButton?.addEventListener("click", () => setCategory(""));
