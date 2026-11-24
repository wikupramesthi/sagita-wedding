import { afterEach, describe, expect, it, vi } from "vitest";
import {
	clearSitaKnowledgeCache,
	getDynamicAliases,
	getSitaKnowledge,
} from "../src/lib/services/sitaKnowledge.js";
import { detectTopics } from "../src/lib/services/sitaNlu.js";

const synonyms = [
	{
		locale: "id",
		term: "fotobooth",
		canonical: "fotografi",
		topic: "vendors",
		is_active: true,
	},
	{
		locale: "id",
		term: "gedungku",
		canonical: "venue",
		topic: "venues",
		is_active: true,
	},
	{
		locale: "id",
		term: "jerapah",
		canonical: "hewan",
		topic: "packages",
		is_active: false,
	},
	{
		locale: "en",
		term: "photobooth",
		canonical: "fotografi",
		topic: "vendors",
		is_active: true,
	},
];

function stubKnowledge(payload: unknown) {
	vi.stubGlobal(
		"fetch",
		vi.fn().mockResolvedValue({
			ok: true,
			json: async () => payload,
		}),
	);
}

describe("Sita knowledge dari backend", () => {
	afterEach(() => {
		vi.unstubAllGlobals();
		clearSitaKnowledgeCache();
	});

	it("mengubah deteksi topik memakai sinonim aktif dari backend", async () => {
		stubKnowledge({ success: true, data: synonyms });

		const knowledge = await getSitaKnowledge("id");

		expect(knowledge.topics.vendors).toContain("fotobooth");
		expect(knowledge.topics.venues).toContain("gedungku");
		expect(detectTopics("fotobooth dong", knowledge.topics).primary).toBe(
			"vendors",
		);
		expect(detectTopics("fotobooth dong").primary).toBeUndefined();
	});

	it("mengabaikan sinonim nonaktif dan locale lain", async () => {
		stubKnowledge({ success: true, data: synonyms });

		const knowledge = await getSitaKnowledge("id");

		expect(knowledge.topics.packages).toBeUndefined();
		expect(knowledge.topics.vendors).not.toContain("photobooth");
	});

	it("memakai daftar alias topik dan bentuk baku yang lebih lengkap", async () => {
		const extendedSynonyms = Array.from({ length: 40 }, (_, index) => ({
			locale: "id",
			term: `photoboothalias${index}`,
			canonical: "fotografi",
			topic: "vendors",
			is_active: true,
		}));
		stubKnowledge({ success: true, data: extendedSynonyms });

		const knowledge = await getSitaKnowledge("id");

		expect(knowledge.topics.vendors).toContain("photoboothalias39");
		expect(knowledge.aliases.fotografi).toContain("photoboothalias39");
	});

	it("tetap memakai alias bawaan saat backend gagal", async () => {
		vi.stubGlobal("fetch", vi.fn().mockRejectedValue(new Error("offline")));

		const knowledge = await getSitaKnowledge("id");

		expect((await getDynamicAliases("id")).paket).toContain("paket");
		expect(knowledge.topics).toEqual({});
	});
});
