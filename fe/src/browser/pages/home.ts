import { $, escapeHtml, lockScroll, onAction, unlockScroll } from "../lib/dom";
import type { TestimonialItem } from "../../types/index";

interface EventData {
	judul?: string;
	tanggal?: string;
	waktu_mulai?: string;
	waktu_selesai?: string;
	lokasi?: string;
	deskripsi?: string;
}

const eventModal = $("eventModal");
let eventsData: EventData[] = [];
try {
	const parsed = JSON.parse($("eventsData")?.textContent || "[]");
	if (Array.isArray(parsed)) eventsData = parsed;
} catch {
	eventsData = [];
}

const META_ICONS: Record<string, string> = {
	date: '<path d="M8 3v4m8-4v4M4 9h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"></path>',
	time: '<circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path>',
	place:
		'<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a 1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle>',
};

function metaItem(icon: string, text: string): string {
	return (
		'<span class="flex items-center gap-1.5">' +
		'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
		icon +
		"</svg>" +
		escapeHtml(text) +
		"</span>"
	);
}

function openEvent(i: number) {
	const e = eventsData[i];
	const title = $("eventTitle");
	const meta = $("eventMeta");
	const desc = $("eventDesc");
	if (!e || !eventModal || !title || !meta || !desc) return;
	title.textContent = e.judul || "";

	let html = "";
	if (e.tanggal) html += metaItem(META_ICONS.date, e.tanggal);
	if (e.waktu_mulai) {
		const range =
			String(e.waktu_mulai).substring(0, 5) +
			(e.waktu_selesai ? ` - ${String(e.waktu_selesai).substring(0, 5)}` : "");
		html += metaItem(META_ICONS.time, range);
	}
	if (e.lokasi) html += metaItem(META_ICONS.place, e.lokasi);
	meta.innerHTML = html;

	// Deskripsi dari CMS backend (disanitasi server-side di eventService).
	desc.innerHTML = e.deskripsi || "";

	const wa = $("eventWhatsApp") as HTMLAnchorElement | null;
	// No hardcoded WA number — base wajib dari server via data-wa-base (contactLinks).
	if (e.judul && wa?.dataset.waBase) {
		const url = new URL(wa.dataset.waBase);
		url.searchParams.set(
			"text",
			`Halo Sagita Wedding Planner 👋\n\nSaya tertarik untuk konsultasi mengenai acara: ${e.judul}.\n\nApakah saya bisa mendapatkan informasi paket dan estimasi biayanya?\n\nTerima kasih 😊`,
		);
		wa.href = url.toString();
	}
	eventModal.classList.remove("hidden");
	lockScroll();
}

function closeEvent() {
	eventModal?.classList.add("hidden");
	unlockScroll();
}

onAction({
	"open-event": (el) => openEvent(parseInt(el.dataset.eventIndex || "", 10)),
	"close-event": closeEvent,
});

document.addEventListener("keydown", (ev) => {
	if (ev.key === "Escape") closeEvent();
});

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
			currentCountElement.textContent = String(activeIndex + 1).padStart(2, "0");

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
