/**
 * Sagita Wedding theme interactions (dimuat di semua halaman).
 * Kode khusus halaman ada di src/browser/pages/<nama>.ts.
 *
 *   1. Navbar (mobile drawer, efek scroll, dropdown bahasa)
 *   2. Modal booking (data-action="open-booking" / "close-booking" + Escape)
 *   3. Reveal on scroll
 *   4. Fallback gambar (data-fallback, data-remove-on-error)
 *
 */

import { $, lockScroll, onAction, unlockScroll } from "./lib/dom";

/* ---------- 1. navbar ---------- */

(() => {
	$("mobileBtn")?.addEventListener("click", () =>
		$("mobileNav")?.classList.toggle("hidden"),
	);

	const navbar = $("navbar");
	if (navbar) {
		const pill = navbar.querySelector("nav");
		const alwaysSolid = navbar.dataset.static === "true";
		let scrollFrame = 0;
		const update = () => {
			const progress = alwaysSolid
				? 1
				: Math.min(Math.max(window.scrollY / 120, 0), 1);
			const scrolled = progress > 0.34;
			navbar.classList.toggle("scrolled", scrolled);
			pill?.style.setProperty("--nav-surface-opacity", `${progress * 0.94}`);
			pill?.style.setProperty("--nav-border-opacity", `${progress * 0.28}`);
			pill?.style.setProperty("--nav-shadow-opacity", `${progress * 0.08}`);
			pill?.style.setProperty("--nav-blur", `${progress * 16}px`);
			pill?.style.setProperty("--nav-lift", `${progress * -1.5}px`);
			pill?.style.setProperty("--nav-logo-scale", `${1 - progress * 0.02}`);
			pill?.style.setProperty(
				"--nav-padding-y",
				`${(window.innerWidth <= 640 ? 8.8 : 12) - progress * 2}px`,
			);
			if (!alwaysSolid) {
				navbar.firstElementChild instanceof HTMLElement &&
					navbar.firstElementChild.style.setProperty(
						"--nav-top-padding",
						`${(window.innerWidth <= 640 ? 10.4 : 16) - progress * 5}px`,
					);
			}
		};
		const scheduleUpdate = () => {
			if (scrollFrame) return;
			scrollFrame = window.requestAnimationFrame(() => {
				scrollFrame = 0;
				update();
			});
		};
		window.addEventListener("scroll", scheduleUpdate, { passive: true });
		window.addEventListener("resize", scheduleUpdate, { passive: true });
		update();
	}

	const toggle = $("langToggle");
	const menu = $("langDropdown");
	if (toggle && menu) {
		toggle.addEventListener("click", (e) => {
			e.stopPropagation();
			menu.classList.toggle("hidden");
		});
		document.addEventListener("click", (e) => {
			if (
				!menu.classList.contains("hidden") &&
				!toggle.contains(e.target as Node)
			) {
				menu.classList.add("hidden");
			}
		});
	}

	// Dropdown user (hanya ada saat sudah login)
	const userToggle = $("userMenuBtn");
	const userMenu = $("userMenu");
	if (userToggle && userMenu) {
		userToggle.addEventListener("click", (e) => {
			e.stopPropagation();
			const open = userMenu.classList.toggle("hidden") === false;
			userToggle.setAttribute("aria-expanded", open ? "true" : "false");
		});
		document.addEventListener("click", (e) => {
			if (
				!userMenu.classList.contains("hidden") &&
				!userMenu.contains(e.target as Node)
			) {
				userMenu.classList.add("hidden");
				userToggle.setAttribute("aria-expanded", "false");
			}
		});
		document.addEventListener("keydown", (e) => {
			if (e.key === "Escape") {
				userMenu.classList.add("hidden");
				userToggle.setAttribute("aria-expanded", "false");
			}
		});
	}
})();

/* ---------- 2. modal booking ---------- */

function openBooking() {
	const m = $("bookingModal");
	if (!m) return;
	m.classList.remove("hidden");
	lockScroll();
}

function closeBooking() {
	$("bookingModal")?.classList.add("hidden");
	unlockScroll();
}

onAction({
	"open-booking": openBooking,
	"close-booking": closeBooking,
	logout: () => {
		void logout();
	},
});

/** Panggil backend (memutus session Laravel), lalu render ulang state anonim. */
async function logout() {
	try {
		const res = await fetch("/api/logout", {
			method: "POST",
			headers: { Accept: "application/json" },
		});
		if (!res.ok) throw new Error("logout failed");
	} catch {
		// Backend tidak terjangkau — jangan reload, user tetap login.
		const msg = document.getElementById("bookingMsg");
		if (msg) {
			msg.className = "text-sm mt-3 p-3 rounded-xl bg-red-50 text-red-700";
			msg.textContent =
				msg.dataset.signoutError || "Could not sign out. Please try again.";
		}
		return;
	}
	window.location.reload();
}

document.addEventListener("keydown", (ev) => {
	if (ev.key === "Escape") closeBooking();
});

/* ---------- 3. reveal on scroll ---------- */

(() => {
	const els = document.querySelectorAll(
		".reveal, .card-earthy, .article-card, .faq-item, main article.group",
	);
	if (!("IntersectionObserver" in window) || !els.length) {
		els.forEach((el) => {
			el.classList.add("reveal-show");
		});
		return;
	}
	const io = new IntersectionObserver(
		(entries) => {
			entries.forEach((ent) => {
				if (!ent.isIntersecting) return;
				const target = ent.target as HTMLElement;
				target.classList.remove("reveal-hide");
				target.classList.add("reveal-show");
				const delay = parseInt(target.dataset.delay || "0", 10);
				setTimeout(() => {
					target.style.transitionDelay = "";
				}, 850 + delay);
				io.unobserve(ent.target);
			});
		},
		{ threshold: 0.12, rootMargin: "0px 0px -8% 0px" },
	);
	els.forEach((el) => {
		const target = el as HTMLElement;
		target.classList.add("reveal-hide");
		if (target.dataset.delay)
			target.style.transitionDelay = `${target.dataset.delay}ms`;
		io.observe(el);
	});
})();

/* ---------- 4. fallback gambar ---------- */

function handleImageError(img: HTMLImageElement) {
	const fb = img.dataset.fallback;
	if (fb) {
		if (img.getAttribute("src") !== fb && !img.dataset.fbk) {
			img.dataset.fbk = "1";
			img.src = fb;
		}
		return;
	}
	if (img.dataset.removeOnError === undefined) return;

	const unclass = img.dataset.errorRemoveClass;
	if (unclass) {
		img
			.closest(img.dataset.errorClassTarget || "body")
			?.classList.remove(unclass);
	}
	const selector = img.dataset.removeOnError;
	(selector ? img.closest(selector) : img)?.remove();
}

function handleImageErrorAttr(img: HTMLImageElement) {
	const action = img.dataset.imgError;
	if (!action) return;
	if (action === "remove") {
		img.remove();
	} else if (action === "closest-figure") {
		img.closest("figure")?.remove();
	} else if (action === "parent-remove") {
		img.closest(".grid")?.classList.remove("lg:grid-cols-2");
		img.parentElement?.remove();
	}
}

document.addEventListener(
	"error",
	(e) => {
		if (!(e.target instanceof HTMLImageElement)) return;
		if (e.target.dataset.imgError) {
			handleImageErrorAttr(e.target);
		} else {
			handleImageError(e.target);
		}
	},
	true,
);

// Gambar yang sudah gagal dimuat sebelum script ini jalan tidak memicu event lagi.
document
	.querySelectorAll<HTMLImageElement>(
		"img[data-fallback], img[data-remove-on-error], img[data-img-error]",
	)
	.forEach((img) => {
		if (img.complete && img.naturalWidth === 0 && img.getAttribute("src")) {
			if (img.dataset.imgError) {
				handleImageErrorAttr(img);
			} else {
				handleImageError(img);
			}
		}
	});

/* ---------- 5. desktop cursor accent ---------- */

(() => {
	const supportsFinePointer = window.matchMedia(
		"(hover: hover) and (pointer: fine) and (prefers-reduced-motion: no-preference)",
	);
	if (!supportsFinePointer.matches) return;

	const cursor = document.createElement("div");
	cursor.className = "cursor-glow";
	cursor.setAttribute("aria-hidden", "true");
	document.body.append(cursor);

	const trail = document.createElement("canvas");
	trail.className = "cursor-trail";
	trail.setAttribute("aria-hidden", "true");
	document.body.append(trail);

	const context = trail.getContext("2d");
	if (!context) {
		trail.remove();
		cursor.remove();
		return;
	}

	type TrailPoint = { x: number; y: number; time: number };
	const points: TrailPoint[] = [];
	let frame = 0;
	let trailFrame = 0;
	let x = 0;
	let y = 0;

	const resizeTrail = () => {
		const ratio = Math.min(window.devicePixelRatio || 1, 2);
		trail.width = Math.round(window.innerWidth * ratio);
		trail.height = Math.round(window.innerHeight * ratio);
		context.setTransform(ratio, 0, 0, ratio, 0, 0);
	};

	const drawTrail = (time: number) => {
		trailFrame = 0;
		while (points.length && time - (points[0]?.time ?? time) > 280) {
			points.shift();
		}
		context.clearRect(0, 0, window.innerWidth, window.innerHeight);

		for (let index = 1; index < points.length; index += 1) {
			const previous = points[index - 1];
			const current = points[index];
			if (!previous || !current) continue;

			const age = time - current.time;
			const life = Math.max(0, 1 - age / 280);
			const taper = index / points.length;
			context.beginPath();
			context.moveTo(previous.x, previous.y);
			context.lineTo(current.x, current.y);
			context.lineWidth = 0.6 + taper * 2.2;
			context.lineCap = "round";
			context.strokeStyle = `rgba(215, 189, 145, ${life * taper * 0.62})`;
			context.shadowColor = "rgba(215, 189, 145, 0.38)";
			context.shadowBlur = 7 * life;
			context.stroke();
		}

		context.shadowBlur = 0;
		if (points.length > 1) {
			trailFrame = window.requestAnimationFrame(drawTrail);
		}
	};

	const scheduleTrail = () => {
		if (!trailFrame) trailFrame = window.requestAnimationFrame(drawTrail);
	};

	resizeTrail();
	window.addEventListener("resize", resizeTrail, { passive: true });

	document.addEventListener(
		"pointermove",
		(event) => {
			if (event.pointerType !== "mouse") return;
			x = event.clientX;
			y = event.clientY;
			const previous = points[points.length - 1];
			if (!previous || Math.hypot(x - previous.x, y - previous.y) > 2) {
				points.push({ x, y, time: performance.now() });
				if (points.length > 22) points.shift();
				scheduleTrail();
			}
			cursor.classList.add("is-visible");
			if (frame) return;

			frame = window.requestAnimationFrame(() => {
				cursor.style.transform = `translate3d(${x}px, ${y}px, 0)`;
				frame = 0;
			});
		},
		{ passive: true },
	);

	document.addEventListener("pointerover", (event) => {
		if (!(event.target instanceof Element)) return;
		cursor.classList.toggle(
			"is-interactive",
			Boolean(
				event.target.closest(
					'a, button, [role="button"], input, select, textarea, summary, label[for]',
				),
			),
		);
	});

	document.addEventListener("pointerout", (event) => {
		if (!event.relatedTarget) cursor.classList.remove("is-visible");
	});

	document.addEventListener("pointerdown", () => {
		cursor.classList.add("is-pressed");
	});

	document.addEventListener("pointerup", () => {
		cursor.classList.remove("is-pressed");
	});
})();
