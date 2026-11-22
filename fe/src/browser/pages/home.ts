import type { TestimonialItem } from "../../types/index";
import { $ } from "../lib/dom";
import "../lib/home-faq";

const testimonialContainer = document.querySelector<HTMLElement>(
	"[data-home-testimonial-panels]",
);
const testimonialDataElement = document.getElementById("homeTestimonialsData");
if (testimonialContainer && testimonialDataElement) {
	function isHomeTestimonial(item: unknown): item is TestimonialItem {
		return (
			typeof item === "object" &&
			item !== null &&
			"isi_testimoni" in item &&
			typeof item.isi_testimoni === "string"
		);
	}

	const testimonialPanels = Array.from(
		testimonialContainer.querySelectorAll<HTMLButtonElement>(
			"[data-home-testimonial-index]",
		),
	);
	const testimonialImages =
		testimonialContainer.querySelectorAll<HTMLImageElement>(
			".home-testimonial-panel__image",
		);
	testimonialImages.forEach((image) => {
		const hideUnavailableImage = (): void => {
			image.hidden = true;
		};
		image.addEventListener("error", hideUnavailableImage, { once: true });
		if (image.complete && image.naturalWidth === 0) hideUnavailableImage();
	});

	let testimonials: TestimonialItem[] = [];

	try {
		const parsed: unknown = JSON.parse(
			testimonialDataElement.textContent ?? "[]",
		);
		if (Array.isArray(parsed)) {
			const validItems = parsed.filter(isHomeTestimonial);
			if (
				validItems.length === testimonialPanels.length &&
				validItems.length > 0
			) {
				testimonials = validItems;
			} else {
				console.error("Home testimonials contain invalid data.");
			}
		} else {
			console.error("Home testimonials contain invalid data.");
		}
	} catch (error) {
		console.error("Home testimonials could not be read.", error);
	}

	const quote = document.querySelector<HTMLElement>(
		"[data-home-testimonial-quote]",
	);
	const currentCount = document.querySelector<HTMLElement>(
		"[data-home-testimonial-current]",
	);
	const previousButton =
		testimonialContainer.parentElement?.querySelector<HTMLButtonElement>(
			'[data-home-testimonial-action="previous"]',
		);
	const nextButton =
		testimonialContainer.parentElement?.querySelector<HTMLButtonElement>(
			'[data-home-testimonial-action="next"]',
		);

	if (
		testimonials.length > 0 &&
		quote &&
		currentCount &&
		previousButton &&
		nextButton
	) {
		const quoteElement = quote;
		const currentCountElement = currentCount;
		let activeIndex = 0;
		const prefersReducedMotion = window.matchMedia(
			"(prefers-reduced-motion: reduce)",
		);

		function setActiveTestimonial(index: number): void {
			activeIndex = (index + testimonials.length) % testimonials.length;
			const testimonial = testimonials[activeIndex];
			const activePanel = testimonialPanels[activeIndex];

			testimonialPanels.forEach((panel, panelIndex) => {
				const isActive = panelIndex === activeIndex;
				panel.classList.toggle("is-active", isActive);
				panel.setAttribute("aria-pressed", String(isActive));
			});

			quoteElement.textContent = testimonial.isi_testimoni ?? "";
			currentCountElement.textContent = String(activeIndex + 1).padStart(
				2,
				"0",
			);

			if (window.matchMedia("(max-width: 639px)").matches) {
				activePanel.scrollIntoView({
					block: "nearest",
					inline: "nearest",
					behavior: prefersReducedMotion.matches ? "auto" : "smooth",
				});
			}
		}

		testimonialPanels.forEach((panel, index) => {
			panel.addEventListener("click", () => setActiveTestimonial(index));
		});
		previousButton.addEventListener("click", () =>
			setActiveTestimonial(activeIndex - 1),
		);
		nextButton.addEventListener("click", () =>
			setActiveTestimonial(activeIndex + 1),
		);
	}
}

const homeSlider = document.querySelector<HTMLElement>("[data-home-slider]");
if (homeSlider) {
	let slideUrls: string[] = [];
	try {
		const parsed: unknown = JSON.parse(homeSlider.dataset.homeSlides ?? "[]");
		if (
			Array.isArray(parsed) &&
			parsed.every((url) => typeof url === "string")
		) {
			slideUrls = parsed;
		} else {
			console.error("Home hero slides contain invalid image URLs.");
		}
	} catch (error) {
		console.error("Home hero slides could not be read.", error);
	}
	const slideImages = Array.from(
		homeSlider.querySelectorAll<HTMLImageElement>("[data-home-slide-image]"),
	);
	const previousButton = homeSlider.querySelector<HTMLButtonElement>(
		'[data-home-slide-action="previous"]',
	);
	const nextButton = homeSlider.querySelector<HTMLButtonElement>(
		'[data-home-slide-action="next"]',
	);
	const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

	if (
		slideUrls.length > 1 &&
		slideImages.length > 1 &&
		previousButton &&
		nextButton
	) {
		let currentIndex = 0;
		let activeImage = slideImages[0];
		let timer = 0;
		let pointerInside = false;
		let focusInside = false;
		let loadRequest = 0;
		const failedSlides = new Set<number>();

		slideImages.forEach((image) => {
			image.addEventListener("error", () => {
				const index = Number(image.dataset.homeSlideIndex);
				if (Number.isInteger(index) && index >= 0 && index < slideUrls.length) {
					failedSlides.add(index);
					console.error(`Home hero slide ${index + 1} could not be loaded.`);
				}
				image.classList.add("hidden");
				scheduleNext();
			});
		});

		function canAutoPlay(): boolean {
			return (
				!pointerInside &&
				!focusInside &&
				!document.hidden &&
				!reducedMotion.matches &&
				failedSlides.size < slideUrls.length
			);
		}

		function scheduleNext(): void {
			window.clearTimeout(timer);
			if (!canAutoPlay()) return;

			timer = window.setTimeout(() => {
				const nextIndex = findAvailableSlide(1);
				if (nextIndex !== undefined) showSlide(nextIndex);
			}, 6500);
		}

		function findAvailableSlide(direction: 1 | -1): number | undefined {
			for (let step = 1; step < slideUrls.length; step += 1) {
				const index =
					(currentIndex + direction * step + slideUrls.length) %
					slideUrls.length;
				if (!failedSlides.has(index)) return index;
			}
			return undefined;
		}

		function showSlide(index: number): void {
			const request = ++loadRequest;
			const imageUrl = slideUrls[index];
			if (!imageUrl || failedSlides.has(index)) return;
			if (index === currentIndex) {
				scheduleNext();
				return;
			}

			const image = new Image();
			image.onload = () => {
				if (request !== loadRequest) return;
				const nextImage = slideImages.find((item) => item !== activeImage);
				if (!nextImage) return;
				nextImage.src = imageUrl;
				nextImage.dataset.homeSlideIndex = String(index);
				nextImage.classList.remove("hidden");
				nextImage.classList.replace("opacity-0", "opacity-100");
				activeImage.classList.replace("opacity-100", "opacity-0");
				activeImage = nextImage;
				currentIndex = index;
				scheduleNext();
			};
			image.onerror = () => {
				if (request !== loadRequest) return;
				failedSlides.add(index);
				console.error(`Home hero slide ${index + 1} could not be loaded.`);
				scheduleNext();
			};
			image.src = imageUrl;
		}

		previousButton.addEventListener("click", () => {
			const previousIndex = findAvailableSlide(-1);
			if (previousIndex !== undefined) showSlide(previousIndex);
		});
		nextButton.addEventListener("click", () => {
			const nextIndex = findAvailableSlide(1);
			if (nextIndex !== undefined) showSlide(nextIndex);
		});
		homeSlider.addEventListener("mouseenter", () => {
			pointerInside = true;
			scheduleNext();
		});
		homeSlider.addEventListener("mouseleave", () => {
			pointerInside = false;
			scheduleNext();
		});
		homeSlider.addEventListener("focusin", () => {
			focusInside = true;
			scheduleNext();
		});
		homeSlider.addEventListener("focusout", (event) => {
			if (
				!(event.relatedTarget instanceof Node) ||
				!homeSlider.contains(event.relatedTarget)
			) {
				focusInside = false;
				scheduleNext();
			}
		});
		document.addEventListener("visibilitychange", scheduleNext);
		reducedMotion.addEventListener("change", scheduleNext);
		scheduleNext();
	}
}
