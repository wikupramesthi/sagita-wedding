import { describe, expect, it } from "vitest";
import { localizeFaqCategories, localizeFaqs } from "../src/data/faqContent.js";

const faq = {
	uuid: "4b9411a3-ccc5-44fd-bc24-e8dc7065773c",
	kategori: "Perencanaan Pernikahan",
	question: "When should we start planning our wedding?",
	answer: "Ideally, start 6–12 months before the big day.",
};

describe("FAQ localization", () => {
	it("shows polished wedding FAQ copy in Indonesian", () => {
		expect(localizeFaqs([faq], "id")).toMatchObject([
			{
				kategori: "Perencanaan Pernikahan",
				question: "Kapan sebaiknya kami mulai merencanakan pernikahan?",
			},
		]);
	});

	it("uses English categories and copy for English visitors", () => {
		expect(localizeFaqs([faq], "en")).toMatchObject([
			{
				kategori: "Wedding Planning",
				question: "When should we start planning our wedding?",
				answer: expect.stringContaining("wedding day"),
			},
		]);
		expect(
			localizeFaqCategories(["Paket & Layanan", "Custom category"], "en"),
		).toEqual(["Packages & Services", "Custom category"]);
	});

	it("preserves FAQ entries not covered by the built-in translations", () => {
		const customFaq = {
			uuid: "custom",
			kategori: "Custom category",
			question: "Custom question?",
			answer: "Custom answer.",
		};
		expect(localizeFaqs([customFaq], "id")).toEqual([customFaq]);
	});
});
