import { describe, expect, it } from "vitest";
import {
	getHomeFaqs,
	localizeFaqCategories,
	localizeFaqs,
} from "../src/lib/data/faqContent.js";

const faq = {
	uuid: "4b9411a3-ccc5-44fd-bc24-e8dc7065773c",
	kategori: "Perencanaan Pernikahan",
	question: "When should we start planning our wedding?",
	answer:
		"We recommend beginning 6–12 months before your wedding day. This gives you time to choose a date, venue, and concept, set a budget, and secure your preferred vendors at a comfortable pace.",
};

describe("FAQ localization", () => {
	it("memakai copy seed lokal untuk bahasa Indonesia", () => {
		expect(localizeFaqs([faq], "id")).toMatchObject([
			{
				kategori: "Perencanaan Pernikahan",
				question: "Kapan sebaiknya kami mulai merencanakan pernikahan?",
			},
		]);
	});

	it("memakai copy Inggris hanya untuk teks seed", () => {
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

	it("mengutamakan perubahan admin pada UUID yang punya copy seed", () => {
		const edited = {
			...faq,
			kategori: "Kategori Admin",
			question: "Pertanyaan baru dari admin?",
			answer: "Jawaban baru dari admin.",
		};
		expect(localizeFaqs([edited], "id")).toEqual([edited]);
		expect(localizeFaqs([edited], "en")).toEqual([edited]);
	});

	it("membangun FAQ beranda dari data API", () => {
		const groups = getHomeFaqs(
			[
				faq,
				{
					uuid: "admin-1",
					kategori: "Custom category",
					question: "Pertanyaan admin?",
					answer: "Jawaban admin.",
				},
			],
			"en",
		);
		expect(groups.map((group) => group.id)).toEqual([
			"start",
			"custom-category",
		]);
		expect(groups[0].items[0].question).toBe(
			"When should we start planning our wedding?",
		);
		expect(groups[1].items[0].answer).toBe("Jawaban admin.");
	});

	it("menjaga FAQ di luar daftar seed apa adanya", () => {
		const customFaq = {
			uuid: "custom",
			kategori: "Custom category",
			question: "Custom question?",
			answer: "Custom answer.",
		};
		expect(localizeFaqs([customFaq], "id")).toEqual([customFaq]);
	});

	it("memilih kebijakan sesuai bahasa tanpa mencampur FAQ bilingual", () => {
		const policies = [
			{
				uuid: "policy-id",
				locale: "id",
				kategori: "Pemesanan & Pembayaran",
				question: "Bagaimana cara booking?",
				answer: "Jawaban Indonesia.",
			},
			{
				uuid: "policy-en",
				locale: "en",
				kategori: "Pemesanan & Pembayaran",
				question: "How do I book?",
				answer: "English answer.",
			},
		];

		expect(localizeFaqs(policies, "id")).toMatchObject([
			{ question: "Bagaimana cara booking?" },
		]);
		expect(localizeFaqs(policies, "en")).toMatchObject([
			{ question: "How do I book?" },
		]);
	});
});
