import type { Locale } from "../config/locales.js";

type FaqCopy = {
	kategori: string;
	question: string;
	answer: string;
};

const faqCopyByUuid: Record<string, Record<Locale, FaqCopy>> = {
	"4b9411a3-ccc5-44fd-bc24-e8dc7065773c": {
		en: {
			kategori: "Wedding Planning",
			question: "When should we start planning our wedding?",
			answer:
				"We recommend beginning 6–12 months before your wedding day. This gives you time to choose a date, venue, and concept, set a budget, and secure your preferred vendors at a comfortable pace.",
		},
		id: {
			kategori: "Perencanaan Pernikahan",
			question: "Kapan sebaiknya kami mulai merencanakan pernikahan?",
			answer:
				"Kami menyarankan Anda mulai merencanakan pernikahan 6–12 bulan sebelumnya. Dengan waktu yang cukup, Anda dapat menentukan tanggal, lokasi, konsep, dan anggaran, sekaligus memilih vendor tanpa terburu-buru.",
		},
	},
	"fa2b17f9-f0d4-4849-8373-8d787b80113e": {
		en: {
			kategori: "Packages & Services",
			question: "What is included in a wedding planning package?",
			answer:
				"Each package is tailored to a different level of support and may include concept planning, vendor coordination, wedding-day coordination, décor, photography, videography, an MC, and entertainment. We’ll explain what’s included and help you find an option that suits your plans and budget.",
		},
		id: {
			kategori: "Paket & Layanan",
			question: "Apa saja yang termasuk dalam paket Wedding Organizer?",
			answer:
				"Setiap paket menawarkan cakupan pendampingan yang berbeda, mulai dari perencanaan konsep dan koordinasi vendor hingga koordinasi hari pernikahan. Layanan tambahan dapat mencakup dekorasi, dokumentasi foto dan video, MC, serta hiburan. Tim kami akan menjelaskan setiap pilihan agar Anda dapat menentukan paket yang sesuai dengan rencana dan anggaran.",
		},
	},
	"9f25d34e-2a60-42cf-8379-ed069dc50ac0": {
		en: {
			kategori: "Booking & Payments",
			question: "How do we book a package and arrange payment?",
			answer:
				"Once we’ve discussed your plans and agreed on a package, our team will confirm availability and send the booking details and invoice. Payments can be made by bank transfer according to the schedule outlined in your service agreement.",
		},
		id: {
			kategori: "Pemesanan & Pembayaran",
			question: "Bagaimana cara memesan paket dan melakukan pembayaran?",
			answer:
				"Setelah membahas kebutuhan dan menyepakati paket, tim kami akan mengonfirmasi ketersediaan tanggal serta mengirimkan rincian pemesanan dan tagihan. Pembayaran dapat dilakukan melalui transfer bank sesuai tahapan dalam kesepakatan layanan.",
		},
	},
	"6bb623f8-916a-436b-85b3-a57035fe5ac5": {
		en: {
			kategori: "Vendors & Partners",
			question: "Can we work with vendors of our choice?",
			answer:
				"Of course. You’re welcome to choose from our trusted partners or suggest vendors of your own. Our team will coordinate with your chosen vendors to keep everyone aligned on your concept, schedule, and event requirements.",
		},
		id: {
			kategori: "Vendor & Mitra",
			question: "Apakah kami boleh menggunakan vendor pilihan sendiri?",
			answer:
				"Tentu. Anda dapat memilih vendor rekanan kami atau mengajukan vendor pilihan sendiri. Tim kami akan berkoordinasi dengan seluruh vendor agar konsep, jadwal, dan kebutuhan acara dipahami dengan baik.",
		},
	},
	"fd90f57c-53aa-4b5d-8e11-faea4808686d": {
		en: {
			kategori: "Wedding-Day Coordination",
			question: "What will your team handle on the wedding day?",
			answer:
				"Our team will oversee preparations, coordinate vendors, and keep the celebration running to schedule. You and your loved ones can be present for every moment while we take care of the details.",
		},
		id: {
			kategori: "Koordinasi Hari Pernikahan",
			question:
				"Apa yang ditangani tim Wedding Organizer pada hari pernikahan?",
			answer:
				"Tim kami mendampingi persiapan, mengoordinasikan vendor, dan menjaga agar rangkaian acara berjalan sesuai jadwal. Anda dan keluarga dapat menikmati setiap momen, sementara detail acara kami tangani.",
		},
	},
	"95b200f1-69c7-4f1b-a76b-172bcf5fcc55": {
		en: {
			kategori: "Changes & Cancellations",
			question: "Can we change our wedding date or package after booking?",
			answer:
				"Please let us know as soon as possible if you need to make a change. Requests made at least 30 days before the event can be considered, subject to availability. Cancellations are handled according to the terms in your service agreement.",
		},
		id: {
			kategori: "Perubahan & Pembatalan",
			question: "Apakah tanggal atau paket dapat diubah setelah pemesanan?",
			answer:
				"Jika perlu melakukan perubahan, silakan hubungi tim kami sesegera mungkin. Permintaan yang diajukan setidaknya 30 hari sebelum acara dapat dipertimbangkan sesuai ketersediaan. Pembatalan mengikuti ketentuan dalam kesepakatan layanan.",
		},
	},
};

const categoryNames: Record<string, Record<Locale, string>> = {
	"Perencanaan Pernikahan": {
		en: "Wedding Planning",
		id: "Perencanaan Pernikahan",
	},
	"Paket & Layanan": { en: "Packages & Services", id: "Paket & Layanan" },
	"Pemesanan & Pembayaran": {
		en: "Booking & Payments",
		id: "Pemesanan & Pembayaran",
	},
	"Vendor & Mitra": { en: "Vendors & Partners", id: "Vendor & Mitra" },
	"Pelaksanaan Acara": {
		en: "Wedding-Day Coordination",
		id: "Koordinasi Hari Pernikahan",
	},
	"Perubahan & Pembatalan": {
		en: "Changes & Cancellations",
		id: "Perubahan & Pembatalan",
	},
};

export function localizeFaqs(value: unknown, locale: Locale): unknown[] {
	if (!Array.isArray(value)) return [];

	return value.map((item) => {
		if (!item || typeof item !== "object") return item;
		const faq = item as Record<string, unknown>;
		const uuid = typeof faq.uuid === "string" ? faq.uuid : "";
		const copy = faqCopyByUuid[uuid]?.[locale];
		const category =
			typeof faq.kategori === "string"
				? (categoryNames[faq.kategori]?.[locale] ?? faq.kategori)
				: faq.kategori;

		return copy ? { ...faq, ...copy } : { ...faq, kategori: category };
	});
}

export function localizeFaqCategories(
	value: unknown,
	locale: Locale,
): unknown[] {
	if (!Array.isArray(value)) return [];
	return value.map((category) =>
		typeof category === "string"
			? (categoryNames[category]?.[locale] ?? category)
			: category,
	);
}
