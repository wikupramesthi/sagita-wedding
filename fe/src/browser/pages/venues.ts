export {};

const searchInput = document.querySelector<HTMLInputElement>("#venueSearch");
const clearButton =
	document.querySelector<HTMLButtonElement>("#venueSearchClear");
const resultCount = document.querySelector<HTMLElement>("#venueResultCount");
const countLabel = document.querySelector<HTMLElement>("#venueCountLabel");
const emptyState = document.querySelector<HTMLElement>("#venueNoResults");
const venues = Array.from(
	document.querySelectorAll<HTMLElement>("[data-venue-item]"),
);

function normalize(value: string): string {
	return value
		.normalize("NFD")
		.replace(/[\u0300-\u036f]/g, "")
		.toLocaleLowerCase()
		.trim();
}

function filterVenues(): void {
	const query = normalize(searchInput?.value || "");
	let visibleCount = 0;

	for (const venue of venues) {
		const searchableText = normalize(venue.dataset.search || "");
		const matches = !query || searchableText.includes(query);
		venue.hidden = !matches;
		if (matches) visibleCount += 1;
	}

	if (resultCount) resultCount.textContent = String(visibleCount);
	if (countLabel) {
		countLabel.textContent =
			visibleCount === 1
				? countLabel.dataset.singular || ""
				: countLabel.dataset.plural || "";
	}
	if (emptyState) emptyState.hidden = visibleCount > 0;
	if (clearButton) clearButton.hidden = !query;
}

searchInput?.addEventListener("input", filterVenues);
clearButton?.addEventListener("click", () => {
	if (!searchInput) return;
	searchInput.value = "";
	filterVenues();
	searchInput.focus();
});
