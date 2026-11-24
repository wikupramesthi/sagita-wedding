import zlib from "node:zlib";
import request from "supertest";
import { beforeAll, describe, expect, it, vi } from "vitest";

if (typeof zlib.createZstdDecompress !== "function") {
	zlib.createZstdDecompress = () => {
		throw new Error("zstd decompression is unavailable in this Node runtime");
	};
}

const { JSDOM } = await import("jsdom");

const { apiRequestMock } = vi.hoisted(() => ({ apiRequestMock: vi.fn() }));
vi.mock("../src/services/apiClient.js", () => ({ default: apiRequestMock }));

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
		logoUrl: "/img/logo.WebP",
		logoWhiteUrl: "/img/logo-white.webp",
	}),
}));

vi.mock("../src/services/batchService.js", () => ({
	getPageBatch: async (page: string) => {
		switch (page) {
			case "home":
				return {
					weddingContentReady: true,
					sliderBanners: [{ posisi: "slider", gambar: "/img/hero.webp" }],
					venues: [
						{
							uuid: "v1",
							name: "Venue Uji",
							slug: "venue-uji",
							address: "Jl. Uji",
							excerpt: "Venue untuk uji aksesibilitas.",
							image: "/img/venue.webp",
						},
					],
					packages: [],
					articles: [],
					testimonials: [],
					faqs: [],
				};
			case "venues":
				return {
					venues: [
						{
							uuid: "v1",
							name: "Venue Uji",
							slug: "venue-uji",
							address: "Jl. Uji",
							excerpt: "Venue untuk uji aksesibilitas.",
							image: "/img/venue.webp",
						},
					],
				};
			case "faq":
			case "contact":
				return { faqs: [], categories: [] };
			case "blog":
				return { articles: [], total: 0 };
			case "packages":
				return { packages: [], meta: {} };
			case "page-detail":
				return {
					page: {
						slug: "cms",
						title: "Halaman CMS",
						content: "<p>Konten CMS</p>",
					},
				};
			default:
				return {};
		}
	},
}));

vi.mock("../src/services/siteStatsService.js", () => ({
	formatStatCount: (value: unknown) => String(value ?? 0),
}));

const { createApp } = await import("../src/app.js");
const axe = await import("axe-core");

type AxeResults = {
	violations: {
		id: string;
		help: string;
		nodes: { target: string[] }[];
	}[];
};

type AxeWindow = Window & {
	eval: (source: string) => void;
	axe: { run: (root: Document) => Promise<AxeResults> };
};

const app = createApp();
const pages = [
	"/",
	"/about",
	"/formulir-konsultasi",
	"/faq",
	"/venues",
	"/packages",
	"/blog",
];

describe("aksesibilitas halaman", () => {
	beforeAll(() => {
		apiRequestMock.mockResolvedValue({ message: "ok", duplicate: false });
	});

	for (const path of pages) {
		it(`${path} lolos audit axe WCAG A/AA`, async () => {
			const res = await request(app).get(path);
			expect(res.status).toBe(200);

			const dom = new JSDOM(res.text, {
				url: `http://localhost:3000${path}`,
				runScripts: "outside-only",
				pretendToBeVisual: true,
			});
			const win = dom.window as unknown as AxeWindow;
			win.eval(axe.default.source);
			const results = await win.axe.run(win.document);
			const violations = results.violations.map((violation) => {
				const targets = violation.nodes
					.map((node) => node.target.join(" "))
					.join(", ");
				return `${violation.id} (${violation.nodes.length}): ${violation.help} → ${targets}`;
			});

			dom.window.close();
			expect(violations).toEqual([]);
		});
	}
});
