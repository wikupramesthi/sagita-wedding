import { describe, expect, it } from "vitest";
import { parseTelemetry } from "../src/routes/api.js";
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
			locale: "id",
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

describe("Sita telemetry allowlist", () => {
	it("keeps no-match events", () => {
		expect(parseTelemetry({ kind: "event", name: "sita.no_match" })).toEqual({
			kind: "event",
			name: "sita.no_match",
			path: "/",
		});
	});

	it("keeps privacy-safe no-match metadata", () => {
		expect(
			parseTelemetry({
				kind: "event",
				name: "sita.no_match",
				topic: "packages",
				locale: "id",
				queryHash: "abc123",
				message: "rahasia",
			}),
		).toEqual({
			kind: "event",
			name: "sita.no_match",
			path: "/",
			topic: "packages",
			locale: "id",
			queryHash: "abc123",
		});
		expect(
			parseTelemetry({
				kind: "event",
				name: "sita.no_match",
				queryHash: "bukan-hash",
			}),
		).toEqual({ kind: "event", name: "sita.no_match", path: "/" });
	});

	it("accepts answer metrics without question text", () => {
		expect(
			parseTelemetry({
				kind: "event",
				name: "sita.answer",
				topic: "vendors",
				confidence: 0.15,
				fallback: true,
				clarify: false,
				responseMs: 342,
				turnId: "turn-4",
				answer: "rahasia",
				locale: "id",
			}),
		).toEqual({
			kind: "event",
			name: "sita.answer",
			path: "/",
			topic: "vendors",
			locale: "id",
			confidence: 0.15,
			fallback: true,
			clarify: false,
			responseMs: 342,
			turnId: "turn-4",
		});
		expect(
			parseTelemetry({
				kind: "event",
				name: "sita.answer",
				confidence: 2,
				fallback: false,
				clarify: false,
				responseMs: 10,
			}),
		).toBeNull();
	});

	it("accepts feedback events with a boolean rating and opaque metadata", () => {
		expect(
			parseTelemetry({
				kind: "event",
				name: "sita.feedback",
				helpful: true,
				turnId: "turn-123",
				topic: "vendors",
				locale: "en",
				answer: "rahasia",
			}),
		).toEqual({
			kind: "event",
			name: "sita.feedback",
			path: "/",
			helpful: true,
			turnId: "turn-123",
			topic: "vendors",
			locale: "en",
		});
		expect(
			parseTelemetry({
				kind: "event",
				name: "sita.feedback",
				helpful: "yes",
			}),
		).toBeNull();
		expect(
			parseTelemetry({
				kind: "event",
				name: "sita.feedback",
				helpful: false,
				topic: "unknown",
				locale: "fr",
			}),
		).toEqual({
			kind: "event",
			name: "sita.feedback",
			path: "/",
			helpful: false,
		});
		expect(
			parseTelemetry({ kind: "event", name: "sita.secret", helpful: true }),
		).toBeNull();
	});
});
