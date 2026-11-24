import { describe, expect, it } from "vitest";
import {
	detectTopics,
	hasFuzzyToken,
	normalizeNluText,
	smallTalkKind,
	withinEditDistance,
} from "../src/services/sitaNlu.js";

describe("Sita local NLU", () => {
	it("normalizes case, accents, and punctuation", () => {
		expect(normalizeNluText("  Halo, SITA!  ")).toBe("halo sita");
		expect(normalizeNluText("Pröst? Paket.")).toBe("prost paket");
	});

	it("reads slang and typos as topics", () => {
		expect(detectTopics("brp hrga pket").primary).toBe("packages");
		expect(detectTopics("venu dong").primary).toBe("venues");
		expect(detectTopics("catring vendor").primary).toBe("vendors");
	});

	it("prefers the strong topic over price words", () => {
		const analysis = detectTopics("harga venue");
		expect(analysis.primary).toBe("venues");
		expect(analysis.secondary).toBeUndefined();
	});

	it("keeps a strong secondary topic for multi-intent questions", () => {
		const mixed = detectTopics("paket sama venue");
		expect(mixed.primary).toBe("packages");
		expect(mixed.secondary).toBe("venues");
	});

	it("breaks score ties with the first strong word", () => {
		expect(detectTopics("venue paket").primary).toBe("venues");
		expect(detectTopics("paket venue").primary).toBe("packages");
	});

	it("matches Indonesian possessive endings", () => {
		expect(detectTopics("paketnya dong").primary).toBe("packages");
		expect(detectTopics("harganya berapa").primary).toBe("packages");
	});

	it("reports no topic for unrelated words", () => {
		expect(
			detectTopics("Berapa jumlah jerapah di bulan?").primary,
		).toBeUndefined();
		expect(detectTopics("galeri foto").primary).toBeUndefined();
	});

	it("detects greetings and thanks but not chit-chat with real words", () => {
		expect(smallTalkKind("Halo")).toBe("greeting");
		expect(smallTalkKind("halo mau tanya dong")).toBe("greeting");
		expect(smallTalkKind("makasih banyak")).toBe("thanks");
		expect(smallTalkKind("halo paket")).toBeNull();
		expect(smallTalkKind("halo cara booking")).toBeNull();
		expect(smallTalkKind("halo 50 juta")).toBeNull();
	});

	it("fuzzy-matches short typos within one edit", () => {
		expect(hasFuzzyToken("pket", ["paket"])).toBe(true);
		expect(hasFuzzyToken("hrga", ["harga"])).toBe(true);
		expect(hasFuzzyToken("venu", ["venue"])).toBe(true);
		expect(withinEditDistance("pket", "paket", 1)).toBe(true);
		expect(withinEditDistance("jerapah", "paket", 1)).toBe(false);
		expect(hasFuzzyToken("jumlah", ["hubungi", "booking"])).toBe(false);
	});

	it("understands English packages and venue wording", () => {
		expect(detectTopics("how much for the packages?").primary).toBe("packages");
		expect(detectTopics("venue price").primary).toBe("venues");
		expect(detectTopics("wedding vendors").primary).toBe("vendors");
		expect(detectTopics("photographer for the wedding").primary).toBe(
			"vendors",
		);
	});

	it("keeps English small talk out of no-match fallback", () => {
		expect(smallTalkKind("hello")).toBe("greeting");
		expect(smallTalkKind("how are you")).toBe("greeting");
		expect(smallTalkKind("thanks a lot")).toBe("thanks");
		expect(smallTalkKind("how much is the package")).toBeNull();
	});

	it("uses aliases from the database to detect topics", () => {
		const aliases = { vendors: ["fotobooth", "booth"] };

		expect(detectTopics("fotobooth dong", aliases).primary).toBe("vendors");
		expect(detectTopics("fotobooth dong").primary).toBeUndefined();
	});

	it("does not let a dynamic alias override a built-in strong topic", () => {
		const aliases = { packages: ["harga"] };

		expect(detectTopics("Harga venue", aliases).primary).toBe("venues");
	});
});
