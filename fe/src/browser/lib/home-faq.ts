const homeFaqTabActive = [
	"bg-sand-900",
	"text-sand-50",
	"shadow-sm",
	"ring-1",
	"ring-sand-900",
];
const homeFaqTabIdle = [
	"bg-white",
	"text-sand-700",
	"ring-1",
	"ring-sand-200",
	"hover:bg-sand-100",
];

function setHomeFaqItemOpen(button: HTMLButtonElement, open: boolean): void {
	const panelId = button.getAttribute("aria-controls");
	const panel = panelId ? document.getElementById(panelId) : null;
	const item = button.closest<HTMLElement>("[data-home-faq-item]");
	const toggle = button.querySelector<HTMLElement>(".home-faq-toggle");

	button.setAttribute("aria-expanded", String(open));
	panel?.classList.toggle("hidden", !open);
	item?.classList.toggle("border-sage-300", open);
	item?.classList.toggle("shadow-md", open);
	if (toggle) toggle.textContent = open ? "−" : "+";
}

function initHomeFaq(): void {
	const section = document.getElementById("home-faq");
	if (!section) return;

	const tabs = Array.from(
		section.querySelectorAll<HTMLButtonElement>("[data-home-faq-tab]"),
	);
	const groups = Array.from(
		section.querySelectorAll<HTMLElement>("[data-home-faq-group]"),
	);
	const buttons = Array.from(
		section.querySelectorAll<HTMLButtonElement>("[data-home-faq-button]"),
	);
	const expandButton = section.querySelector<HTMLButtonElement>(
		"[data-home-faq-expand]",
	);
	const collapseButton = section.querySelector<HTMLButtonElement>(
		"[data-home-faq-collapse]",
	);

	const activateGroup = (groupId: string): void => {
		tabs.forEach((tab) => {
			const active = tab.dataset.homeFaqTab === groupId;
			tab.classList.remove(...homeFaqTabActive, ...homeFaqTabIdle);
			tab.classList.add(...(active ? homeFaqTabActive : homeFaqTabIdle));
			tab.setAttribute("aria-pressed", String(active));
		});
		groups.forEach((group) => {
			group.classList.toggle("hidden", group.dataset.homeFaqGroup !== groupId);
		});
	};

	const setAllItemsOpen = (open: boolean): void => {
		buttons.forEach((button) => {
			setHomeFaqItemOpen(button, open);
		});
	};

	tabs.forEach((tab) => {
		tab.addEventListener("click", () => {
			if (tab.dataset.homeFaqTab) activateGroup(tab.dataset.homeFaqTab);
		});
	});

	buttons.forEach((button) => {
		button.addEventListener("click", () => {
			setHomeFaqItemOpen(
				button,
				button.getAttribute("aria-expanded") !== "true",
			);
		});
	});

	expandButton?.addEventListener("click", () => setAllItemsOpen(true));
	collapseButton?.addEventListener("click", () => setAllItemsOpen(false));

	const firstTabId = tabs[0]?.dataset.homeFaqTab;
	if (firstTabId) activateGroup(firstTabId);
	const firstButton = buttons[0];
	if (firstButton) setHomeFaqItemOpen(firstButton, true);
}

initHomeFaq();
