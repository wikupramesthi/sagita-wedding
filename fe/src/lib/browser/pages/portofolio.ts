const reduceMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;
const rootArtha = document.documentElement;
rootArtha.classList.add("sw-lp-js");
if (reduceMotion) rootArtha.classList.add("sw-lp-tanpa-scrub");
let siap = false;
window.setTimeout(() => {
	if (!siap) rootArtha.classList.remove("sw-lp-js");
}, 12000);

document
	.querySelectorAll<HTMLImageElement | HTMLVideoElement>("[data-src]")
	.forEach((el) => {
		if (el instanceof HTMLVideoElement && el.dataset.srcHp) return;
		const src = el.dataset.src;
		if (src) el.src = src;
		const srcset = el.dataset.srcset;
		if (srcset && el instanceof HTMLImageElement) el.srcset = srcset;
	});

const fokusTerlihat = (target: EventTarget | null) => {
	try {
		return target instanceof Element && target.matches(":focus-visible");
	} catch {
		return false;
	}
};

const muncul = [...document.querySelectorAll<HTMLElement>("[data-muncul]")];
const tanpaViewTimeline = !window.CSS?.supports?.("animation-timeline: view()");
if (
	muncul.length &&
	tanpaViewTimeline &&
	!reduceMotion &&
	"IntersectionObserver" in window
) {
	const pengamat = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					entry.target.classList.add("sw-masuk");
					pengamat.unobserve(entry.target);
				}
			});
		},
		{ rootMargin: "0px 0px -10% 0px", threshold: 0.05 },
	);
	muncul.forEach((el) => {
		pengamat.observe(el);
	});
} else if (tanpaViewTimeline) {
	muncul.forEach((el) => {
		el.classList.add("sw-masuk");
	});
}

const panggung = document.querySelector<HTMLElement>(".sw-hero__panggung");
const produk = [...document.querySelectorAll<HTMLElement>(".sw-produk__item")];
const lapis = [...document.querySelectorAll<HTMLElement>(".sw-hero__lapis")];
if (panggung && produk.length && produk.length === lapis.length) {
	const koneksi = (
		navigator as Navigator & {
			connection?: { saveData?: boolean; effectiveType?: string };
		}
	).connection;
	const hemat =
		Boolean(koneksi?.saveData) || /2g|3g/.test(koneksi?.effectiveType || "");
	const bolehVideo = !reduceMotion && !hemat;
	let siapIdle = false;
	let izinVideo = false;
	const jalankanSaatIdle = (fn: () => void) => {
		const idleWindow = window as Window & {
			requestIdleCallback?: (
				cb: () => void,
				opts?: { timeout: number },
			) => number;
		};
		if (typeof idleWindow.requestIdleCallback === "function") {
			idleWindow.requestIdleCallback(fn, { timeout: 1500 });
		} else {
			window.setTimeout(fn, 800);
		}
	};
	const videoKe = (i: number) => {
		const video = lapis[i].querySelector<HTMLVideoElement>("video");
		if (!video || !bolehVideo || !izinVideo) return null;
		if (!video.getAttribute("src")) {
			video.muted = true;
			video.preload = "metadata";
			const sumber = window.matchMedia("(max-width: 899px)").matches
				? video.dataset.srcHp
				: video.dataset.src;
			if (sumber) video.setAttribute("src", sumber);
		}
		return video;
	};
	const gambarKe = (i: number) => {
		const gambar = lapis[i].querySelector<HTMLImageElement>("img");
		if (!gambar || gambar.getAttribute("src")) return;
		if (gambar.dataset.srcset) {
			gambar.setAttribute("srcset", gambar.dataset.srcset);
		}
		if (gambar.dataset.src) gambar.setAttribute("src", gambar.dataset.src);
	};
	const videoSiap: boolean[] = [];
	let aktif = 0;
	let berjalan = false;
	let terlihat = true;
	let bingkai = 0;
	let terakhir = performance.now();
	let jeda = 0;
	const aktifkanVideo = () => {
		if (!siapIdle || !terlihat || document.hidden || izinVideo) return;
		izinVideo = true;
		gambarKe(aktif);
		videoKe(aktif);
		terakhir = performance.now();
		mulai();
	};
	const mulai = () => {
		if (!berjalan || !terlihat || document.hidden) return;
		const video = videoKe(aktif);
		if (video && !videoSiap[aktif]) {
			const diputar = video.play();
			if (diputar?.catch) {
				void diputar.catch(() => {
					videoSiap[aktif] = true;
					terakhir = performance.now();
				});
			}
		}
		if (!bingkai) {
			terakhir = performance.now() - jeda;
			bingkai = requestAnimationFrame(detak);
		}
	};
	const ganti = (indeks: number) => {
		const berikut = (indeks + produk.length) % produk.length;
		const lama = lapis[aktif].querySelector<HTMLVideoElement>("video");
		if (lama?.getAttribute("src")) lama.pause();
		if (bingkai) cancelAnimationFrame(bingkai);
		bingkai = 0;
		aktif = berikut;
		produk.forEach((el, i) => {
			el.classList.toggle("sw-aktif", i === berikut);
			el.style.setProperty("--isi", "0");
		});
		lapis.forEach((el, i) => {
			el.classList.toggle("sw-aktif", i === berikut);
			el.querySelector("video")?.classList.remove("sw-main");
		});
		gambarKe(berikut);
		const video = videoKe(berikut);
		if (video?.getAttribute("src")) {
			try {
				video.currentTime = 0;
			} catch {}
		}
		terakhir = performance.now();
		jeda = 0;
		mulai();
	};
	const detak = (now: number) => {
		if (!berjalan || document.hidden) {
			bingkai = 0;
			return;
		}
		if (!videoSiap[aktif]) {
			const video = videoKe(aktif);
			if (video?.duration) {
				if (video.duration - video.currentTime < 2.5) {
					const berikut = (aktif + 1) % produk.length;
					gambarKe(berikut);
					videoKe(berikut);
				}
				produk[aktif].style.setProperty(
					"--isi",
					Math.min(1, video.currentTime / video.duration).toFixed(4),
				);
			} else if (now - terakhir > 6000) {
				videoSiap[aktif] = true;
				terakhir = now;
			}
		} else {
			const progres = (now - terakhir) / 7000;
			if (progres >= 1) {
				ganti(aktif + 1);
				return;
			}
			produk[aktif].style.setProperty("--isi", Math.max(0, progres).toFixed(4));
		}
		bingkai = requestAnimationFrame(detak);
	};
	const henti = () => {
		const video = lapis[aktif].querySelector<HTMLVideoElement>("video");
		if (video?.getAttribute("src")) video.pause();
		if (bingkai) cancelAnimationFrame(bingkai);
		bingkai = 0;
		if (!video || !bolehVideo || videoSiap[aktif]) {
			jeda = performance.now() - terakhir;
		}
	};
	lapis.forEach((el, i) => {
		const video = el.querySelector<HTMLVideoElement>("video");
		if (!video) return;
		video.addEventListener("playing", () => {
			el.classList.add("sw-main");
			if (i === aktif) gambarKe((aktif + 1) % produk.length);
		});
		video.addEventListener("ended", () => {
			if (i === aktif) ganti(aktif + 1);
		});
		video.addEventListener("error", () => {
			if (!video.getAttribute("src")) return;
			videoSiap[i] = true;
			if (i === aktif) terakhir = performance.now();
		});
	});
	produk.forEach((el, i) => {
		el.addEventListener("pointerenter", (event) => {
			if (event instanceof PointerEvent && event.pointerType === "mouse") {
				ganti(i);
			}
		});
		el.addEventListener("focusin", () => ganti(i));
	});
	ganti(0);
	if ("IntersectionObserver" in window) {
		new IntersectionObserver(
			([entri]) => {
				terlihat = entri.isIntersecting;
				berjalan = terlihat;
				if (berjalan) {
					aktifkanVideo();
					mulai();
				} else henti();
			},
			{ threshold: 0.15 },
		).observe(panggung);
	}
	document.addEventListener("visibilitychange", () => {
		if (document.hidden) henti();
		else {
			aktifkanVideo();
			mulai();
		}
	});
	const siapkanVideo = () => {
		mulai();
		jalankanSaatIdle(() => {
			siapIdle = true;
			aktifkanVideo();
		});
	};
	if (document.readyState === "complete") siapkanVideo();
	else window.addEventListener("load", siapkanVideo, { once: true });
}

const sorotan = document.querySelector<HTMLElement>(".sw-ds-bb");
const slide = [...document.querySelectorAll<HTMLElement>(".sw-ds-bb__slide")];
const tombol = [
	...document.querySelectorAll<HTMLButtonElement>(".sw-ds-bb__no"),
];
if (sorotan && slide.length > 1) {
	let saatIni = Math.max(
		0,
		slide.findIndex((el) => el.classList.contains("sw-is-aktif")),
	);
	let lewat = 0;
	let terlihat = true;
	let fokus = false;
	const jeda = () => fokus || !terlihat;
	const perbaruiJeda = () => sorotan.classList.toggle("sw-is-jeda", jeda());
	const gantiSlide = (indeks: number) => {
		lewat = 0;
		saatIni = (indeks + slide.length) % slide.length;
		slide.forEach((el, i) => {
			el.classList.toggle("sw-is-aktif", i === saatIni);
			el.setAttribute("aria-hidden", i === saatIni ? "false" : "true");
		});
		tombol.forEach((el, i) => {
			el.setAttribute("aria-pressed", i === saatIni ? "true" : "false");
			el.querySelector(".sw-ds-bb__jalan")?.remove();
			if (i === saatIni) {
				const garis = document.createElement("span");
				garis.className = "sw-ds-bb__jalan";
				el.querySelector(".sw-ds-bb__garis")?.append(garis);
			}
		});
	};
	gantiSlide(saatIni);
	perbaruiJeda();
	tombol.forEach((el, i) => {
		el.addEventListener("click", () => gantiSlide(i));
	});
	if (!reduceMotion) {
		window.setInterval(() => {
			if (jeda() || document.hidden) return;
			lewat += 250;
			if (lewat >= 9000) gantiSlide(saatIni + 1);
		}, 250);
	}
	if ("IntersectionObserver" in window) {
		new IntersectionObserver(([entri]) => {
			terlihat = entri.isIntersecting;
			perbaruiJeda();
		}).observe(sorotan);
	}
	sorotan.addEventListener("focusin", (event) => {
		if (fokusTerlihat(event.target)) {
			fokus = true;
			perbaruiJeda();
		}
	});
	sorotan.addEventListener("focusout", () => {
		fokus = false;
		perbaruiJeda();
	});
}

const artisPanggung = document.querySelector<HTMLElement>(
	"[data-artis-panggung]",
);
const artisJalur =
	artisPanggung?.querySelector<HTMLElement>(".sw-artis__jalur");
if (artisPanggung && artisJalur && "IntersectionObserver" in window) {
	const artisItem = [...artisJalur.children] as HTMLElement[];
	const layarKecil = window.matchMedia("(max-width: 899px)");
	let setengah = 0;
	let tengah: number[] = [];
	const ukur = () => {
		setengah = artisJalur.scrollWidth / 2;
		tengah = artisItem.map((el) => el.offsetLeft + el.offsetWidth / 2);
	};
	const miring = () => {
		const lebar = artisPanggung.clientWidth;
		const fokus = artisPanggung.scrollLeft + lebar / 2;
		const sudut = layarKecil.matches ? 18 : 24;
		artisItem.forEach((el, i) => {
			const jarak = Math.max(
				-1.25,
				Math.min(1.25, (tengah[i] - fokus) / (lebar / 2)),
			);
			el.style.transform = `rotateY(${(-jarak * sudut).toFixed(2)}deg) scale(${(0.9 + 0.12 * jarak * jarak).toFixed(3)})`;
		});
	};
	let jedaSampai = 0;
	let jedaFokus = false;
	const tunda = () => {
		jedaSampai = Date.now() + 1500;
	};
	let bingkai = 0;
	let terakhir = 0;
	let sisa = 0;
	const detak = (now: number) => {
		const selisih = terakhir ? Math.min(64, now - terakhir) : 16;
		terakhir = now;
		if (!reduceMotion && !jedaFokus && Date.now() > jedaSampai && setengah) {
			sisa += (32 * selisih) / 1000;
			const langkah = Math.floor(sisa);
			if (langkah) {
				artisPanggung.scrollLeft += langkah;
				sisa -= langkah;
			}
		}
		if (setengah && artisPanggung.scrollLeft >= setengah) {
			artisPanggung.scrollLeft -= setengah;
		}
		miring();
		bingkai = requestAnimationFrame(detak);
	};
	const mulai = () => {
		if (!bingkai) {
			terakhir = 0;
			bingkai = requestAnimationFrame(detak);
		}
	};
	const berhenti = () => {
		if (bingkai) {
			cancelAnimationFrame(bingkai);
			bingkai = 0;
		}
	};
	ukur();
	miring();
	new IntersectionObserver(([entri]) => {
		if (entri.isIntersecting) mulai();
		else berhenti();
	}).observe(artisPanggung);
	window.addEventListener("resize", () => {
		ukur();
		miring();
	});
	artisPanggung.addEventListener("touchstart", tunda, { passive: true });
	artisPanggung.addEventListener("touchmove", tunda, { passive: true });
	artisPanggung.addEventListener(
		"wheel",
		(event) => {
			if (Math.abs(event.deltaX) > Math.abs(event.deltaY)) tunda();
		},
		{ passive: true },
	);
	artisPanggung.addEventListener("focusin", (event) => {
		if (fokusTerlihat(event.target)) jedaFokus = true;
	});
	artisPanggung.addEventListener("focusout", () => {
		jedaFokus = false;
	});
	artisItem.forEach((el) => {
		const gambar = el.querySelector("img");
		if (gambar && !gambar.complete) gambar.addEventListener("load", ukur);
	});
}

const komposit = [...document.querySelectorAll<HTMLElement>(".sw-komposit")];
const lebarScrub = window.matchMedia("(min-width: 961px)");
const laporan = document.querySelector<HTMLElement>(".sw-bt__laporan");
const layarLaporan = laporan?.querySelector<HTMLElement>(".sw-ipad__layar");
const gambarLaporan = layarLaporan?.querySelector<HTMLImageElement>("img");
let bungkusLaporan: HTMLElement | null = null;
let aturTinggi: (() => void) | null = null;
if (!reduceMotion && laporan && layarLaporan && gambarLaporan) {
	bungkusLaporan = document.createElement("div");
	laporan.parentElement?.insertBefore(bungkusLaporan, laporan);
	bungkusLaporan.append(laporan);
	laporan.style.position = "sticky";
	const atur = () => {
		if (!bungkusLaporan) return;
		const faktor = lebarScrub.matches ? 1.5 : 1.3;
		const atas = window.innerHeight / 2 - laporan.offsetHeight / 2;
		bungkusLaporan.style.height = `${
			laporan.offsetHeight + window.innerHeight * faktor
		}px`;
		laporan.style.top = `${atas}px`;
	};
	aturTinggi = atur;
	atur();
	if (!gambarLaporan.complete) gambarLaporan.addEventListener("load", atur);
}
let antreScrub = false;
const jalankanScrub = () => {
	antreScrub = false;
	if (reduceMotion) return;
	const tinggi = window.innerHeight;
	if (lebarScrub.matches) {
		komposit.forEach((el) => {
			const kotak = el.getBoundingClientRect();
			const progres = Math.min(
				1,
				Math.max(0, (0.95 * tinggi - kotak.top) / (0.65 * tinggi)),
			);
			[...el.querySelectorAll<HTMLElement>(".sw-kp-kartu")].forEach(
				(elKartu, i) => {
					elKartu.style.transform = `translateY(${((1 - progres) * (26 + i * 18)).toFixed(1)}px)`;
				},
			);
		});
	} else {
		komposit.forEach((el) => {
			el.querySelectorAll<HTMLElement>(".sw-kp-kartu").forEach((elKartu) => {
				elKartu.style.transform = "";
			});
		});
	}
	if (bungkusLaporan && laporan && layarLaporan && gambarLaporan) {
		const jarak = tinggi * (lebarScrub.matches ? 1.5 : 1.3);
		const atas = tinggi / 2 - laporan.offsetHeight / 2;
		const progres = Math.min(
			1,
			Math.max(0, (atas - bungkusLaporan.getBoundingClientRect().top) / jarak),
		);
		const geser = Math.max(
			0,
			gambarLaporan.offsetHeight - layarLaporan.clientHeight,
		);
		gambarLaporan.style.transform = `translateY(${(-progres * geser).toFixed(1)}px)`;
	}
};
const mintaScrub = () => {
	if (!antreScrub) {
		antreScrub = true;
		requestAnimationFrame(jalankanScrub);
	}
};
if (!reduceMotion) {
	window.addEventListener("scroll", mintaScrub, { passive: true });
	window.addEventListener("resize", () => {
		aturTinggi?.();
		mintaScrub();
	});
	lebarScrub.addEventListener("change", () => {
		aturTinggi?.();
		mintaScrub();
	});
	mintaScrub();
}

document
	.querySelectorAll<HTMLButtonElement>("[data-jeda]")
	.forEach((tombolJeda) => {
		const target = document.querySelector<HTMLElement>(
			tombolJeda.dataset.jeda || "",
		);
		if (!target) return;
		const label = tombolJeda.getAttribute("aria-label") || "";
		const labelLanjut =
			tombolJeda.dataset.ariaLanjut || label.replace(/^Jeda /, "Lanjutkan ");
		tombolJeda.addEventListener("click", () => {
			const jeda = target.classList.toggle("sw-dijeda");
			tombolJeda.setAttribute("aria-pressed", String(jeda));
			tombolJeda.setAttribute("aria-label", jeda ? labelLanjut : label);
			tombolJeda.innerHTML = `<svg aria-hidden="true" class="sw-ikon"><use href="#sw-i-${jeda ? "play" : "pause"}"></use></svg>`;
		});
	});

const saring = [
	...document.querySelectorAll<HTMLButtonElement>("[data-saring]"),
];
const katalog = document.querySelector<HTMLElement>(".sw-marquee--katalog");
const desain = [...document.querySelectorAll<HTMLElement>(".sw-desain-kartu")];
const tautanKatalog = document.querySelector<HTMLElement>(
	"[data-tautan-katalog]",
);
saring.forEach((chip) => {
	chip.addEventListener("click", () => {
		const kunci = chip.dataset.saring || "semua";
		saring.forEach((el) => {
			el.setAttribute("aria-pressed", String(el === chip));
		});
		desain.forEach((el) => {
			const gaya = ` ${el.dataset.gaya || ""} `;
			el.hidden = !(kunci === "semua" || gaya.includes(` ${kunci} `));
		});
		katalog?.classList.toggle("sw-diam", kunci !== "semua");
		if (katalog) katalog.scrollLeft = 0;
		if (tautanKatalog) {
			tautanKatalog.setAttribute(
				"href",
				kunci === "semua" ? "/design" : `/design?style=${kunci}`,
			);
		}
	});
});

document.querySelectorAll<HTMLElement>(".sw-marquee").forEach((marquee) => {
	marquee.addEventListener("focusin", (event) => {
		if (!fokusTerlihat(event.target)) return;
		marquee.classList.add("sw-marquee--fokus");
		if (!(event.target instanceof Element)) return;
		const el = event.target.closest<HTMLElement>(".sw-porto, .sw-desain-kartu");
		el?.scrollIntoView({ block: "nearest", inline: "nearest" });
	});
	marquee.addEventListener("focusout", (event) => {
		if (marquee.contains(event.relatedTarget as Node | null)) return;
		marquee.classList.remove("sw-marquee--fokus");
		marquee.scrollLeft = 0;
	});
});

const ctaHP = document.querySelector<HTMLElement>(".sw-cta-hp");
if (ctaHP && "IntersectionObserver" in window) {
	const zona = new Map<Element, boolean>();
	const perbaruiCTA = () => {
		const ada = [...zona.values()].some(Boolean);
		ctaHP.classList.toggle("sw-tampil", !ada);
	};
	const pengamatCTA = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			zona.set(
				entry.target,
				entry.isIntersecting &&
					entry.target instanceof HTMLElement &&
					entry.target.offsetParent !== null,
			);
		});
		perbaruiCTA();
	});
	document
		.querySelectorAll<HTMLElement>(
			".sw-hero__aksi, .sw-bt__cta, .sw-harga__kaki, .sw-penutup, .sw-kaki-hal, [data-ajakan]",
		)
		.forEach((el) => {
			pengamatCTA.observe(el);
		});
	perbaruiCTA();
	document.addEventListener("focusin", (event) => {
		if (
			!window.matchMedia("(max-width: 899px)").matches ||
			!ctaHP.classList.contains("sw-tampil") ||
			ctaHP.contains(event.target as Node) ||
			!fokusTerlihat(event.target) ||
			!(event.target instanceof HTMLElement)
		) {
			return;
		}
		const bawah = ctaHP.getBoundingClientRect().top - 12;
		const kotak = event.target.getBoundingClientRect();
		if (kotak.bottom > bawah && kotak.top < window.innerHeight) {
			window.scrollBy(0, kotak.bottom - bawah);
		}
	});
}

const angka = document.querySelector<HTMLElement>(".sw-angka[data-target]");
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

siap = true;

export {};
