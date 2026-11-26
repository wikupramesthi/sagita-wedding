const reduceMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;
const rootArtha = document.documentElement;
rootArtha.classList.add("lp-js");
if (reduceMotion) rootArtha.classList.add("lp-tanpa-scrub");
let siap = false;
window.setTimeout(() => {
	if (!siap) rootArtha.classList.remove("lp-js");
}, 12000);

document
	.querySelectorAll<HTMLImageElement | HTMLVideoElement>("[data-src]")
	.forEach((el) => {
		const src = el.dataset.src;
		if (src) el.src = src;
		const srcset = el.dataset.srcset;
		if (srcset && el instanceof HTMLImageElement) el.srcset = srcset;
	});

const muncul = [...document.querySelectorAll<HTMLElement>("[data-muncul]")];
if (muncul.length && "IntersectionObserver" in window) {
	const pengamat = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					entry.target.classList.add("masuk");
					pengamat.unobserve(entry.target);
				}
			});
		},
		{ rootMargin: "0px 0px -8% 0px", threshold: 0.05 },
	);
	muncul.forEach((el) => {
		pengamat.observe(el);
	});
} else {
	muncul.forEach((el) => {
		el.classList.add("masuk");
	});
}

const panggung = document.querySelector<HTMLElement>(".hero__panggung");
const produk = [...document.querySelectorAll<HTMLElement>(".produk__item")];
const lapis = [...document.querySelectorAll<HTMLElement>(".hero__lapis")];
if (produk.length && lapis.length) {
	let aktif = 0;
	let berhenti = false;
	let berjalan = 0;
	let terakhir = performance.now();
	const durasi = 5200;
	const tampilkan = (indeks: number) => {
		aktif = indeks;
		produk.forEach((el, i) => {
			el.classList.toggle("aktif", i === indeks);
		});
		lapis.forEach((el, i) => {
			el.classList.toggle("aktif", i === indeks);
			const video = el.querySelector<HTMLVideoElement>("video");
			if (!video) return;
			if (i === indeks) {
				video.classList.add("main");
				if (!reduceMotion) void video.play().catch(() => {});
			} else {
				video.classList.remove("main");
				video.pause();
			}
		});
	};
	tampilkan(0);
	if (!reduceMotion) {
		const detak = (now: number) => {
			berjalan += now - terakhir;
			terakhir = now;
			if (!berhenti) {
				if (berjalan >= durasi) {
					berjalan = 0;
					tampilkan((aktif + 1) % produk.length);
				}
				produk[aktif].style.setProperty("--isi", String(berjalan / durasi));
			}
			requestAnimationFrame(detak);
		};
		requestAnimationFrame(detak);
		panggung?.addEventListener("mouseenter", () => {
			berhenti = true;
		});
		panggung?.addEventListener("mouseleave", () => {
			berhenti = false;
		});
	}
}

const sorotan = document.querySelector<HTMLElement>(".ds-bb");
const slide = [...document.querySelectorAll<HTMLElement>(".ds-bb__slide")];
const tombol = [...document.querySelectorAll<HTMLButtonElement>(".ds-bb__no")];
if (sorotan && slide.length > 1) {
	let saatIni = Math.max(
		0,
		slide.findIndex((el) => el.classList.contains("is-aktif")),
	);
	const gantiSlide = (indeks: number) => {
		saatIni = indeks;
		slide.forEach((el, i) => {
			el.classList.toggle("is-aktif", i === indeks);
			el.setAttribute("aria-hidden", i === indeks ? "false" : "true");
		});
		tombol.forEach((el, i) => {
			el.setAttribute("aria-pressed", i === indeks ? "true" : "false");
			el.querySelector(".ds-bb__jalan")?.remove();
			if (i === indeks) {
				const garis = document.createElement("span");
				garis.className = "ds-bb__jalan";
				el.querySelector(".ds-bb__garis")?.append(garis);
			}
		});
	};
	gantiSlide(saatIni);
	if (!reduceMotion) {
		let pengatur = window.setInterval(
			() => gantiSlide((saatIni + 1) % slide.length),
			9000,
		);
		tombol.forEach((el, i) => {
			el.addEventListener("click", () => {
				gantiSlide(i);
				window.clearInterval(pengatur);
				pengatur = window.setInterval(
					() => gantiSlide((saatIni + 1) % slide.length),
					9000,
				);
			});
		});
		sorotan.addEventListener("mouseenter", () =>
			sorotan.classList.add("is-jeda"),
		);
		sorotan.addEventListener("mouseleave", () =>
			sorotan.classList.remove("is-jeda"),
		);
	}
}

const lintasan = document.querySelector<HTMLElement>(".kelola__lintasan");
const langkah = [...document.querySelectorAll<HTMLElement>(".kelola__item")];
const kartu = [...document.querySelectorAll<HTMLElement>(".kp-ganti")];
if (lintasan && langkah.length) {
	const perbarui = () => {
		const kotak = lintasan.getBoundingClientRect();
		const jarak = Math.max(1, lintasan.offsetHeight - window.innerHeight);
		const progres = Math.min(1, Math.max(0, -kotak.top / jarak));
		const indeks = Math.min(
			langkah.length - 1,
			Math.floor(progres * langkah.length),
		);
		langkah.forEach((el, i) => {
			el.classList.toggle("aktif", i === indeks);
			el.querySelector("button")?.setAttribute(
				"aria-expanded",
				i === indeks ? "true" : "false",
			);
		});
		kartu.forEach((el) => {
			el.classList.toggle("tampil", Number(el.dataset.untuk) === indeks);
		});
	};
	perbarui();
	window.addEventListener("scroll", perbarui, { passive: true });
	window.addEventListener("resize", perbarui);
}

document
	.querySelectorAll<HTMLButtonElement>("[data-jeda]")
	.forEach((tombolJeda) => {
		tombolJeda.addEventListener("click", () => {
			const target = document.querySelector<HTMLElement>(
				tombolJeda.dataset.jeda || "",
			);
			if (!target) return;
			const jeda = target.classList.toggle("dijeda");
			tombolJeda.setAttribute("aria-pressed", jeda ? "true" : "false");
			tombolJeda
				.querySelector("use")
				?.setAttribute("href", jeda ? "#i-play" : "#i-pause");
		});
	});

const saring = [
	...document.querySelectorAll<HTMLButtonElement>("[data-saring]"),
];
const katalog = document.querySelector<HTMLElement>(".marquee--katalog");
const desain = [...document.querySelectorAll<HTMLElement>(".desain-kartu")];
saring.forEach((chip) => {
	chip.addEventListener("click", () => {
		const kunci = chip.dataset.saring || "semua";
		saring.forEach((el) => {
			el.setAttribute("aria-pressed", el === chip ? "true" : "false");
		});
		katalog?.classList.toggle("diam", kunci !== "semua");
		desain.forEach((el) => {
			el.style.display =
				kunci === "semua" || (el.dataset.gaya || "").includes(kunci)
					? ""
					: "none";
		});
	});
});

const ctaHP = document.querySelector<HTMLElement>(".cta-hp");
const hero = document.querySelector<HTMLElement>(".hero");
if (ctaHP && hero && "IntersectionObserver" in window) {
	new IntersectionObserver(
		([entri]) => {
			ctaHP.classList.toggle(
				"tampil",
				!entri.isIntersecting && entri.boundingClientRect.top < 0,
			);
		},
		{ threshold: 0 },
	).observe(hero);
}

const angka = document.querySelector<HTMLElement>(".angka[data-target]");
if (angka && !reduceMotion && "IntersectionObserver" in window) {
	const target = Number(angka.dataset.target || 0);
	const awal = performance.now();
	const durasi = 1800;
	const detakAngka = (now: number) => {
		const progres = Math.min(1, (now - awal) / durasi);
		const nilai = Math.round(target * (1 - (1 - progres) ** 3));
		angka.textContent = String(nilai).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		if (progres < 1) requestAnimationFrame(detakAngka);
	};
	new IntersectionObserver(
		([entri], pengamat) => {
			if (entri.isIntersecting) {
				pengamat.disconnect();
				requestAnimationFrame(detakAngka);
			}
		},
		{ threshold: 0.4 },
	).observe(angka);
}

const waMelayang = document.querySelector<HTMLElement>(".wa-melayang");
if (waMelayang && hero && "IntersectionObserver" in window) {
	new IntersectionObserver(
		([entri]) => {
			waMelayang.classList.toggle("di-hero", entri.isIntersecting);
		},
		{ threshold: 0 },
	).observe(hero);
}

siap = true;

export {};
