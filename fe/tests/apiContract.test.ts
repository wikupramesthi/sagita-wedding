import { describe, expect, it } from "vitest";
import {
	assertBatchPagesMatchContract,
	BATCH_CONTRACT,
	FAQ_ITEM_CONTRACT,
	IDENTITY_CONTRACT,
	missingBatchKeys,
	missingIdentityKeys,
	missingItemKeys,
	VENUE_ITEM_CONTRACT,
} from "../src/services/apiContract.js";
import { SUPPORTED_BATCH_PAGES } from "../src/services/batchService.js";

describe("API contract", () => {
	it("only exposes batch pages implemented by the backend", () => {
		expect(SUPPORTED_BATCH_PAGES).toEqual([
			"home",
			"venues",
			"about",
			"blog",
			"blog-detail",
			"packages",
			"package-detail",
			"vendors",
			"events",
			"gallery",
			"contact",
			"faq",
			"page-detail",
		]);
		assertBatchPagesMatchContract();
	});

	it("defines every batch page consumed by the frontend", () => {
		for (const page of SUPPORTED_BATCH_PAGES) {
			expect(BATCH_CONTRACT[page].length).toBeGreaterThan(0);
		}
	});

	it("detects missing batch fields", () => {
		const home = {
			weddingContentReady: true,
			sliderBanners: [],
			testimonials: [],
			venues: [],
			packages: [],
			articles: [],
			faqs: [],
		};
		expect(missingBatchKeys("home", home)).toEqual([]);
		expect(missingBatchKeys("home", { ...home, faqs: undefined })).toEqual([
			"faqs",
		]);
	});

	it("detects missing website identity fields", () => {
		const identity = Object.fromEntries(
			IDENTITY_CONTRACT.map((key) => [key, ""]),
		);
		expect(missingIdentityKeys(identity)).toEqual([]);
		const withoutLogoWhite = { ...identity };
		delete withoutLogoWhite.logo_white_url;
		expect(missingIdentityKeys(withoutLogoWhite)).toEqual(["logo_white_url"]);
	});

	it("detects missing nested item fields", () => {
		const faq = {
			uuid: "1",
			kategori: "Paket & Layanan",
			question: "Q?",
			answer: "A.",
		};
		const venue = {
			uuid: "1",
			name: "Venue",
			slug: "venue",
			excerpt: "",
			address: "",
			google_maps_url: "",
			image: null,
		};
		expect(missingItemKeys([faq], FAQ_ITEM_CONTRACT)).toEqual([]);
		expect(missingItemKeys([venue], VENUE_ITEM_CONTRACT)).toEqual([]);
		expect(
			missingItemKeys([{ ...faq, answer: undefined }], FAQ_ITEM_CONTRACT),
		).toEqual(["answer"]);
	});
});
