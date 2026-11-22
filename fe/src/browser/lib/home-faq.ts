function setHomeFaqItemOpen(button: HTMLButtonElement, open: boolean): void {
	const panelId = button.getAttribute("aria-controls");
	const panel = panelId ? document.getElementById(panelId) : null;
	const toggle = button.querySelector<HTMLElement>(".home-faq-toggle");

	button.setAttribute("aria-expanded", String(open));
	panel?.classList.toggle("hidden", !open);
	if (toggle) toggle.textContent = open ? "×" : "+";
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

	const activateGroup = (groupId: string, scrollToGroup = false): void => {
		tabs.forEach((tab) => {
			const active = tab.dataset.homeFaqTab === groupId;
			tab.setAttribute("aria-pressed", String(active));
		});
		const group = groups.find((item) => item.dataset.homeFaqGroup === groupId);
		if (scrollToGroup && group) {
			group.scrollIntoView({
				behavior: window.matchMedia("(prefers-reduced-motion: reduce)").matches
					? "auto"
					: "smooth",
				block: "start",
			});
		}
	};

	const setAllItemsOpen = (open: boolean): void => {
		buttons.forEach((button) => {
			setHomeFaqItemOpen(button, open);
		});
	};

	tabs.forEach((tab) => {
		tab.addEventListener("click", () => {
			if (tab.dataset.homeFaqTab) {
				activateGroup(tab.dataset.homeFaqTab, true);
			}
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
