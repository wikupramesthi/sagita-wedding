const root = document.documentElement;
const widget = document.getElementById("accessibilityWidget");
const trigger = document.getElementById("accessibilityTrigger");
const panel = document.getElementById("accessibilityPanel");

if (widget && trigger && panel) {
	const storageKey = "sagita-accessibility";
	const sizeSteps = [100, 110, 120, 130] as const;
	type FontScale = (typeof sizeSteps)[number];
	type ToggleKey = "contrast" | "grayscale" | "underline" | "motion";
	type AccessibilitySettings = Record<ToggleKey, boolean> & {
		scale: FontScale;
	};

	const toggleKeys: ToggleKey[] = [
		"contrast",
		"grayscale",
		"underline",
		"motion",
	];
	const defaults: AccessibilitySettings = {
		scale: 100,
		contrast: false,
		grayscale: false,
		underline: false,
		motion: false,
	};
	const sizeValue = panel.querySelector<HTMLButtonElement>(
		"[data-a11y-size-value]",
	);
	const standardLabel = sizeValue?.dataset.standard || "Standard";
	const toggleButtons = [
		...panel.querySelectorAll<HTMLButtonElement>("[data-a11y-toggle]"),
	];
	const closeButton =
		panel.querySelector<HTMLButtonElement>("[data-a11y-close]");

	const isRecord = (value: unknown): value is Record<string, unknown> =>
		typeof value === "object" && value !== null && !Array.isArray(value);
	const isFontScale = (value: unknown): value is FontScale =>
		sizeSteps.some((scale) => scale === value);
	const getToggleKey = (value: string | undefined): ToggleKey | undefined =>
		toggleKeys.find((key) => key === value);

	const loadSettings = (): AccessibilitySettings => {
		try {
			const saved = window.localStorage.getItem(storageKey);
			if (!saved) return { ...defaults };

			const parsed: unknown = JSON.parse(saved);
			if (!isRecord(parsed)) {
				console.error("Invalid accessibility preferences; using defaults.");
				return { ...defaults };
			}

			return {
				scale: isFontScale(parsed.scale) ? parsed.scale : defaults.scale,
				contrast: parsed.contrast === true,
				grayscale: parsed.grayscale === true,
				underline: parsed.underline === true,
				motion: parsed.motion === true,
			};
		} catch (error) {
			console.error("Could not load accessibility preferences.", error);
			return { ...defaults };
		}
	};

	let state = loadSettings();

	const saveSettings = () => {
		try {
			window.localStorage.setItem(storageKey, JSON.stringify(state));
		} catch (error) {
			console.error("Could not save accessibility preferences.", error);
		}
	};

	const render = () => {
		root.style.setProperty("--a11y-root-font-size", `${state.scale}%`);
		for (const key of toggleKeys) {
			if (state[key]) root.setAttribute(`data-a11y-${key}`, "true");
			else root.removeAttribute(`data-a11y-${key}`);
		}
		if (sizeValue) {
			sizeValue.textContent =
				state.scale === 100 ? standardLabel : `${state.scale}%`;
		}
		for (const button of toggleButtons) {
			const key = getToggleKey(button.dataset.a11yToggle);
			if (key) button.setAttribute("aria-pressed", String(state[key]));
		}
	};

	const setOpen = (open: boolean) => {
		panel.hidden = !open;
		trigger.setAttribute("aria-expanded", String(open));
		if (open) closeButton?.focus();
	};

	trigger.addEventListener("click", () =>
		setOpen(panel.hasAttribute("hidden")),
	);
	closeButton?.addEventListener("click", () => {
		setOpen(false);
		trigger.focus();
	});

	panel.addEventListener("click", (event) => {
		const target = event.target;
		if (!(target instanceof Element)) return;

		const sizeButton = target.closest<HTMLButtonElement>("[data-a11y-size]");
		const sizeAction = sizeButton?.dataset.a11ySize;
		if (sizeAction === "reset") {
			state.scale = defaults.scale;
			render();
			saveSettings();
			return;
		}
		if (sizeAction === "up" || sizeAction === "down") {
			const currentIndex = sizeSteps.indexOf(state.scale);
			const step = sizeAction === "up" ? 1 : -1;
			state.scale =
				sizeSteps[
					Math.max(0, Math.min(sizeSteps.length - 1, currentIndex + step))
				];
			render();
			saveSettings();
			return;
		}

		const toggleButton =
			target.closest<HTMLButtonElement>("[data-a11y-toggle]");
		const key = getToggleKey(toggleButton?.dataset.a11yToggle);
		if (!key) return;

		state[key] = !state[key];
		render();
		saveSettings();
	});

	panel
		.querySelector<HTMLButtonElement>("[data-a11y-reset]")
		?.addEventListener("click", () => {
			state = { ...defaults };
			render();
			saveSettings();
		});

	document.addEventListener("keydown", (event) => {
		if (event.key === "Escape" && !panel.hidden) {
			setOpen(false);
			trigger.focus();
		}
	});

	document.addEventListener("click", (event) => {
		if (
			!panel.hidden &&
			event.target instanceof Node &&
			!widget.contains(event.target)
		) {
			setOpen(false);
		}
	});

	render();
}
