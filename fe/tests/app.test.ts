import request from "supertest";
import { beforeEach, describe, expect, it, vi } from "vitest";

const { apiRequestMock } = vi.hoisted(() => ({ apiRequestMock: vi.fn() }));
vi.mock("../src/services/apiClient.js", () => ({ default: apiRequestMock }));

/* ---------- mock semua service agar test tidak menyentuh backend ---------- */

vi.mock("../src/services/siteIdentityService.js", () => ({
	resolveSite: () => ({
		name: "Sagita Wedding",
		tagline: "Wedding Planner",
		description: "Deskripsi uji.",
		address: "Jl. Uji",
		phone: "0812",
		phoneTel: "+62812",
		email: "uji@example.com",
		instagramHandle: "sagita.test",
		instagramHref: "https://www.instagram.com/sagita.test/",
		facebookHref: "",
		youtubeHref: "",
		tiktokHref: "",
		logoUrl: "https://example.test/sagita-logo.webp",
		logoWhiteUrl: "/img/logo-white.webp",
	}),
}));

const CAPTCHA = {
	captcha_id: "cf4e35eb-36da-4de1-b0d9-4f36d3a618d8",
	question: "Masukkan 4 angka berikut.",
	image: "data:image/svg+xml;base64,PHN2Zy8+",
};

const EVENTS_PAGE = {
	items: [{ judul: "Event Uji", deskripsi: "<p>ok</p>" }],
	currentPage: 2,
	lastPage: 5,
	perPage: 10,
	total: 42,
};

const PACKAGES_PAGE = {
	items: [{ uuid: "p1", name: "Paket Uji", slug: "paket-uji", options: [] }],
	currentPage: 1,
	lastPage: 3,
	perPage: 10,
	total: 25,
};

let homeSliderBanners: unknown[] = [];
let homeTestimonials: unknown = [];
let homeArticles: unknown[] = [];
let blogPosts: unknown[] = [];
let blogDetailPost: unknown = null;
let homePackages: unknown[] = [];
let homeFaqs: unknown[] = [];
let cmsPages = false;
let homeVenues: unknown = [
	{
		uuid: "v1",
		name: "Venue Uji",
		slug: "venue-uji",
		address: "Jl. Pernikahan",
		excerpt: "Tempat untuk perayaan istimewa.",
	},
];
let homeWeddingContentReady = true;

const getPageBatch = vi.fn(
	async (page: string, params: Record<string, unknown> = {}) => {
		switch (page) {
			case "packages":
				return {
					packages: PACKAGES_PAGE.items,
					meta: {
						current_page: params.page ?? PACKAGES_PAGE.currentPage,
						last_page: PACKAGES_PAGE.lastPage,
						total: PACKAGES_PAGE.total,
					},
				};
			case "events":
				return {
					events: EVENTS_PAGE.items,
					meta: {
						current_page: EVENTS_PAGE.currentPage,
						last_page: EVENTS_PAGE.lastPage,
						total: EVENTS_PAGE.total,
					},
				};
			case "contact":
				return { faqs: [] };
			case "faq":
				return { faqs: [], categories: [] };
			case "vendors":
				return {
					vendors: [
						{
							name: "Vendor Katering",
							vendors_categories: [{ name: "Katering" }],
						},
						{
							name: "Vendor Dekorasi",
							vendors_categories: [{ name: "Dekorasi" }],
						},
					],
				};
			case "about":
				return { stats: null, articles: [] };
			case "page-detail": {
				if (!cmsPages) return {};
				const slug = String(params.slug ?? "");
				return {
					page: {
						slug,
						title: `CMS ${slug}`,
						excerpt: `Ringkasan ${slug}`,
						content: `<p>Konten CMS ${slug}</p>`,
					},
				};
			}
			case "home":
				return {
					weddingContentReady: homeWeddingContentReady,
					sliderBanners: homeSliderBanners,
					venues: homeVenues,
					packages: homePackages,
					articles: homeArticles,
					testimonials: homeTestimonials,
					faqs: homeFaqs,
				};
			case "venues":
				return {
					venues: [
						{
							uuid: "v1",
							name: "Venue Uji",
							slug: "venue-uji",
							address: "Jl. Pernikahan",
							excerpt: "Tempat untuk perayaan istimewa.",
						},
					],
				};
			case "blog":
				return { articles: blogPosts, total: blogPosts.length };
			case "blog-detail":
				if (!blogDetailPost) return {};
				return {
					post: blogDetailPost,
					related: blogPosts.slice(0, 2),
					popular: blogPosts.slice().reverse(),
					venues: [
						{
							uuid: "v1",
							name: "Venue Uji",
							slug: "venue-uji",
							address: "Jl. Pernikahan",
							image: "/img/venue.jpg",
						},
					],
				};
			default:
				return {};
		}
	},
);

vi.mock("../src/services/batchService.js", () => ({
	getPageBatch: (page: string, params?: Record<string, unknown>) =>
		getPageBatch(page, params),
}));

vi.mock("../src/services/siteStatsService.js", () => ({
	formatStatCount: (v: unknown) => String(v ?? 0),
}));

const { createApp } = await import("../src/app.js");

const app = createApp();

beforeEach(() => {
	vi.clearAllMocks();
	apiRequestMock.mockImplementation(async (path: string) => {
		if (path.startsWith("/contact/captcha")) return CAPTCHA;
		return {
			message: "Pesan berhasil dikirim. Kami akan segera menghubungi Anda.",
			duplicate: false,
		};
	});
	homeSliderBanners = [];
	homeTestimonials = [];
	homeArticles = [];
	blogPosts = [];
	blogDetailPost = null;
	homePackages = [];
	cmsPages = false;
	homeFaqs = [
		{
			uuid: "4b9411a3-ccc5-44fd-bc24-e8dc7065773c",
			kategori: "Perencanaan Pernikahan",
			question: "When should we start planning our wedding?",
			answer: "Ideally, start 6–12 months before the big day.",
		},
		{
			uuid: "fa2b17f9-f0d4-4849-8373-8d787b80113e",
			kategori: "Paket & Layanan",
			question: "What is included in a wedding planning package?",
			answer: "Packages cover planning and coordination.",
		},
	];
	homeWeddingContentReady = true;
	homeVenues = [
		{
			uuid: "v1",
			name: "Venue Uji",
			slug: "venue-uji",
			address: "Jl. Pernikahan",
			excerpt: "Tempat untuk perayaan istimewa.",
		},
	];
});

/* ------------------------------ health ------------------------------ */

describe("health", () => {
	it("GET /healthz hidup tanpa memanggil backend", async () => {
		const res = await request(app).get("/healthz");
		expect(res.status).toBe(200);
		expect(res.body.status).toBe("ok");
		expect(res.headers["cache-control"]).toBe("no-store");
	});
});

describe("telemetry", () => {
	it("menerima metrik web vitals dari browser", async () => {
		const res = await request(app).post("/api/telemetry").send({
			kind: "vital",
			name: "LCP",
			value: 123.456,
			path: "/",
		});
		expect(res.status).toBe(204);
	});

	it("menolak payload telemetry yang tidak dikenal", async () => {
		const res = await request(app)
			.post("/api/telemetry")
			.send({ kind: "vital", name: "", value: 1, path: "/" });
		expect(res.status).toBe(400);
		expect(res.body.success).toBe(false);
	});
});

/* --------------------------- security headers -------------------------- */

describe("security headers", () => {
	// Catatan: /healthz & /readyz di-mount sebelum cspNonce/securityHeaders
	// (src/app.ts), jadi endpoint liveness itu memang tanpa CSP.
	it("menyembunyikan x-powered-by", async () => {
		const res = await request(app).get("/healthz");
		expect(res.headers["x-powered-by"]).toBeUndefined();
	});

	it("halaman publik memakai CSP ketat dengan nonce", async () => {
		const res = await request(app).get("/");
		const csp = res.headers["content-security-policy"];
		expect(csp).toBeDefined();
		expect(csp).toContain("script-src");
		expect(csp).toMatch(/script-src[^;]*'nonce-/);
		expect(csp).not.toMatch(/script-src[^;]*'unsafe-inline'/);
		expect(csp).toMatch(/script-src-attr 'none'/);
		expect(csp).toContain("frame-ancestors 'none'");
		expect(csp).toContain("object-src 'none'");
	});

	it("nonce di CSP sama dengan nonce di tag <script>", async () => {
		const res = await request(app).get("/");
		const cspNonce = /'nonce-([^']+)'/.exec(
			res.headers["content-security-policy"],
		)?.[1];
		expect(cspNonce).toBeTruthy();
		expect(res.text).toContain(`nonce="${cspNonce}"`);
	});

	it("nonce berbeda tiap request", async () => {
		const grab = (csp: string) => /'nonce-([^']+)'/.exec(csp)?.[1];
		const a = await request(app).get("/");
		const b = await request(app).get("/");
		expect(grab(a.headers["content-security-policy"])).toBeTruthy();
		expect(grab(a.headers["content-security-policy"])).not.toBe(
			grab(b.headers["content-security-policy"]),
		);
	});
});

/* ------------------------------- robots ------------------------------- */

describe("robots.txt", () => {
	it("menyertakan directive Sitemap", async () => {
		const res = await request(app).get("/robots.txt");
		expect(res.status).toBe(200);
		expect(res.text).toMatch(/Sitemap:\s*http/i);
	});
});

/* ------------------------------ /api/contact ---------------------------- */

describe("POST /api/contact", () => {
	const valid = {
		nama: "Budi",
		email: "budi@example.com",
		no_telp: "081234567890",
		isi: "Halo",
		captcha_id: CAPTCHA.captcha_id,
		captcha_answer: "0042",
	};

	it("menolak request lintas origin (CSRF)", async () => {
		const res = await request(app)
			.post("/api/contact")
			.set("Origin", "https://evil.test")
			.send(valid);
		expect(res.status).toBe(403);
	});

	it("menolak payload tidak valid dengan 400", async () => {
		const res = await request(app).post("/api/contact").send({ nama: "" });
		expect(res.status).toBe(400);
		expect(res.body.success).toBe(false);
	});

	it("menerima payload valid", async () => {
		const res = await request(app).post("/api/contact").send(valid);
		expect(res.status).toBe(200);
		expect(res.body.success).toBe(true);
	});
});

describe("GET /api/contact/captcha", () => {
	it("mengembalikan captcha dan tanpa cache", async () => {
		const res = await request(app).get("/api/contact/captcha");
		expect(res.status).toBe(200);
		expect(res.body.captcha_id).toBe(CAPTCHA.captcha_id);
		expect(res.body.question).toBe(CAPTCHA.question);
		expect(res.body.image).toBe(CAPTCHA.image);
		expect(res.headers["cache-control"]).toBe("no-store");
	});
});

describe("consultation form", () => {
	it("loads the four-digit local CAPTCHA and versioned page script", async () => {
		const res = await request(app).get("/formulir-konsultasi");

		expect(res.status).toBe(200);
		expect(res.text).toContain('id="consultationCaptchaCode"');
		expect(res.text).toContain('pattern="[0-9]{4}"');
		expect(res.text).toMatch(
			/\/js\/pages\/consultation-form\.js\?v=[^"]+-localcaptchaimage1/,
		);
	});
});

describe("booking publik", () => {
	const payload = {
		nama: "Budi",
		email: "budi@example.com",
		no_telp: "081234567890",
		paket: "Paket Uji",
		tanggal: "2026-12-20",
		isi: "Ingin konsultasi.",
		captcha_id: CAPTCHA.captcha_id,
		captcha_answer: "0042",
	};

	it("POST /api/booking meneruskan permintaan ke backend", async () => {
		const res = await request(app).post("/api/booking").send(payload);

		expect(res.status).toBe(200);
		expect(res.body.success).toBe(true);
		expect(res.body.duplicate).toBe(false);
		expect(apiRequestMock).toHaveBeenCalledWith(
			"/contact",
			expect.objectContaining({
				method: "POST",
				body: JSON.stringify({
					nama: payload.nama,
					email: payload.email,
					no_telp: payload.no_telp,
					isi: `Permintaan booking paket ${payload.paket} untuk tanggal ${payload.tanggal}. Catatan: ${payload.isi}`,
					captcha_id: payload.captcha_id,
					captcha_answer: payload.captcha_answer,
				}),
			}),
		);
	});

	it("POST /api/booking/captcha memakai captcha kontak", async () => {
		const res = await request(app).get("/api/booking/captcha");

		expect(res.status).toBe(200);
		expect(res.body.captcha_id).toBe(CAPTCHA.captcha_id);
	});

	it("POST /api/booking tetap 400 bila payload tidak valid", async () => {
		const res = await request(app).post("/api/booking").send({ nama: "" });
		expect(res.status).toBe(400);
	});

	it("POST /api/newsletter membalas 501, bukan sukses palsu", async () => {
		const res = await request(app)
			.post("/api/newsletter")
			.send({ email: "a@b.co" });
		expect(res.status).toBe(501);
		expect(res.body.success).toBe(false);
	});
});

/* ------------------------------ pagination ----------------------------- */

describe("pagination dari meta backend", () => {
	it("/packages memakai totalPages dari backend, bukan hardcode", async () => {
		const res = await request(app).get("/packages");
		expect(res.status).toBe(200);
		// mock: last_page = 3, current_page = 1 -> nav harus tampil
		expect(res.text).toContain('aria-label="Pagination"');
		expect(res.text).toMatch(/aria-current="page"[^>]*>\s*1\s*</);
		expect(res.text).toContain("page=2");
		expect(res.text).toContain("page=3");
	});

	it("/packages meneruskan page ke query backend", async () => {
		await request(app).get("/packages?page=2&sort=lowest_price");
		expect(getPageBatch).toHaveBeenCalledWith(
			"packages",
			expect.objectContaining({ page: 2, sort: "lowest_price", per_page: 10 }),
		);
	});

	it("/event memakai totalPages dari backend", async () => {
		const res = await request(app).get("/event");
		expect(res.status).toBe(200);
		// mock: current_page = 2, last_page = 5
		expect(res.text).toContain('aria-label="Pagination"');
		expect(res.text).toMatch(/aria-current="page"[^>]*>\s*2\s*</);
		expect(res.text).toContain("page=5");
	});

	it("/event meneruskan page ke query backend", async () => {
		await request(app).get("/event?page=3");
		expect(getPageBatch).toHaveBeenCalledWith(
			"events",
			expect.objectContaining({ page: 3, per_page: 10 }),
		);
	});
});

/* ------------------------- pageScript wiring ------------------------- */

describe("pageScript wiring", () => {
	it("footer uses identity API values for branding and contact details", async () => {
		const res = await request(app).get("/");

		expect(res.status).toBe(200);
		expect(res.text).toContain('src="https://example.test/sagita-logo.webp"');
		expect(res.text).toContain('"slogan":"Wedding Planner"');
		expect(res.text).toContain('href="tel:+62812"');
		expect(res.text).toContain('href="https://www.instagram.com/sagita.test/"');
		expect(res.text).toContain('aria-label="Sagita Wedding on Instagram"');
		const footer = res.text.match(/<footer[\s\S]*?<\/footer>/)?.[0] ?? "";
		expect(footer).not.toContain('href="https://api.whatsapp.com/send/');
		expect(footer).toContain('href="/portofolio"');
	});
	it("halaman dengan island memuat loader bundle", async () => {
		const cases: [string, string][] = [
			["/formulir-konsultasi", "consultation-form"],
			["/vendors", "vendors"],
			["/faq", "faq"],
			["/blog", "blog"],
			["/", "home"],
		];
		for (const [path, name] of cases) {
			const res = await request(app).get(path);
			expect(res.status, path).toBe(200);
			expect(res.text, `${path} harus memuat pages/${name}.js`).toContain(
				`/js/pages/${name}.js`,
			);
		}
	});

	it("halaman blog menampilkan artikel unggulan dan tombol muat lainnya", async () => {
		blogPosts = Array.from({ length: 10 }, (_, index) => ({
			slug: `artikel-${index + 1}`,
			title: `Artikel ${index + 1}`,
			image: `https://example.test/article-${index + 1}.webp`,
			category: "Tips",
			date: "12 November 2026",
			read: "4 menit baca",
			excerpt: `Ringkasan artikel ${index + 1}.`,
		}));

		const res = await request(app).get("/blog").set("Cookie", "locale=id");
		expect(res.status).toBe(200);
		expect(res.text).toContain('id="articleGrid"');
		expect(res.text).toContain("blog-feature-card");
		expect(res.text).toContain("Bacaan pilihan");
		expect(res.text).toContain("Baca artikel");
		expect(res.text).toContain('id="loadMoreBtn"');
		expect(res.text.match(/article-card blog-card/g)).toHaveLength(10);
		expect(res.text).toContain("hidden");
	});

	it("halaman blog menampilkan pesan kosong ketika belum ada artikel", async () => {
		const res = await request(app).get("/blog").set("Cookie", "locale=id");
		expect(res.status).toBe(200);
		expect(res.text).toContain("Belum ada artikel");
		expect(res.text).not.toContain('id="articleGrid"');
	});

	it("halaman detail blog punya SEO artikel dan sidebar berita populer serta venue", async () => {
		blogPosts = [
			{
				slug: "artikel-1",
				title: "Artikel Satu",
				featured_image: "https://example.test/artikel-1.webp",
				scheduled_at: "2026-11-20T08:00:00Z",
				content: "<p>Isi artikel satu.</p>",
				category: "Tips",
				excerpt: "Ringkasan artikel satu.",
			},
			{
				slug: "artikel-2",
				title: "Artikel Dua",
				featured_image: "https://example.test/artikel-2.webp",
				scheduled_at: "2026-11-21T08:00:00Z",
				content: "<p>Isi artikel dua.</p>",
				category: "Venue",
				excerpt: "Ringkasan artikel dua.",
			},
		];
		blogDetailPost = {
			slug: "panduan-venue",
			title: "Panduan Memilih Venue",
			featured_image: "https://example.test/cover.webp",
			scheduled_at: "2026-11-22T08:00:00Z",
			updated_at: "2026-11-23T08:00:00Z",
			category: "Venue",
			excerpt: "Semua yang perlu dipertimbangkan saat memilih venue.",
			content: "<h2>Memilih venue</h2><p>Isi artikel.</p>",
		};

		const res = await request(app)
			.get("/blog/panduan-venue")
			.set("Cookie", "locale=id");

		expect(res.status).toBe(200);
		expect(getPageBatch).toHaveBeenCalledWith("blog-detail", {
			slug: "panduan-venue",
		});
		expect(res.text).toContain('property="og:type" content="article"');
		expect(res.text).toContain(
			'property="article:published_time" content="2026-11-22"',
		);
		expect(res.text).toContain('"@type":"BlogPosting"');
		expect(res.text).toContain("blog-detail-main");
		expect(res.text).toContain("blog-detail-aside");
		expect(res.text).toContain("Berita populer");
		expect(res.text).toContain("Rekomendasi venue");
		expect(res.text).toContain('href="/venues#venue-venue-uji"');
		expect(res.text).toContain("Artikel Satu");
	});

	it("halaman vendor menggunakan satu batch dengan kategori untuk filter frontend", async () => {
		const res = await request(app).get("/vendors");
		expect(res.status).toBe(200);
		expect(getPageBatch).toHaveBeenCalledTimes(1);
		expect(getPageBatch).toHaveBeenCalledWith("vendors", undefined);
		expect(res.text).toContain('data-category="Katering"');
		expect(res.text).toContain('data-category="Dekorasi"');
		expect(res.text).toContain('data-categories="[&#34;Katering&#34;]"');
		expect(res.text).toContain("/js/pages/vendors.js");
		expect(res.text).toContain("The right people");
		expect(res.text).toContain("Wedding Vendors | Sagita Wedding Planner");
		expect(res.text).toContain("Ask about this vendor");
		expect(res.text).toContain("https://wa.me/");
		expect(res.text).toContain("Vendor%20Katering");
		expect(res.text).toContain('href="/formulir-konsultasi"');
		expect(res.text).not.toContain("Book a Session");
		expect(res.text).not.toContain("certified RIMY");
		expect(res.text).not.toContain("mindful movement and intention");
	});

	it("form konsultasi mandiri tidak merender header atau footer global", async () => {
		const res = await request(app).get("/formulir-konsultasi");
		expect(res.status).toBe(200);
		expect(res.text).toContain(
			"<title>Wedding Planning Consultation | Sagita Wedding Planner</title>",
		);
		expect(res.text).toContain('<meta name="robots" content="index, follow"/>');
		expect(res.text).toContain(
			'<meta name="description" content="Plan your wedding with Sagita Wedding Planner.',
		);
		expect(res.text).toContain('id="consultationForm"');
		expect(res.text).toContain('<dialog id="consultationSuccess"');
		expect(res.text).toContain('id="consultationWhatsappConfirm"');
		expect(res.text).toContain('placeholder="Enter code"');
		expect(res.text).toContain(
			'data-whatsapp-message="Hello, I’ve submitted the wedding consultation form. I look forward to hearing from your team. Thank you."',
		);
		expect(res.text).toContain(
			"Wedding planning shaped around your celebration",
		);
		expect(res.text).toContain('name="wedding_date"');
		expect(res.text).toContain('name="services"');
		expect(res.text).toContain("/js/pages/consultation-form.js");
		expect(res.text).not.toContain('id="navbar"');
		expect(res.text).not.toContain("<footer");
		expect(getPageBatch).not.toHaveBeenCalledWith("contact");
	});

	it("form konsultasi memiliki metadata berbahasa Indonesia saat locale id aktif", async () => {
		const res = await request(app)
			.get("/formulir-konsultasi")
			.set("Cookie", "locale=id");
		expect(res.status).toBe(200);
		expect(res.text).toContain(
			"<title>Konsultasi Wedding Organizer &amp; Wedding Planner | Sagita</title>",
		);
		expect(res.text).toContain("Rencana pernikahan, dibahas bersama");
		expect(res.text).toContain("Terima kasih telah menghubungi kami.");
		expect(res.text).toContain('placeholder="Ketik di sini"');
		expect(res.text).toContain(
			'data-whatsapp-message="Halo, saya sudah mengirim formulir konsultasi pernikahan. Mohon kabari saya jika ada informasi lebih lanjut. Terima kasih."',
		);
		expect(res.text).toContain("Permintaan konsultasi Anda telah kami terima.");
	});

	it("halaman tanpa island tidak memuat loader halaman", async () => {
		const res = await request(app).get("/about");
		expect(res.status).toBe(200);
		expect(res.text).not.toContain("/js/pages/");
	});

	it("halaman about dan konsultasi memakai konten CMS bila tersedia", async () => {
		cmsPages = true;
		const about = await request(app).get("/about");
		expect(about.status).toBe(200);
		expect(about.text).toContain("<title>CMS about</title>");
		expect(about.text).toContain("<p>Konten CMS about</p>");

		const consultation = await request(app).get("/formulir-konsultasi");
		expect(consultation.status).toBe(200);
		expect(consultation.text).toContain(
			"<title>CMS formulir-konsultasi</title>",
		);
		expect(consultation.text).toContain(
			"<p>Konten CMS formulir-konsultasi</p>",
		);
	});

	it("beranda menampilkan venue dari data batch", async () => {
		const res = await request(app).get("/").set("Cookie", "locale=id");
		expect(res.text).toContain("Hari istimewa,");
		expect(res.text).toContain("dirancang dengan penuh makna.");
		expect(res.text).toContain(
			"Dari konsep hingga pelaksanaan, setiap detail kami persiapkan untuk mencerminkan cerita dan impian Anda.",
		);
		expect(res.text).toContain('href="#home-planning-summary"');
		expect(res.text).toContain('id="home-planning-summary"');
		expect(res.text).toContain(
			"Persiapan pernikahan terasa lebih ringan saat setiap detail saling terhubung.",
		);
		expect(res.text.indexOf('id="home-planning-summary"')).toBeLessThan(
			res.text.indexOf('id="home-venues"'),
		);
		expect(res.text).toContain('id="home-venues"');
		expect(res.text).toContain("VENUE PILIHAN UNTUK PERAYAAN ANDA");
		expect(res.text).toContain("Pilihan Venue");
		expect(res.text).toContain("untuk Anda");
		expect(res.text).toContain(
			"home-venues-grid grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4",
		);
		expect(res.text).toContain("Venue Uji");
		expect(res.text).toContain("home-venue-card");
		expect(res.text).toContain("home-venue-icon");
		expect(res.text).not.toContain("home-venue-card__description");
		expect(res.text).not.toContain('data-img-error="remove"');
		expect(res.text).not.toContain('href="/classes"');
		expect(res.text).toContain('id="accessibilityWidget"');
		expect(res.text).toContain('aria-controls="accessibilityPanel"');
	});

	it("beranda menyembunyikan konten CMS sebelum konten wedding disetujui", async () => {
		homeWeddingContentReady = false;
		homeSliderBanners = [
			{ posisi: "slider", gambar: "https://example.test/yoga-banner.webp" },
		];
		homeVenues = [{ name: "Yoga Roots Studio", slug: "yoga-roots" }];
		homeArticles = [
			{
				slug: "yoga-article",
				title: "Yoga article",
				featured_image: "/img/yoga.webp",
			},
		];
		homeTestimonials = [
			{ nama: "Yoga Guest", isi_testimoni: "A peaceful yoga class." },
		];

		const res = await request(app).get("/").set("Cookie", "locale=id");

		expect(res.status).toBe(200);
		expect(res.text).not.toContain("Yoga Roots Studio");
		expect(res.text).not.toContain("Yoga article");
		expect(res.text).not.toContain("yoga-banner.webp");
		expect(res.text).not.toContain("/img/yoga.webp");
		expect(res.text).not.toContain("Yoga Guest");
		expect(res.text).not.toContain("Art of Living");
		expect(res.text).not.toContain('id="home-venues"');
		expect(res.text).not.toContain('id="eventsData"');
		expect(res.text).not.toContain('class="home-testimonials-section');
		expect(res.text).toContain('id="home-planning-summary"');
		expect(res.text).toContain(
			'href="#home-planning-summary" class="scroll-indicator',
		);
		expect(res.text).not.toContain('id="home-packages"');
	});

	it("beranda menampilkan maksimal tiga artikel dengan layout kartu yang sama", async () => {
		homeArticles = Array.from({ length: 4 }, (_, index) => ({
			slug: `artikel-${index + 1}`,
			title: `Artikel Pernikahan ${index + 1}`,
			featured_image: `https://example.test/article-${index + 1}.webp`,
			created_at: "2026-11-20 12:00:00",
		}));

		const res = await request(app).get("/").set("Cookie", "locale=id");

		expect(res.status).toBe(200);
		const preparationPosition = res.text.indexOf('id="home-preparation"');
		const articlesPosition = res.text.indexOf('id="home-articles"');
		expect(preparationPosition).toBeGreaterThan(-1);
		expect(preparationPosition).toBeLessThan(articlesPosition);
		expect(res.text).toContain("Rencanakan pernikahan dengan lebih tenang");
		expect(res.text).toContain("Koordinasi vendor");
		expect(res.text).toContain("BACAAN SEPUTAR PERNIKAHAN");
		expect(res.text).toContain("Artikel &amp;");
		expect(res.text).toContain("Inspirasi Pernikahan");
		expect(res.text).toContain('href="/blog/artikel-1"');
		expect(res.text).toContain("https://example.test/article-1.webp");
		expect(res.text).toContain("Baca artikel");
		expect(res.text).toContain('href="/blog"');
		expect(res.text.match(/href="\/blog\/artikel-\d+"/g)).toHaveLength(3);
		expect(res.text).toContain("aspect-[4/5]");
		expect(res.text).not.toContain("Acara &amp;");
		expect(res.text).not.toContain("open-event");
	});

	it("beranda menampilkan tiga paket dan menempatkan paket populer di tengah", async () => {
		homePackages = [
			{
				slug: "favorit",
				name: "Paket Favorit",
				description: "Paket pilihan pasangan.",
				is_popular: true,
				options: [{ final_price: 25000000 }],
				features: [{ feature: "Koordinasi hari pernikahan" }],
			},
			{ slug: "intimate", name: "Paket Intimate", is_popular: false },
			{ slug: "signature", name: "Paket Signature", is_popular: false },
		];

		const res = await request(app).get("/").set("Cookie", "locale=id");
		const intimatePosition = res.text.indexOf(
			'data-home-package-slug="intimate"',
		);
		const popularPosition = res.text.indexOf(
			'data-home-package-slug="favorit"',
		);
		const signaturePosition = res.text.indexOf(
			'data-home-package-slug="signature"',
		);

		expect(res.status).toBe(200);
		expect(res.text).toContain("Paket Pernikahan");
		expect(res.text).toContain("untuk Setiap Impian");
		expect(res.text).toContain("Lihat semua paket");
		expect(res.text).toContain('href="/packages"');
		expect(res.text).toContain("sm:justify-between");
		expect(res.text.match(/<article data-home-package-slug=/g)).toHaveLength(3);
		expect(intimatePosition).toBeLessThan(popularPosition);
		expect(popularPosition).toBeLessThan(signaturePosition);
		expect(res.text).toContain("PALING DIMINATI");
		expect(res.text).toContain("Rp 25.000.000");
		expect(res.text).toContain("Koordinasi hari pernikahan");
		expect(res.text).toContain('href="/packages/favorit"');
		expect(res.text).not.toContain('id="home-approach"');
	});

	it("beranda menampilkan maksimal empat venue", async () => {
		homeVenues = Array.from({ length: 6 }, (_, index) => ({
			uuid: `v${index + 1}`,
			name: `Venue ${index + 1}`,
			slug: `venue-${index + 1}`,
			address: `Jalan ${index + 1}`,
			excerpt: `Deskripsi venue ${index + 1}.`,
		}));

		const res = await request(app).get("/");
		expect(res.status).toBe(200);
		expect(
			res.text.match(/class="home-venue-card group reveal"/g),
		).toHaveLength(4);
		expect(res.text).toContain("Venue 4");
		expect(res.text).not.toContain("Venue 5");
	});

	it("beranda menampilkan skeleton saat data venue dari API belum tersedia", async () => {
		homeVenues = null;

		const res = await request(app).get("/").set("Cookie", "locale=id");
		expect(res.status).toBe(200);
		expect(res.text).toContain('aria-busy="true"');
		expect(res.text).toContain("Memuat venue pilihan...");
		expect(res.text.match(/class="home-venue-skeleton"/g)).toHaveLength(4);
		expect(res.text).not.toContain("Informasi venue segera hadir");
	});

	it("beranda menampilkan pesan kosong setelah API mengembalikan daftar kosong", async () => {
		homeVenues = [];

		const res = await request(app).get("/");
		expect(res.status).toBe(200);
		expect(res.text).toContain("Contact our team and we’ll help you explore");
		expect(res.text).not.toContain("home-venue-skeleton");
	});

	it("beranda menampilkan carousel testimoni setelah paket", async () => {
		homePackages = [
			{ slug: "paket-a", name: "Paket A" },
			{ slug: "paket-b", name: "Paket B", is_popular: true },
			{ slug: "paket-c", name: "Paket C" },
		];
		homeTestimonials = [
			{
				nama: "Alya & Raka",
				jabatan: "Menikah di Jakarta",
				foto: "https://example.test/testimonials/alyaraka.webp",
				isi_testimoni: "Hari kami terasa tenang dan penuh kenangan.",
			},
			{
				nama: "Nadia & Bima",
				jabatan: "Menikah di Bandung",
				foto: null,
				isi_testimoni: "Tim Sagita membantu setiap detail.",
			},
			{
				nama: "Dina & Arif",
				jabatan: null,
				foto: "https://example.test/testimonials/dinaarif.webp",
				isi_testimoni: "Semua berjalan sesuai rencana.",
			},
		];

		const res = await request(app).get("/").set("Cookie", "locale=id");
		expect(res.status).toBe(200);
		expect(
			res.text.indexOf('class="home-testimonials-section'),
		).toBeGreaterThan(res.text.indexOf('id="home-packages"'));
		expect(res.text).toContain("CERITA PERNIKAHAN");
		expect(res.text).toContain("Dengarkan kisah");
		expect(res.text).toContain("pasangan ini");
		expect(res.text).toContain("Alya &amp; Raka");
		expect(res.text).toContain('data-home-testimonial-action="previous"');
		expect(res.text).toContain('data-home-testimonial-action="next"');
		expect(res.text).toContain("Hari kami terasa tenang dan penuh kenangan.");
		expect(res.text).toContain(
			"https://example.test/testimonials/alyaraka.webp",
		);
	});

	it("beranda menampilkan maksimal sepuluh testimoni", async () => {
		homeTestimonials = Array.from({ length: 12 }, (_, index) => ({
			nama: `Pasangan ${index + 1}`,
			jabatan: "Hari istimewa",
			foto: null,
			isi_testimoni: `Cerita ${index + 1}.`,
		}));

		const res = await request(app).get("/");
		expect(res.status).toBe(200);
		expect(res.text.match(/data-home-testimonial-index="\d+"/g)).toHaveLength(
			10,
		);
		expect(res.text).toContain("Pasangan 10");
		expect(res.text).not.toContain("Pasangan 11");
		expect(res.text).not.toContain("Pasangan 12");
	});

	it("beranda menampilkan FAQ ringkas setelah testimoni", async () => {
		const res = await request(app).get("/").set("Cookie", "locale=id");
		expect(res.status).toBe(200);
		const navbar =
			res.text.match(/<header id="navbar"[\s\S]*?<\/header>/)?.[0] || "";
		expect(navbar).not.toContain('href="/faq"');
		expect(res.text).toContain('id="home-faq"');
		expect(res.text.indexOf('id="home-faq"')).toBeGreaterThan(
			res.text.indexOf('class="home-testimonials-section'),
		);
		expect(res.text).not.toContain(
			'<p class="font-sans text-xs uppercase tracking-[0.2em] text-terracotta-600 mb-3">INFORMASI UNTUK ANDA</p>',
		);
		expect(res.text).not.toContain("Ada yang ingin");
		expect(res.text).toContain('data-home-faq-tab="start"');
		expect(res.text).toContain('data-home-faq-tab="packages"');
		expect(res.text).toContain('class="reveal self-start lg:sticky lg:top-24"');
		expect(res.text).toContain('aria-controls="home-faq-group-packages"');
		expect(res.text).toContain('id="home-faq-group-packages"');
		expect(res.text).toContain('id="home-faq-group-start"');
		expect(res.text).toContain('class="home-faq-group reveal scroll-mt-28"');
		expect(res.text).toContain('data-home-faq-group="start"');
		expect(res.text).toContain("data-home-faq-expand");
		expect(res.text).toContain("data-home-faq-collapse");
		expect(res.text).toContain(
			"Kapan sebaiknya kami mulai merencanakan pernikahan?",
		);
		expect(res.text).toContain("Lihat semua FAQ");
	});

	it("beranda hanya menampilkan maksimal lima banner slider valid", async () => {
		homeSliderBanners = [
			...Array.from({ length: 6 }, (_, index) => ({
				posisi: "slider",
				gambar: `https://example.test/slide-${index + 1}.webp`,
			})),
			{ posisi: "slider", gambar: "https://example.test/images/default.png" },
			{
				posisi: "pengumuman",
				gambar: "https://example.test/announcement.webp",
			},
		];

		const res = await request(app).get("/");
		expect(res.status).toBe(200);
		const slideUrls = res.text.match(/data-home-slides="([^"]*)"/)?.[1];
		expect(slideUrls?.match(/slide-\d\.webp/g)).toHaveLength(5);
		expect(slideUrls).not.toContain("slide-6.webp");
		expect(res.text).toContain('fetchpriority="high"');
		expect(res.text).toContain(
			'rel="preload" as="image" href="https://example.test/slide-1.webp"',
		);
		expect(res.text).toContain("data-home-slide-image");
		expect(res.text).toContain(
			'href="#home-planning-summary" class="scroll-indicator',
		);
		expect(res.text).toContain("text-sm font-semibold tracking-[0.18em]");
		expect(res.text).not.toContain("bg-white/90 px-4 py-2");
		expect(res.text).toMatch(
			/aria-label="(?:Hero image controls|Kontrol gambar utama)"/,
		);
		expect(
			res.text.match(/data-home-slide-action="(?:previous|next)"/g),
		).toHaveLength(2);
		expect(res.text).not.toContain("data-home-slide-dot");
		expect(res.text).not.toContain('data-home-slide-action="toggle"');
		expect(res.text).not.toContain(
			'<img src="https://example.test/slide-2.webp"',
		);
		expect(res.text).not.toContain("announcement.webp");
		expect(res.text).not.toContain("images/default.png");
	});

	it("beranda mempertahankan latar fallback saat tidak ada banner slider", async () => {
		const res = await request(app).get("/");
		expect(res.status).toBe(200);
		expect(res.text).not.toContain("data-home-slider");
		expect(res.text).not.toContain('data-home-slide-action="previous"');
		expect(res.text).not.toContain('data-home-slide-action="next"');
		expect(res.text).toContain("radial-gradient");
	});

	it("versi bahasa pada URL memiliki canonical dan hreflang yang dapat dirayapi", async () => {
		const res = await request(app).get("/?lang=id");
		expect(res.status).toBe(200);
		expect(res.headers.location).toBeUndefined();
		expect(res.headers["set-cookie"]?.join(";")).toContain("locale=id");
		expect(res.text).toContain('<html lang="id">');
		expect(res.text).toContain(
			"<title>Wedding Organizer &amp; Paket Pernikahan | Sagita Wedding</title>",
		);
		const canonical = res.text.match(
			/<link rel="canonical" href="([^"]+)"/,
		)?.[1];
		expect(canonical).toMatch(/\/\?lang=id$/);
		expect(res.text).toContain(`hreflang="id" href="${canonical}"`);
		expect(res.text).toMatch(/hreflang="en" href="[^"]+\/"/);
		const structuredData = res.text.match(
			/<script type="application\/ld\+json">([\s\S]*?)<\/script>/,
		)?.[1];
		expect(structuredData).toBeTruthy();
		expect(JSON.parse(structuredData as string)["@graph"]).toEqual(
			expect.arrayContaining([
				expect.objectContaining({ "@type": "Organization" }),
				expect.objectContaining({ "@type": "WebSite" }),
				expect.objectContaining({
					"@type": "WebPage",
					url: canonical,
					inLanguage: "id",
				}),
			]),
		);

		const cookieLocale = await request(app).get("/").set("Cookie", "locale=id");
		expect(cookieLocale.text).toMatch(
			/<link rel="canonical" href="https?:\/\/[^"]+\/\?lang=id"/,
		);
	});

	it("direktori venue menggunakan data batch dan tautan peta", async () => {
		const res = await request(app).get("/venues");
		expect(res.status).toBe(200);
		expect(getPageBatch).toHaveBeenCalledWith("venues", undefined);
		expect(res.text).toContain("Venue Uji");
		expect(res.text).toContain("Jl. Pernikahan");
		expect(res.text).toContain("https://www.google.com/maps/search/");
		expect(res.text).toContain('id="venueSearch"');
		expect(res.text).toContain('id="venueList"');
		expect(res.text).toContain('data-singular="venue to explore"');
		expect(res.text).toContain("pages/venues.js");
		expect(res.text).toContain("data-venue-item");
	});

	it("paket yang tidak ditemukan tidak dapat diindeks", async () => {
		const res = await request(app).get("/packages/not-a-package");
		expect(res.status).toBe(404);
		expect(res.text).toMatch(
			/<meta\s+name="robots"\s+content="noindex, nofollow"\s*\/?>/,
		);
	});

	it("widget aksesibilitas dirender dengan label bahasa Indonesia", async () => {
		const res = await request(app).get("/").set("Cookie", "locale=id");
		expect(res.status).toBe(200);
		expect(res.text).toContain("Aksesibilitas");
		expect(res.text).toContain("Kontras tinggi");
		expect(res.text).toContain('aria-label="Buka pengaturan aksesibilitas"');
	});

	it("URL kelas dan jadwal lama diarahkan ke direktori venue", async () => {
		for (const path of ["/classes", "/classes/yoga", "/schedules"]) {
			const res = await request(app).get(path);
			expect(res.status, path).toBe(301);
			expect(res.headers.location, path).toBe("/venues");
		}
	});

	it("halaman About menampilkan layanan wedding planner dan SEO yang sesuai", async () => {
		const response = await request(app).get("/about");
		expect(response.status).toBe(200);
		expect(response.text).toContain("About Sagita Wedding Planner");
		expect(response.text).toContain('data-hero-overlay="true"');
		expect(response.text).toContain("Vendor coordination");
		expect(response.text).toContain(
			"<title>About Sagita Wedding Planner | Wedding Organizer</title>",
		);
		expect(response.text).toContain(
			'name="description" content="Meet Sagita Wedding Planner, a wedding organizer helping you plan your concept, budget, vendors, and wedding-day coordination around your story."',
		);
		expect(response.text).toContain("-about-wedding-icons1");
		const structuredData = response.text.match(
			/<script type="application\/ld\+json">([\s\S]*?)<\/script>/,
		)?.[1];
		expect(JSON.parse(structuredData as string)["@graph"]).toEqual(
			expect.arrayContaining([
				expect.objectContaining({ "@type": "AboutPage" }),
			]),
		);

		const indonesianResponse = await request(app).get("/about?lang=id");
		expect(indonesianResponse.status).toBe(200);
		expect(indonesianResponse.text).toContain(
			"<title>Tentang Sagita Wedding Planner | Wedding Organizer</title>",
		);
		expect(indonesianResponse.text).toContain(
			'name="description" content="Kenali Sagita Wedding Planner, wedding organizer yang membantu menyusun konsep, anggaran, vendor, dan koordinasi hari pernikahan sesuai cerita Anda."',
		);
		expect(indonesianResponse.text).toContain("Koordinasi vendor");
		expect(indonesianResponse.text).toContain("Keunggulan Wedding Organizer");

		const oldUrl = await request(app).get("/art-of-living");
		expect(oldUrl.status).toBe(301);
		expect(oldUrl.headers.location).toBe("/about");

		const home = await request(app).get("/");
		expect(home.text).toContain('href="/about"');
		expect(home.text).not.toContain('href="/art-of-living"');
	});
});

/* --------------------------- CSP regression -------------------------- */

describe("regresi CSP: tidak ada inline script tanpa nonce", () => {
	it("halaman yang dulu punya inline script tetap aman", async () => {
		for (const path of ["/", "/venues", "/blog", "/faq"]) {
			const res = await request(app).get(path);
			const scripts = [...res.text.matchAll(/<script\b([^>]*)>/gi)];
			for (const [, attrs] of scripts) {
				if (/src=/.test(attrs)) continue; // external 'self' diizinkan
				if (/type=["']?application\/(ld\+)?json/i.test(attrs)) continue;
				expect(attrs, `inline script tanpa nonce di ${path}`).toMatch(/nonce=/);
			}
		}
	});
});

/* ------------------------------- 404 --------------------------------- */

describe("not found", () => {
	it("halaman tak dikenal merender 404", async () => {
		const res = await request(app).get("/halaman-yang-tidak-ada-xyz");
		expect(res.status).toBe(404);
	});

	it("404 berbahasa Indonesia menampilkan CTA konsultasi dan footer yang sesuai", async () => {
		const res = await request(app)
			.get("/vendorsdasd")
			.set("Cookie", "locale=id");

		expect(res.status).toBe(404);
		expect(res.text).toContain("Mencari halaman yang tepat?");
		expect(res.text).toContain("Mulai konsultasi");
		expect(res.text).toContain('href="/formulir-konsultasi"');
		expect(res.text).toContain("Deskripsi uji.");
		expect(res.text).toContain("Sagita Wedding");
		expect(res.text).not.toContain("Serene Yoga");
	});

	it("API tak dikenal membalas JSON 404", async () => {
		const res = await request(app).get("/api/entah");
		expect(res.status).toBe(404);
	});

	it("endpoint class/schedule lama membalas 410, bukan 404 atau 502", async () => {
		for (const path of [
			"/api/classes",
			"/api/class-schedules",
			"/api/batch/schedules",
		]) {
			const res = await request(app).get(path);
			expect(res.status, path).toBe(410);
			expect(res.body.success).toBe(false);
		}
	});
});
