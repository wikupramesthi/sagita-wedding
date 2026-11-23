import { beforeEach, describe, expect, it, vi } from "vitest";

vi.mock("../src/services/batchService.js", () => ({
	getPageBatch: vi.fn(),
}));

import { getPageBatch } from "../src/services/batchService.js";
import { answerSitaQuestion } from "../src/services/sitaService.js";

const getPageBatchMock = vi.mocked(getPageBatch);

const dataset = {
	packages: [
		{
			name: "Paket Mawar",
			slug: "paket-mawar",
			description: "Paket pernikahan klasik dengan dekorasi bunga.",
			guest_capacity: "300 tamu",
			cover_url: "https://cdn.example.test/mawar.jpg",
			dp_percentage: 30,
			payment_terms_note: "DP 30% saat kontrak, pelunasan sesuai termin.",
			blackout_dates: [{ date: "2027-06-20", reason: "libur acara" }],
			promos: [
				{
					code: "HEMAT",
					type: "percent",
					value: 10,
					min_purchase: 1_000_000,
				},
			],
			options: [{ final_price: 25_000_000, quota: 50 }],
		},
		{
			name: "Paket Melati",
			slug: "paket-melati",
			description: "Paket pernikahan modern untuk akad dan resepsi.",
			guest_capacity: "300 tamu",
			options: [{ final_price: 45_000_000, quota: 30 }],
		},
		{
			name: "Paket Anggrek",
			slug: "paket-anggrek",
			description: "Paket pernikahan mewah dengan dekorasi lengkap.",
			guest_capacity: "500 tamu",
			options: [{ final_price: 60_000_000, quota: 20 }],
		},
	],
	venues: [
		{
			name: "Gedung Jakarta",
			slug: "gedung-jakarta",
			address: "Jakarta Selatan",
			excerpt: "Venue pernikahan dalam ruangan.",
		},
		{
			name: "Gedung Bandung",
			slug: "gedung-bandung",
			address: "Bandung",
			excerpt: "Venue pernikahan dengan taman.",
		},
		{
			name: "Gedung Sentul",
			slug: "gedung-sentul",
			address: "Sentul, Bogor",
			excerpt: "Venue pernikahan outdoor.",
		},
	],
	vendors: [
		{
			name: "Vendor Dekor Mawar",
			biografi: "Dekorasi pernikahan untuk akad dan resepsi.",
			vendors_categories: [{ name: "Dekorasi" }],
		},
		{
			name: "Vendor Katering Melati",
			biografi: "Katering pernikahan dengan menu tradisional.",
			vendors_categories: [{ name: "Catering" }],
		},
		{
			name: "Vendor Foto Anggrek",
			biografi: "Fotografi pernikahan dan dokumentasi acara.",
			vendors_categories: [{ name: "Fotografi" }],
		},
		{
			name: "Vendor Rias Mawar",
			biografi: "Makeup dan rias pengantin di Jakarta.",
			vendors_categories: [{ name: "Makeup dan rias" }],
		},
	],
	faqs: [
		{
			question: "Bagaimana cara booking?",
			answer:
				"Pemesanan dapat dimulai dengan menghubungi tim kami melalui WhatsApp.",
		},
		{
			question: "Apakah paket custom bisa disesuaikan?",
			answer:
				"Paket dapat disesuaikan dengan kebutuhan acara setelah konsultasi.",
		},
		{
			question: "Apakah tersedia layanan WO?",
			answer: "Layanan Wedding Organizer tersedia melalui formulir konsultasi.",
		},
		{
			question: "Berapa biaya venue?",
			answer: "Biaya venue perlu dikonfirmasi langsung dengan admin.",
		},
	],
	testimonials: [
		{
			nama: "Rina",
			jabatan: "Bride 2025",
			isi_testimoni: "Wedding planning was smooth and the team was wonderful.",
		},
	],
};

interface GoldenQuery {
	message: string;
	minConfidence: number;
	contains?: string;
	cards?: number;
	unanswered?: boolean;
	clarify?: boolean;
}

const goldenQueries: GoldenQuery[] = [
	{ message: "Paket", minConfidence: 0.9, cards: 3 },
	{ message: "Paket wedding", minConfidence: 0.9, cards: 3 },
	{ message: "brp hrga pket", minConfidence: 0.9, cards: 3 },
	{ message: "pketnya brapa?", minConfidence: 0.9, cards: 3 },
	{ message: "Paket termurah", minConfidence: 0.9, cards: 3 },
	{ message: "Paket untuk 200 tamu", minConfidence: 0.9, cards: 3 },
	{ message: "Paket budget 50 juta", minConfidence: 0.9, cards: 2 },
	{
		message: "photographer for the wedding",
		minConfidence: 0.9,
		cards: 1,
	},
	{ message: "wedding photographer", minConfidence: 0.9, cards: 1 },

	{ message: "Bandingkan paket Mawar vs Melati", minConfidence: 0.9, cards: 2 },
	{ message: "paket sama venue", minConfidence: 0.9, cards: 3 },
	{ message: "Venue", minConfidence: 0.9, cards: 3 },
	{ message: "venue Jakarta", minConfidence: 0.9, cards: 1 },
	{ message: "venue di Bandung", minConfidence: 0.9, cards: 1 },
	{ message: "gedung pernikahan", minConfidence: 0.9, cards: 3 },
	{ message: "harga venue", minConfidence: 0.8, contains: "dikonfirmasi" },
	{ message: "lokasi venue", minConfidence: 0.9, cards: 3 },
	{ message: "venue sama vendor", minConfidence: 0.9, cards: 3 },
	{ message: "venue untuk 100 tamu", minConfidence: 0.9, cards: 3 },
	{ message: "Vendor", minConfidence: 0.9, cards: 4 },
	{ message: "vendor dekorasi", minConfidence: 0.9, cards: 1 },
	{ message: "vendor catering", minConfidence: 0.9, cards: 1 },
	{ message: "catering", minConfidence: 0.9, cards: 1 },
	{ message: "fotografer", minConfidence: 0.9, cards: 1 },
	{ message: "vendor makeup", minConfidence: 0.9, cards: 1 },
	{ message: "vendor Jakarta", minConfidence: 0.9, cards: 1 },
	{ message: "cara booking", minConfidence: 0.8, contains: "Pemesanan" },
	{
		message: "Bagaimana cara booking?",
		minConfidence: 0.8,
		contains: "Pemesanan",
	},
	{ message: "halo cara booking", minConfidence: 0.8, contains: "Pemesanan" },
	{
		message: "bisa custom paket?",
		minConfidence: 0.8,
		contains: "disesuaikan",
	},
	{
		message: "layanan WO",
		minConfidence: 0.9,
		contains: "formulir konsultasi",
	},
	{ message: "Halo", minConfidence: 0.9, contains: "Sita" },
	{ message: "hai", minConfidence: 0.9, contains: "Sita" },
	{ message: "makasih banyak", minConfidence: 0.9, contains: "Sama-sama" },
	{ message: "thanks a lot", minConfidence: 0.9, contains: "Sama-sama" },
	{ message: "how are you", minConfidence: 0.9, contains: "Sita" },
	{ message: "Ada promo paket?", minConfidence: 0.9, contains: "HEMAT" },
	{ message: "any discount?", minConfidence: 0.9, contains: "HEMAT" },
	{ message: "Bagaimana pembayaran DP?", minConfidence: 0.9, contains: "30%" },
	{ message: "cicilan", minConfidence: 0.9, contains: "pembayaran" },
	{
		message: "ketersediaan tanggal 2027-06-15",
		minConfidence: 0.9,
		contains: "2027-06-15",
	},
	{
		message: "tersedia tanggal 20/06/2027",
		minConfidence: 0.9,
		contains: "libur acara",
	},
	{
		message: "apakah tanggalnya tersedia?",
		minConfidence: 0.8,
		clarify: true,
	},
	{
		message: "Ada testimoni?",
		minConfidence: 0.9,
		contains: "testimoni",
		cards: 1,
	},
	{
		message: "review klien",
		minConfidence: 0.9,
		contains: "testimoni",
		cards: 1,
	},
	{ message: "Alamat kantor?", minConfidence: 0.9, contains: "belum tercatat" },
	{
		message: "Where is your office?",
		minConfidence: 0.9,
		contains: "belum tercatat",
	},
	{
		message: "Dimana kantor Sagita?",
		minConfidence: 0.9,
		contains: "belum tercatat",
	},
	{ message: "how much is the package", minConfidence: 0.9, cards: 3 },
	{ message: "venue price", minConfidence: 0.8, contains: "dikonfirmasi" },
	{ message: "wedding vendors", minConfidence: 0.9, cards: 4 },
	{ message: "photographer for the wedding", minConfidence: 0.9, cards: 1 },
	{ message: "packages and pricing", minConfidence: 0.9, cards: 3 },
	{
		message: "Berapa jumlah jerapah di bulan?",
		minConfidence: 0.1,
		contains: "belum menangkap",
		unanswered: true,
	},
	{
		message: "asdf qwerty",
		minConfidence: 0.1,
		contains: "belum menangkap",
		unanswered: true,
	},
];

describe("Sita golden queries", () => {
	beforeEach(() => {
		getPageBatchMock.mockResolvedValue(dataset);
	});

	it("has at least fifty regression queries", () => {
		expect(goldenQueries.length).toBeGreaterThanOrEqual(50);
	});

	it.each(goldenQueries)("$message", async (query) => {
		const result = await answerSitaQuestion(query.message, "id");

		expect(result.confidence ?? 0).toBeGreaterThanOrEqual(query.minConfidence);
		if (query.contains) {
			expect(result.answer.toLowerCase()).toContain(
				query.contains.toLowerCase(),
			);
		}
		if (query.cards !== undefined) {
			expect(result.cards).toHaveLength(query.cards);
		}
		if (query.unanswered !== undefined) {
			expect(result.unanswered).toBe(query.unanswered ? true : undefined);
		}
		if (query.clarify) {
			expect(result.clarify?.options.length).toBeGreaterThan(0);
		}
	});
});
