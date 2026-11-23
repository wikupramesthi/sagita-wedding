import { beforeEach, describe, expect, it, vi } from "vitest";

vi.mock("../src/services/batchService.js", () => ({
	getPageBatch: vi.fn(),
}));

import { getPageBatch } from "../src/services/batchService.js";
import { answerSitaQuestion } from "../src/services/sitaService.js";

const getPageBatchMock = vi.mocked(getPageBatch);

describe("Sita database answers", () => {
	beforeEach(() => {
		getPageBatchMock.mockReset();
	});

	it("shows package facts and prices from the Sagita catalog", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Wedding Mawar",
					slug: "paket-wedding-mawar",
					description: "Paket pernikahan di gedung",
					cover_url: "https://cdn.example.test/mawar.jpg",
					guest_capacity: 300,
					options: [
						{ final_price: 25000000, price: 30000000 },
						{ final_price: 45000000, price: 45000000 },
					],
				},
			],
		});

		const result = await answerSitaQuestion("Paket wedding", "id");

		expect(result.answer).toContain("katalog Sagita");
		expect(result.cards).toHaveLength(1);
		expect(result.cards[0].description).toContain("25.000.000");
		expect(result.cards[0].description).toContain("300");
		expect(result.cards[0].image).toBe("https://cdn.example.test/mawar.jpg");
		expect(result.cards[0].actions).toEqual([
			{ label: "Lihat paket", href: "/packages/paket-wedding-mawar" },
			{ label: "Galeri", href: "/portofolio" },
		]);
		expect(result.offerContact).toBe(true);
	});

	it("uses package context to filter by budget and recorded guest capacity", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Mawar",
					slug: "paket-mawar",
					description: "Dekorasi klasik.",
					guest_capacity: 100,
					options: [{ final_price: 30_000_000 }],
				},
				{
					name: "Paket Melati",
					slug: "paket-melati",
					description: "Dekorasi modern.",
					guest_capacity: 300,
					options: [{ final_price: 45_000_000 }],
				},
				{
					name: "Paket Anggrek",
					slug: "paket-anggrek",
					description: "Dekorasi mewah.",
					guest_capacity: 500,
					options: [{ final_price: 60_000_000 }],
				},
			],
		});

		const result = await answerSitaQuestion(
			"yang budget 50 juta untuk 250 tamu",
			"id",
			{ topic: "packages" },
		);

		expect(result.cards.map((card) => card.title)).toEqual(["Paket Melati"]);
	});

	it("reuses structured package criteria for a follow-up question", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Melati",
					slug: "paket-melati",
					description: "Paket pernikahan.",
					guest_capacity: 300,
					options: [{ final_price: 45_000_000 }],
				},
				{
					name: "Paket Anggrek",
					slug: "paket-anggrek",
					description: "Paket pernikahan.",
					guest_capacity: 500,
					options: [{ final_price: 60_000_000 }],
				},
			],
		});

		const result = await answerSitaQuestion("yang cocok", "id", {
			topic: "packages",
			criteria: { budgetLimit: 50_000_000, guestCount: 250 },
		});

		expect(result.cards.map((card) => card.title)).toEqual(["Paket Melati"]);
		expect(result.answer).toMatch(/anggaran maksimal Rp\s?50\.000\.000/);
		expect(result.answer).toContain("kapasitas minimal 250 tamu");
	});

	it("compares package prices and capacity using catalog values", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Mawar",
					slug: "paket-mawar",
					description: "Dekorasi klasik.",
					guest_capacity: 200,
					options: [{ final_price: 35_000_000 }],
				},
				{
					name: "Paket Melati",
					slug: "paket-melati",
					description: "Dekorasi modern.",
					guest_capacity: 500,
					options: [{ final_price: 50_000_000 }],
				},
			],
		});

		const result = await answerSitaQuestion(
			"Bandingkan paket Mawar vs Melati",
			"id",
		);

		expect(result.cards).toHaveLength(2);
		expect(result.answer).toContain("Paket Mawar (Rp");
		expect(result.answer).toContain(
			"Kapasitas terbesar yang tercatat: Paket Melati (500 tamu)",
		);
	});

	it("reuses venue context for a city follow-up", async () => {
		getPageBatchMock.mockResolvedValue({
			venues: [
				{
					name: "Gedung Jakarta",
					slug: "gedung-jakarta",
					address: "Jakarta Selatan",
					excerpt: "Venue pernikahan.",
				},
				{
					name: "Gedung Bogor",
					slug: "gedung-bogor",
					address: "Bogor",
					excerpt: "Venue pernikahan.",
				},
			],
		});

		const result = await answerSitaQuestion("yang di Jakarta", "id", {
			topic: "venues",
		});

		expect(result.cards.map((card) => card.title)).toEqual(["Gedung Jakarta"]);
	});

	it("uses structured venue city criteria and avoids claiming date availability", async () => {
		getPageBatchMock.mockResolvedValue({
			venues: [
				{
					name: "Gedung Jakarta",
					slug: "gedung-jakarta",
					address: "Jakarta Selatan",
					excerpt: "Venue pernikahan.",
				},
				{
					name: "Gedung Bogor",
					slug: "gedung-bogor",
					address: "Bogor",
					excerpt: "Venue pernikahan.",
				},
			],
		});

		const result = await answerSitaQuestion("yang cocok", "id", {
			topic: "venues",
			criteria: { city: "Jakarta", eventDate: "2027-06-15" },
		});

		expect(result.cards.map((card) => card.title)).toEqual(["Gedung Jakarta"]);
		expect(result.answer).toContain("untuk Jakarta");
		expect(result.answer).toContain("perlu dikonfirmasi langsung");
	});

	it("does not invent an answer when no FAQ matches", async () => {
		getPageBatchMock.mockResolvedValue({
			faqs: [
				{
					question: "Bagaimana cara booking?",
					answer: "Hubungi admin untuk memulai booking.",
				},
			],
		});

		const result = await answerSitaQuestion(
			"Berapa jumlah jerapah di bulan?",
			"id",
		);

		expect(result.answer).toContain("belum menangkap maksudnya");
		expect(result.cards).toEqual([]);
		expect(result.offerContact).toBe(true);
		expect(result.unanswered).toBe(true);
		expect(result.suggestions).toContainEqual({
			label: "Hubungi admin",
			action: "contact",
		});
	});

	it("shows package records returned by the current catalog", async () => {
		getPageBatchMock.mockImplementation(async (page) =>
			page === "packages"
				? {
						packages: [
							{
								name: "Paket Pernikahan",
								slug: "paket-pernikahan",
								description: "Start your yoga journey with our studio session.",
								options: [{ final_price: 210000 }],
							},
						],
					}
				: { faqs: [] },
		);

		const result = await answerSitaQuestion("Paket wedding", "id");

		expect(result.answer).toContain("katalog Sagita");
		expect(result.cards).toHaveLength(1);
		expect(result.cards[0].description).toContain("yoga journey");
	});

	it("asks users to choose contact instead of opening the form for typed requests", async () => {
		const result = await answerSitaQuestion("Hubungi admin", "id");

		expect(result.answer).toContain("pilih tombol");
		expect(result.suggestions).toContainEqual({
			label: "Hubungi admin",
			action: "contact",
		});
	});

	it("answers a matching question with the stored FAQ answer", async () => {
		getPageBatchMock.mockResolvedValue({
			faqs: [
				{
					question: "Bagaimana cara booking?",
					answer: "<p>Hubungi admin untuk memulai booking.</p>",
				},
			],
		});

		const result = await answerSitaQuestion("Bagaimana cara booking?", "id");

		expect(result.answer).toBe("Hubungi admin untuk memulai booking.");
		expect(result.offerContact).toBe(true);
	});

	it("shows venue details from Sagita's venue data", async () => {
		getPageBatchMock.mockResolvedValue({
			venues: [
				{
					name: "Gedung Sentul",
					slug: "gedung-sentul",
					address: "Sentul, Bogor",
					excerpt: "Venue pernikahan dengan area outdoor.",
					image: "https://cdn.example.test/sentul.jpg",
					google_maps_url: "https://maps.example.test/sentul",
				},
			],
		});

		const result = await answerSitaQuestion("Venue Sentul", "id");

		expect(result.cards).toEqual([
			{
				title: "Gedung Sentul",
				description: "Sentul, Bogor · Venue pernikahan dengan area outdoor.",
				image: "https://cdn.example.test/sentul.jpg",
				actions: [
					{
						label: "Lihat venue",
						href: "/venues#venue-gedung-sentul",
					},
					{ label: "Peta", href: "https://maps.example.test/sentul" },
				],
			},
		]);
		expect(result.answer).toContain("venue yang tercatat");
	});

	it("offers helpful venue guidance without inventing a venue price", async () => {
		getPageBatchMock.mockImplementation(async (page) =>
			page === "venues" ? { venues: [] } : { faqs: [] },
		);

		const result = await answerSitaQuestion("Harga venue", "id");

		expect(result.answer).toContain("biaya venue perlu dikonfirmasi");
		expect(result.cards[0].actions?.[0]?.href).toBe("/formulir-konsultasi");
		expect(result.offerContact).toBe(true);
	});

	it("shows vendor images, categories, and a vendor link", async () => {
		getPageBatchMock.mockResolvedValue({
			vendors: [
				{
					name: "Vendor Dekor Mawar",
					avatar: "https://cdn.example.test/vendor-mawar.jpg",
					biografi: "Menyediakan dekorasi pernikahan.",
					vendors_categories: [{ name: "Dekorasi" }],
				},
			],
		});

		const result = await answerSitaQuestion("Vendor dekorasi", "id");

		expect(result.cards[0]).toMatchObject({
			title: "Vendor Dekor Mawar",
			description: "Dekorasi · Menyediakan dekorasi pernikahan.",
			image: "https://cdn.example.test/vendor-mawar.jpg",
			actions: [{ label: "Lihat vendor", href: "/vendors" }],
		});
	});

	it("uses the selected vendor category when answering a follow-up", async () => {
		getPageBatchMock.mockResolvedValue({
			vendors: [
				{
					name: "Vendor Dekor Mawar",
					biografi: "Menyediakan dekorasi pernikahan.",
					vendors_categories: [{ name: "Dekorasi" }],
				},
				{
					name: "Vendor Katering Melati",
					biografi: "Menyediakan katering pernikahan.",
					vendors_categories: [{ name: "Catering" }],
				},
			],
		});

		const result = await answerSitaQuestion("yang tersedia", "id", {
			topic: "vendors",
			criteria: { vendorCategory: "Dekorasi" },
		});

		expect(result.cards.map((card) => card.title)).toEqual([
			"Vendor Dekor Mawar",
		]);
		expect(result.answer).toContain("kategori Dekorasi");
	});

	it("returns at most four package, venue, or vendor recommendations", async () => {
		getPageBatchMock.mockImplementation(async (page) => {
			if (page === "packages") {
				return {
					packages: Array.from({ length: 5 }, (_, index) => ({
						name: `Wedding Package ${index + 1}`,
						slug: `wedding-package-${index + 1}`,
						description: "Wedding planning and coordination.",
						options: [{ final_price: 50_000_000 + index }],
					})),
				};
			}
			if (page === "venues") {
				return {
					venues: Array.from({ length: 5 }, (_, index) => ({
						name: `Venue Jakarta ${index + 1}`,
						slug: `venue-jakarta-${index + 1}`,
						address: "Jakarta",
						excerpt: "Wedding venue",
					})),
				};
			}
			return {
				vendors: Array.from({ length: 5 }, (_, index) => ({
					name: `Vendor ${index + 1}`,
					biografi: "Wedding vendor",
					vendors_categories: [{ name: "Dekorasi" }],
				})),
			};
		});

		const results = await Promise.all([
			answerSitaQuestion("Paket", "id"),
			answerSitaQuestion("Venue Jakarta", "id"),
			answerSitaQuestion("Vendor", "id"),
		]);

		expect(results.map((result) => result.cards.length)).toEqual([4, 4, 4]);
	});

	it("shows a Jakarta venue's address and map action", async () => {
		getPageBatchMock.mockResolvedValue({
			venues: [
				{
					name: "Yoga Roots Studio",
					slug: "yoga-roots-studio",
					address:
						"Roots Prasasta Building, Jl. Kawi Raya No.37, Jakarta 12980",
					excerpt: "Venue di Jakarta.",
				},
			],
		});

		const result = await answerSitaQuestion("Venue di Jakarta", "id");

		expect(result.cards[0].description).toContain("Jakarta 12980");
		expect(result.cards[0].actions).toContainEqual({
			label: "Lihat venue",
			href: "/venues#venue-yoga-roots-studio",
		});
		expect(result.cards[0].actions?.[1]?.label).toBe("Peta");
	});

	it("matches a booking question to the existing FAQ", async () => {
		getPageBatchMock.mockResolvedValue({
			faqs: [
				{
					question:
						"Bagaimana cara melakukan pemesanan layanan Sagita Wedding Planner?",
					answer:
						"Pemesanan dapat dimulai dengan menghubungi tim kami melalui WhatsApp.",
				},
			],
		});

		const result = await answerSitaQuestion("Bagaimana cara booking?", "id");

		expect(result.answer).toContain("Pemesanan dapat dimulai");
		expect(result.offerContact).toBe(true);
	});

	it("directs Wedding Organizer service questions to the consultation form", async () => {
		const result = await answerSitaQuestion("Layanan WO", "id");

		expect(result.answer).toContain("formulir konsultasi");
		expect(result.cards[0].actions).toEqual([
			{ label: "Isi formulir konsultasi", href: "/formulir-konsultasi" },
		]);
		expect(result.offerContact).toBe(false);
		expect(getPageBatchMock).not.toHaveBeenCalled();
	});

	it("answers package questions with relevant FAQ when no valid package is listed", async () => {
		getPageBatchMock.mockImplementation(async (page) =>
			page === "packages"
				? { packages: [] }
				: {
						faqs: [
							{
								question:
									"Apakah paket pernikahan dapat disesuaikan dengan budget saya?",
								answer:
									"Sampaikan anggaran saat konsultasi agar tim membantu memberi pilihan layanan.",
							},
						],
					},
		);

		const result = await answerSitaQuestion("Paket & harga", "id");

		expect(result.answer).toContain("Sampaikan anggaran");
		expect(result.offerContact).toBe(true);
	});
});
