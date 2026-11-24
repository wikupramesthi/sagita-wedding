import { describe, expect, it } from "vitest";
import {
	SEMANTIC_MATCH_THRESHOLD,
	semanticScore,
	semanticScores,
} from "../src/lib/services/sitaSemantic.js";

describe("Sita local semantic retrieval", () => {
	it("matches a package price paraphrase", () => {
		const match = semanticScore("harga paket mulai berapa", [
			"Berapa biaya paket pernikahan?",
		]);

		expect(match).not.toBeNull();
		expect(match?.index).toBe(0);
		expect(match?.score ?? 0).toBeGreaterThanOrEqual(SEMANTIC_MATCH_THRESHOLD);
	});

	it("matches a booking paraphrase", () => {
		const match = semanticScore("cara daftar", ["Bagaimana cara booking?"]);

		expect(match).not.toBeNull();
		expect(match?.score ?? 0).toBeGreaterThanOrEqual(SEMANTIC_MATCH_THRESHOLD);
	});

	it("rejects unrelated yoga and testimonial content", () => {
		const candidates = [
			"Testimoni klien: wedding planner membuat acara berjalan lancar.",
			"Kelas yoga pagi dengan biaya membership bulanan.",
		];

		const scores = semanticScores("harga paket mulai berapa", candidates);
		expect(scores.every((score) => score < SEMANTIC_MATCH_THRESHOLD)).toBe(
			true,
		);
		expect(semanticScore("cara daftar", candidates)).toBeNull();
	});
});
