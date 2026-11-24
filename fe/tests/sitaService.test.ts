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
		expect(result.answer).toContain("di sekitar Jakarta");
		expect(result.answer).toContain("dapat dikonfirmasi bersama tim Sagita");
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

		expect(result.answer).toContain("belum memahami pertanyaan");
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

		expect(result.answer).toContain("biaya venue akan dikonfirmasi oleh tim");
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

describe("Sita NLU-local understanding", () => {
	beforeEach(() => {
		getPageBatchMock.mockReset();
	});

	it("greets a hello with the topic menu instead of a no-match reply", async () => {
		const result = await answerSitaQuestion("Halo", "id");

		expect(result.answer).toContain("Sita");
		expect(result.answer).toContain("paket, venue, vendor");
		expect(result.cards).toEqual([]);
		expect(result.offerContact).toBe(false);
		expect(result.confidence).toBe(0.95);
		expect(result.suggestions).toContainEqual({
			label: "Paket & harga",
			topic: "packages",
		});
		expect(result.suggestions).toContainEqual({
			label: "Hubungi admin",
			action: "contact",
		});
		expect(getPageBatchMock).not.toHaveBeenCalled();
	});

	it("answers a thank-you without treating it as a failed question", async () => {
		const result = await answerSitaQuestion("makasih banyak", "id");

		expect(result.answer).toContain("Sama-sama");
		expect(result.confidence).toBe(0.95);
		expect(result.unanswered).toBeUndefined();
	});

	it("understands slang and typos as a package question", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Wedding Mawar",
					slug: "paket-wedding-mawar",
					description: "Paket pernikahan di venue",
					guest_capacity: "300 tamu",
					options: [{ final_price: 25_000_000, price: 30_000_000 }],
				},
			],
		});

		const result = await answerSitaQuestion("brp hrga pket", "id");

		expect(result.cards).toHaveLength(1);
		expect(result.cards[0].title).toBe("Paket Wedding Mawar");
		expect(result.confidence).toBe(0.9);
	});

	it("keeps the fallback reply and marks it as low confidence", async () => {
		getPageBatchMock.mockImplementation(async (page) =>
			page === "packages" ? { packages: [] } : { faqs: [] },
		);

		const result = await answerSitaQuestion(
			"Berapa jumlah jerapah di bulan?",
			"id",
		);

		expect(result.answer).toContain("belum memahami pertanyaan");
		expect(result.confidence).toBe(0.15);
		expect(result.unanswered).toBe(true);
		expect(result.suggestions).toContainEqual({
			label: "Venue",
			topic: "venues",
		});
		expect(result.suggestions).toContainEqual({
			label: "Hubungi admin",
			action: "contact",
		});
	});

	it("scores FAQ matches as confidently understood", async () => {
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
		expect(result.confidence).toBe(0.8);
	});

	it("uses session criteria from a context follow-up", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Wedding Mawar",
					slug: "paket-wedding-mawar",
					description: "Paket pernikahan lengkap",
					guest_capacity: "400 tamu",
					options: [{ final_price: 25_000_000, price: 30_000_000 }],
				},
			],
		});

		const result = await answerSitaQuestion("Untuk 200 tamu", "id", {
			topic: "packages",
		});

		expect(result.cards).toHaveLength(1);
		expect(result.answer).toContain("200");
		expect(result.confidence).toBe(0.85);
	});

	it("offers a secondary topic chip when the question mixes topics", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Wedding Mawar",
					slug: "paket-wedding-mawar",
					description: "Paket pernikahan di venue",
					guest_capacity: "300 tamu",
					options: [{ final_price: 25_000_000, price: 30_000_000 }],
				},
			],
		});

		const result = await answerSitaQuestion("paket sama venue", "id");

		expect(result.cards).toHaveLength(1);
		expect(result.confidence).toBe(0.9);
		expect(result.suggestions).toContainEqual({
			label: "Lihat venue",
			topic: "venues",
		});
	});

	it("still answers a booking question that starts with a hello", async () => {
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

		const result = await answerSitaQuestion("halo cara booking", "id");

		expect(result.answer).toContain("Pemesanan dapat dimulai");
		expect(result.answer).not.toContain("belum menangkap");
	});
});

describe("Sita explicit intents", () => {
	beforeEach(() => {
		getPageBatchMock.mockReset();
	});

	it("answers a promo question from active package promos", async () => {
		getPageBatchMock.mockImplementation(async (page) =>
			page === "packages"
				? {
						packages: [
							{
								name: "Paket Mawar",
								slug: "paket-mawar",
								promos: [
									{
										code: "HEMAT",
										type: "percent",
										value: 10,
										min_purchase: 1000000,
									},
								],
							},
						],
					}
				: { faqs: [] },
		);

		const result = await answerSitaQuestion("Ada promo paket?", "id");

		expect(result.answer).toContain("HEMAT");
		expect(result.cards[0].description).toContain("10%");
		expect(result.cards[0].description).toContain("min.");
		expect(result.confidence).toBe(0.9);
	});

	it("says honestly when no active promo is recorded", async () => {
		getPageBatchMock.mockResolvedValue({ packages: [] });

		const result = await answerSitaQuestion("Ada diskon?", "id");

		expect(result.answer).toContain("belum ada promo aktif");
		expect(result.suggestions).toContainEqual({
			label: "Hubungi admin",
			action: "contact",
		});
	});

	it("answers payment questions from recorded package terms", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Mawar",
					slug: "paket-mawar",
					dp_percentage: 30,
					payment_terms_note: "DP 30% saat kontrak, pelunasan sesuai termin.",
				},
			],
		});

		const result = await answerSitaQuestion("Bagaimana pembayaran DP?", "id");

		expect(result.answer).toContain("DP yang tercatat adalah 30%");
		expect(result.answer).toContain("pelunasan sesuai termin");
		expect(result.cards[0].actions?.[0]?.href).toBe("/formulir-konsultasi");
	});

	it("uses blackout dates for an availability question with a date", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Mawar",
					blackout_dates: [{ date: "2027-06-20", reason: "libur acara" }],
				},
			],
		});

		const result = await answerSitaQuestion(
			"Apakah venue tersedia 20/06/2027?",
			"id",
		);

		expect(result.answer).toContain("2027-06-20");
		expect(result.answer).toContain("libur acara");
		expect(result.confidence).toBe(0.9);
	});

	it("asks for a date when availability intent has no date", async () => {
		const result = await answerSitaQuestion(
			"Apakah tanggalnya tersedia?",
			"id",
		);

		expect(result.clarify?.question).toBe("Tanggal acara yang diinginkan?");
		expect(result.clarify?.options).toHaveLength(3);
		expect(result.clarify?.options[0]).toMatch(/^20\d{2}-\d{2}-\d{2}$/);
		expect(result.clarify?.options[1]).toMatch(/^20\d{2}-\d{2}-\d{2}$/);
		expect(result.clarify?.options[2]).toBe("Hubungi admin");
		expect(result.confidence).toBe(0.85);
		expect(getPageBatchMock).not.toHaveBeenCalled();
	});

	it("offers clarify options for an ambiguous budget", async () => {
		const result = await answerSitaQuestion(
			"Paket dengan budget 50 untuk 100 tamu",
			"id",
		);

		expect(result.answer).toContain("boleh sebutkan batas anggaran");
		expect(result.clarify).toMatchObject({
			question: "Berapa batas anggaran yang Kakak rencanakan?",
			options: expect.arrayContaining(["50 juta"]),
		});
	});

	it("does not present unrelated studio reviews as wedding testimonials", async () => {
		getPageBatchMock.mockResolvedValue({
			testimonials: [
				{
					nama: "Member",
					isi_testimoni: "Yoga membership and wellness class were great.",
				},
			],
		});

		const result = await answerSitaQuestion("Ada testimoni?", "id");

		expect(result.answer).toContain("Saat ini belum ada testimoni pernikahan");
		expect(result.cards).toEqual([]);
	});

	it("shows relevant wedding testimonials when available", async () => {
		getPageBatchMock.mockResolvedValue({
			testimonials: [
				{
					nama: "Rina",
					jabatan: "Bride 2025",
					isi_testimoni:
						"Wedding planning was smooth and the team was wonderful.",
				},
			],
		});

		const result = await answerSitaQuestion("Ada review klien?", "id");

		expect(result.cards[0].title).toBe("Rina");
		expect(result.cards[0].description).toContain(
			"Wedding planning was smooth",
		);
	});

	it("uses the site office address when provided", async () => {
		const result = await answerSitaQuestion("Alamat kantor?", "id", undefined, {
			officeAddress: "Jl. Sagita No. 12, Jakarta",
		});

		expect(result.answer).toContain("Jl. Sagita No. 12, Jakarta");
		expect(result.cards[0].actions?.[0]?.href).toContain("google.com/maps");
		expect(getPageBatchMock).not.toHaveBeenCalled();
	});

	it("does not invent an office address when the site has none", async () => {
		const result = await answerSitaQuestion("Dimana kantor Sagita?", "id");

		expect(result.answer).toContain("belum tercantum");
		expect(result.cards).toEqual([]);
		expect(result.suggestions).toContainEqual({
			label: "Hubungi admin",
			action: "contact",
		});
	});

	it("shows promo and quota facts on package cards", async () => {
		getPageBatchMock.mockResolvedValue({
			packages: [
				{
					name: "Paket Mawar",
					slug: "paket-mawar",
					options: [{ final_price: 25000000, quota: 50 }],
					promos: [
						{
							code: "HEMAT",
							type: "fixed",
							value: 1000000,
							min_purchase: 0,
						},
					],
				},
			],
		});

		const result = await answerSitaQuestion("Paket", "id");

		expect(result.cards[0].description).toContain("Promo: HEMAT");
		expect(result.cards[0].description).toContain("Kuota tercatat: 50");
	});

	it("keeps an explicit vendor category as the vendor topic", async () => {
		getPageBatchMock.mockResolvedValue({
			vendors: [
				{
					name: "Vendor Foto Anggrek",
					biografi: "Fotografi pernikahan dan dokumentasi acara.",
					vendors_categories: [{ name: "Fotografi" }],
				},
			],
			faqs: [],
		});

		const result = await answerSitaQuestion(
			"photographer for the wedding",
			"en",
		);

		expect(result.cards.map((card) => card.title)).toEqual([
			"Vendor Foto Anggrek",
		]);
		expect(result.confidence).toBe(0.9);
		expect(result.suggestions).not.toContainEqual({
			label: "View packages",
			topic: "packages",
		});
	});

	it("offers a source link for package and FAQ answers", async () => {
		getPageBatchMock.mockImplementation(async (page) =>
			page === "packages"
				? {
						packages: [
							{
								name: "Paket Mawar",
								slug: "paket-mawar",
								options: [{ final_price: 25000000 }],
							},
						],
					}
				: {
						faqs: [
							{
								question: "Bagaimana cara booking?",
								answer: "Hubungi tim kami melalui WhatsApp.",
							},
						],
					},
		);

		const packages = await answerSitaQuestion("Paket", "id");
		const faq = await answerSitaQuestion("Cara booking", "id");

		expect(packages.source?.href).toBe("/packages");
		expect(packages.topic).toBe("packages");
		expect(faq.source?.href).toBe("/faq");
	});

	it("uses future dates in the availability clarify options", async () => {
		const result = await answerSitaQuestion("Ketersediaan tanggal?", "id");
		const [first, second] = result.clarify?.options ?? [];

		expect(first).toMatch(/^20\d{2}-\d{2}-\d{2}$/);
		expect(second).toMatch(/^20\d{2}-\d{2}-\d{2}$/);
		expect(first).not.toBe(second);
		expect(new Date(`${first}T00:00:00Z`).getTime()).toBeGreaterThan(
			Date.now(),
		);
	});
});
