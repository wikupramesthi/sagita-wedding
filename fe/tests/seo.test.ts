import request from "supertest";
import { beforeEach, describe, expect, it, vi } from "vitest";

const { apiRequestMock } = vi.hoisted(() => ({ apiRequestMock: vi.fn() }));
vi.mock("../src/lib/services/apiClient.js", () => ({
	default: apiRequestMock,
}));

vi.mock("../src/lib/services/siteIdentityService.js", () => ({
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

vi.mock("../src/lib/services/siteStatsService.js", () => ({
	formatStatCount: (value: unknown) => String(value ?? 0),
}));

const getPageBatch = vi.fn(
	async (page: string, params?: Record<string, unknown>) => {
		switch (page) {
			case "home":
				return {
					weddingContentReady: false,
					sliderBanners: [],
					venues: [],
					packages: [],
					articles: [],
					testimonials: [],
				};
			case "venues":
				return { venues: [] };
			case "packages":
				return { packages: [], meta: {} };
			case "package-detail":
				return {
					package: {
						name: "Paket Uji",
						slug: "paket-uji",
						description: "Paket pernikahan untuk uji SEO.",
						options: [],
					},
				};
			case "events":
				return { events: [], meta: {} };
			case "gallery":
				return { gallery: [] };
			case "vendors":
				return { vendors: [] };
			case "blog":
				return { articles: [], total: 0 };
			case "blog-detail":
				return {
					post: {
						slug: "panduan-venue",
						title: "Panduan Memilih Venue",
						featured_image: "https://example.test/cover.webp",
						scheduled_at: "2026-11-22T08:00:00Z",
						content: "<p>Isi artikel.</p>",
						excerpt: "Ringkasan artikel.",
						category: "Venue",
					},
					related: [],
					popular: [],
					venues: [],
				};
			case "page-detail":
				if (params?.slug !== "tentang") return {};
				return {
					page: {
						title: "Tentang Sagita",
						slug: "tentang",
						content: "<p>Halaman uji.</p>",
					},
				};
			case "faq":
				return { faqs: [], categories: [] };
			case "about":
				return { stats: null, articles: [] };
			case "contact":
				return { faqs: [] };
			default:
				return {};
		}
	},
);

vi.mock("../src/lib/services/batchService.js", () => ({
	getPageBatch: (page: string, params?: Record<string, unknown>) =>
		getPageBatch(page, params),
}));

const { createApp } = await import("./helpers/createApp.js");
const app = createApp();

const SEO_ROUTES = [
	"/",
	"/venues",
	"/formulir-konsultasi",
	"/about",
	"/vendors",
	"/blog",
	"/blog/panduan-venue",
	"/packages",
	"/packages/paket-uji",
	"/event",
	"/portofolio",
	"/faq",
	"/pages/tentang",
];

beforeEach(() => {
	vi.clearAllMocks();
	apiRequestMock.mockImplementation(async (path: string) => {
		const page = Number(
			new URL(`http://localhost${path}`).searchParams.get("page") ?? 1,
		);
		if (path.startsWith("/packages?"))
			return page === 1
				? { data: [{ slug: "paket-uji" }], meta: { last_page: 2 } }
				: { data: [{ slug: "paket-uji-2" }], meta: { last_page: 2 } };
		if (path.startsWith("/articles")) return [{ slug: "panduan-venue" }];
		if (path.startsWith("/pages")) return [{ slug: "tentang" }];
		return [];
	});
});

describe("audit SEO lintas halaman", () => {
	it("setiap halaman publik punya metadata, canonical, hreflang, OG, JSON-LD, dan satu H1", async () => {
		for (const route of SEO_ROUTES) {
			const res = await request(app).get(route).set("Cookie", "locale=id");
			expect(res.status, route).toBe(200);

			const html = res.text;
			expect(html, `${route} title`).toMatch(/<title>[^<]{5,}<\/title>/);
			expect(html, `${route} description`).toMatch(
				/<meta name="description" content="[^"]{20,}"/,
			);
			expect(html, `${route} canonical`).toMatch(
				/<link rel="canonical" href="https?:\/\//,
			);
			expect(html, `${route} hreflang x-default`).toContain(
				'hreflang="x-default"',
			);
			expect(html, `${route} hreflang id`).toContain('hreflang="id"');
			expect(html, `${route} og:title`).toContain('property="og:title"');
			expect(html, `${route} og:image`).toContain('property="og:image"');
			expect(html, `${route} JSON-LD`).toContain('type="application/ld+json"');
			expect(html, `${route} Organization`).toContain('"@type":"Organization"');
			expect(html, `${route} WebSite`).toContain('"@type":"WebSite"');

			const h1Count = html.match(/<h1[\s>]/g)?.length ?? 0;
			expect(h1Count, `${route} jumlah H1`).toBe(1);

			const images = html.match(/<img\b[^>]*>/g) ?? [];
			const missingAlt = images.filter((tag) => !/\balt=/.test(tag));
			expect(missingAlt, `${route} alt gambar`).toEqual([]);
		}
	});

	it("title dan description unique lintas halaman", async () => {
		const titles = new Map<string, string>();
		const descriptions = new Map<string, string>();

		for (const route of SEO_ROUTES) {
			const res = await request(app).get(route).set("Cookie", "locale=id");
			const title = /<title>([^<]+)<\/title>/.exec(res.text)?.[1] ?? "";
			const description =
				/<meta name="description" content="([^"]+)"/.exec(res.text)?.[1] ?? "";
			expect(titles.get(title), `judul duplikat: ${title}`).toBeUndefined();
			expect(
				descriptions.get(description),
				`deskripsi duplikat: ${description}`,
			).toBeUndefined();
			titles.set(title, route);
			descriptions.set(description, route);
		}
	});

	it("sitemap dan robots mencakup halaman statis serta slug dinamis", async () => {
		const [sitemap, robots] = await Promise.all([
			request(app).get("/sitemap.xml"),
			request(app).get("/robots.txt"),
		]);

		expect(sitemap.status).toBe(200);
		expect(sitemap.headers["content-type"]).toContain("xml");
		expect(sitemap.text).toContain("<loc>http://localhost:3000/</loc>");
		expect(sitemap.text).toContain("<loc>http://localhost:3000/blog</loc>");
		expect(sitemap.text).toContain(
			"<loc>http://localhost:3000/packages/paket-uji</loc>",
		);
		expect(sitemap.text).toContain(
			"<loc>http://localhost:3000/packages/paket-uji-2</loc>",
		);
		expect(sitemap.text).toContain(
			"<loc>http://localhost:3000/blog/panduan-venue</loc>",
		);
		expect(sitemap.text).toContain(
			"<loc>http://localhost:3000/pages/tentang</loc>",
		);

		expect(robots.status).toBe(200);
		expect(robots.text).toContain("User-agent: *");
		expect(robots.text).toContain("Sitemap: http://localhost:3000/sitemap.xml");
	});
});
