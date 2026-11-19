import { LOCALES } from "../config/locales.js";

const contactPageEn = {
	badge: "Replies within 24 hours",
	titleA: "Let's",
	titleB: "connect",
	heroDesc:
		"Questions about classes, packages, or schedules? Tell us what you need — our team is here to listen and help you find the right practice.",
	waCta: "Chat on WhatsApp",
	mailCta: "Email",
	infoTitle: "Contact Info",
	infoDesc: "Or reach us directly through any channel below.",
	addressLabel: "Venue Address",
	directions: "Get directions",
	formTitle: "Send Us a Message",
	namePh: "Full Name",
	emailPh: "Email Address",
	phonePh: "Active WhatsApp Number",
	msgPh: "Tell us how we can help...",
	captchaPh: "Enter your answer",
	captchaUnavailable: "Verification unavailable",
	sendBtn: "Send Message",
	sendingBtn: "Sending...",
	mapCardTitle: "YogaRoots Studio",
	faqEyebrow: "FAQ",
	faqTitle: "Frequently asked",
	faqEmpty: "No FAQs available.",
};

const artOfLivingEn = {
	badge: "Founder — Art of Living",
	titleA: "Gurudev Sri Sri",
	titleB: "Ravi Shankar",
	subtitle: "Ambassador of Peace & Global Humanitarian Leader",
	heroDesc:
		"His transformation of age-old wisdom into simple messages for everyday life — and his vision of a stress-free, violence-free society — has inspired millions across 180 countries.",
	learnMore: "Learn more",
	follow: "Follow Gurudev",
	stats: [
		{ v: "800M+", l: "Lives touched" },
		{ v: "180", l: "Countries" },
		{ v: "1981", l: "AoL founded" },
	],
	aboutEyebrow: "About Gurudev",
	aboutTitle: "Guru, leader & ambassador of peace",
	aboutP1:
		"Gurudev Sri Sri Ravi Shankar is a globally revered spiritual and humanitarian leader. Through a network of organizations including the Art of Living and the International Association for Human Values, he has spearheaded a worldwide movement for a stress-free, violence-free society — reaching an estimated 800+ million people.",
	aboutP2:
		"Born in 1956 in Southern India, Gurudev was a gifted child — reciting the Bhagavad Gita by age four and often found in meditation. He holds degrees in both Vedic literature and physics. In 1982, after a ten-day period of silence in Shimoga, the Sudarshan Kriya™ was born — a powerful breathing technique that became the centerpiece of Art of Living courses.",
	honorsEyebrow: "Honors & recognitions",
	honorsTitle: "Honors and",
	honorsTitleItalic: "Recognitions",
	bioEyebrow: "Biography",
	bioTitle: "A journey of",
	bioTitleItalic: "service & wisdom",
	kriyaTitle: "Sudarshan Kriya™ (SKY)",
	kriyaDesc:
		"Gurudev’s signature breathing technique — documented by independent medical research to ease stress, calm the mind, and strengthen immunity.",
	exploreClasses: "Explore YogaRoots classes",
	// Teks untuk area kosong di panorama gurudev-hero.webp
	missionEyebrow: "Our Mission",
	mission: "Towards a stress-free, violence-free society.",
	quote:
		"Joy is the goal of life. Service is its expression, and knowledge is the way.",
	quoteAuthor: "— Gurudev Sri Sri Ravi Shankar",
	engage: "Engage with Gurudev Sri Sri Ravi Shankar",
	honors: [
		{
			t: "Distinguished Citizen",
			d: "The highest civilian recognition in Colombia, Mongolia, Paraguay and Suriname.",
		},
		{
			t: "Padma Vibhushan",
			d: "The highest annual civilian recognition in the Republic of India.",
		},
		{
			t: "Peace Mediator",
			d: "Government recognitions in Sri Lanka, Iraq, Colombia, Venezuela, Ivory Coast, Cameroon and India.",
		},
		{
			t: "Honorary Doctorates",
			d: "From 26 international institutions worldwide.",
		},
		{
			t: "Honors by Institutions",
			d: "Honors from 43 other institutions across the globe.",
		},
		{
			t: "Sri Sri Ravi Shankar Days",
			d: "Proclaimed in multiple cities and countries.",
		},
	],
	milestones: [
		{
			y: "1956",
			t: "Born in Southern India",
			d: "A gifted child reciting the Bhagavad Gita by age four, often in meditation.",
		},
		{
			y: "1981",
			t: "Art of Living founded",
			d: "An educational and humanitarian movement growing across 180 countries.",
		},
		{
			y: "1982",
			t: "Sudarshan Kriya™ born",
			d: "After ten days of silence in Shimoga — the centerpiece of all courses.",
		},
		{
			y: "Today",
			t: "800M+ lives touched",
			d: "Self-development programs, peace mediation, and global humanitarian service.",
		},
	],
	peacePoints: [
		{
			t: "Mediating armed conflict",
			d: "From Kashmir, Assam and Bihar to Colombia, Kosovo, Iraq, Syria and Côte d’Ivoire — his programs moved people in conflict toward peace.",
		},
		{
			t: "A global volunteer wave",
			d: "His message of inner peace sparked a surge of service through a huge corps of volunteers worldwide.",
		},
		{
			t: "Research-backed",
			d: "Ancient Yoga techniques adapted for modern life — easing depression, lowering cortisol, strengthening immunity.",
		},
	],
};

const rawTranslations = {
	en: {
		nav: {
			home: "Home",
			about: "About",
			artofliving: "Art of Living",
			classes: "Classes",
			schedules: "Schedules",
			packages: "Packages",
			instructors: "Instructors",
			events: "Events",
			articles: "Articles",
			contact: "Contact",
		},
		contactUs: "Contact Us",
		login: "Login",
		// State setelah login (data dari backend GET /auth/me)
		signedInAs: "Signed in as",
		signOut: "Sign out",
		signOutFailed: "Could not sign out. Please try again.",
		viewSchedule: "View schedule",
		bookingSignedInTitle: "Welcome back",
		bookingSignedInDesc:
			"You're signed in. Pick a class to continue with your booking.",
		bookingSignedOutTitle: "Begin Your Practice",
		bookingSignedOutDesc:
			"Sign in with Google and take the first step toward a healthier body and a more balanced mind.",
		syncedNote: "Name and email are synced automatically.",
		loginWithGoogle: "Sign in with Google",
		closeModal: "Close",

		// --- class detail ---
		backToClasses: "Back to Classes",
		levelLabel: "Level",
		levelAdvanced: "Advanced",
		instructorLabel: "Instructor",
		venueLabel: "Venue",
		scheduleLabel: "Class Schedule",
		schedulePick: "Choose your preferred schedule",
		scheduleHint: "Available schedules will be shown when you book this class.",
		bookNow: "Book Now →",
		classDescFallback:
			"Discover a mindful yoga practice designed to support your journey.",

		// --- package detail ---
		packageNotFoundTitle: "Package not found",
		packageNotFoundDesc: "The package you are looking for is not available.",
		backToMembership: "Back to Membership",
		mostPopular: "Most Popular",
		chooseYourOption: "Choose Your Option",
		chooseYourOptionDesc: "Select the option that best fits your practice.",
		whatsIncluded: "What's Included",
		chooseYourPackage: "Choose Your Package",
		chooseYourPackageDesc:
			"Find the option that works best for your practice and make every session at YogaRoots count.",
		quotaClasses: "classes",
		perMonth: "/month",
		durationDay: "day",
		durationDays: "days",
		durationWeek: "week",
		durationWeeks: "weeks",
		durationMonth: "month",
		durationMonths: "months",
		durationYear: "year",
		durationYears: "years",

		// --- schedules ---
		filterTitle: "Filter",
		filterAllLevels: "All Levels",
		filterAllTimes: "All Times",
		filterAllVenues: "All Venues",
		filterMorning: "Morning",
		filterAfternoon: "Afternoon",
		filterEvening: "Evening",
		prevWeek: "Previous week",
		nextWeek: "Next week",
		today: "Today",

		// --- packages list ---
		packagesSearchPlaceholder: "Search membership packages...",
		pagination: "Pagination",
		prevPage: "Previous",
		nextPage: "Next",

		bookClassBtn: "Book a Class →",

		// Hero
		heroLine1: "Strong Roots. Serious Progress.",
		heroLine2: "Start. Stabilize. Transform.",
		heroSub: "Start or go deeper — this is where your practice takes root.",
		heroBookClass: "Book Your First Class",
		scroll: "Scroll",

		// Classes section
		whatWeOffer: "What we offer",
		homeClassesTitle: "Classes for every",
		homeClassesTitleItalic: "body",
		viewAllClasses: "View all classes",
		viewAllEvents: "View all events",
		eventViewDetails: "View Details",
		eventReserve: "Reserve My Spot",
		eventNoDesc: "More details will be announced soon.",
		learnMore: "Learn more",
		fallbackClasses: [
			{
				name: "Yoga Nidra",
				description:
					"Guided yogic sleep in a calm, dark setting — a non-movement practice for deep conscious rest.",
			},
			{
				name: "Vinyasa to Yin",
				description:
					"Dynamic Vinyasa flow transitioning into long Yin stretches for a balance of strength and flexibility.",
			},
			{
				name: "Pranayama & Meditation",
				description:
					"Breathing techniques and meditation for emotional balance, improved focus, and mental clarity.",
			},
			{
				name: "Infra Detox Yoga",
				description:
					"Slow, heat-supported practice for detox and lymphatic stimulation with infrared heat assistance.",
			},
		],

		// Approach section
		ourApproach: "Our approach",
		moreThanJust: "More than just",
		moreThanJustItalic: "yoga",
		approachCards: [
			{
				title: "Therapeutic Approach",
				desc: "Every class is designed with your mind and body's needs in mind.",
			},
			{
				title: "Wide Variety of Classes",
				desc: "Explore over 50 different classes with various levels and intensity.",
			},
			{
				title: "Inclusive Community",
				desc: "All levels welcome. Your practice, your pace, your journey.",
			},
		],

		// Events
		upcoming: "Upcoming",
		eventsTitle: "Events &",
		eventsTitleItalic: "Announcements",

		// Final CTA
		readyTitle: "Ready to begin your",
		readyTitleItalic: "journey",
		ctaDesc:
			"Contact us via WhatsApp to learn about our packages, or book a class if you're ready to practice.",
		chatWhatsApp: "Chat on WhatsApp",
		bookClassCta: "Book a Class",

		// Pages header
		classesEyebrow: "CLASS SCHEDULE",
		classesTitle: "Find your flow",
		classesDesc:
			"Choose a class, pick your preferred time, and book your next practice at YogaRoots.",
		classGuideEyebrow: "OUR CLASSES",
		classGuideTitle: "Class",
		classGuideTitleItalic: "Guide",
		classGuideDesc:
			"All classes are led by certified instructors with personalized attention.",
		classSearchPlaceholder: "Search classes...",
		classFilterLevel: "Level:",
		classFilterAll: "All Levels",
		classFilterFoundation: "Foundation",
		classFilterIntermediate: "Intermediate",
		classFilterAdvance: "Advance",
		classJoinTitle: "Interested in joining?",
		classJoinDesc: "Contact us for packages and pricing",
		classWhatsappUs: "WhatsApp Us",
		tableOfContents: "On this page",
		ctaQuestions: "Questions about these terms?",
		instructorsTitleA: "Meet Our",
		instructorsTitleB: "Instructors",
		instructorsDesc:
			"Meet the instructors who bring experience, intention, and warmth into every YogaRoots practice.",
		eventsTitleA: "Something New",
		eventsTitleB: "Awaits You",
		eventsDesc:
			"Explore our upcoming events and workshops. Find new ways to learn, move, connect, and enjoy meaningful moments together.",
		eventsHeading: "Events & Workshops",
		eventsCount: "event available",
		eventsSearchPlaceholder: "Search events, workshops, or classes...",
		eventsFilterAll: "All",
		eventsFilterToday: "Today",
		eventsFilterWeek: "This Week",
		eventsFilterMonth: "This Month",
		eventsSearchBtn: "Search",
		packagesTitleA: "Membership",
		packagesTitleB: "Packages",
		packagesDesc:
			"Find the membership that fits your practice, your pace, and your goals. Explore flexible packages designed to make every Yogaroots session more meaningful.",
		blogTitleA: "Tips &",
		blogTitleB: "Inspiration",
		blogDesc:
			"Discover helpful tips, inspiring ideas, and simple practices to support a healthier body, a calmer mind, and a more balanced life.",
		contactTitle: "Connect With Us",
		shareArticle: "Share this article",
		copiedLink: "Link copied!",
		relatedArticles: "Related articles",
		latestClasses: "Latest classes",
		contactDesc:
			"Have a question? We're here to listen, guide, and help you find what feels right for your journey.",
		galleryTitle: "Our Gallery",
		galleryDesc:
			"Explore moments, spaces, and experiences created to inspire comfort, balance, and tranquility. Click to enlarge.",
		notFoundTitle: "Lost? Take a breath first.",
		notFoundDesc:
			"The page you're looking for isn't here. It may have moved, like a flowing pose.",
		notFoundBack: "Back to Home →",
		// About page
		about: {
			eyebrow: "Our Story",
			titleA: "Rooted in",
			titleB: "Practice",
			heroDesc:
				"YogaRoots was born from a simple belief: everyone deserves a space to slow down, breathe, and return to themselves.",
			storyEyebrow: "About us",
			storyTitle: "More than a studio",
			storyP1:
				"What began as a small room, a few mats, and a shared love for mindful movement has grown into a community where people come to move, breathe, and reconnect. We believe yoga is not about perfect poses — it's about meeting yourself exactly where you are.",
			storyP2:
				"Our teachers blend traditional practice with a warm, modern approach, so whether you're rolling out a mat for the first time or deepening a lifelong practice, you'll always find a class that feels like home.",
			valuesEyebrow: "What we believe",
			valuesTitle: "Our core",
			valuesTitleItalic: "values",
			values: [
				{
					title: "Presence",
					desc: "We practice slowing down — creating space to breathe, feel, and be fully here.",
				},
				{
					title: "Inclusivity",
					desc: "Every body, every level, every journey is welcome on the mat. No exceptions.",
				},
				{
					title: "Growth",
					desc: "Progress over perfection. We meet you where you are and grow together.",
				},
			],
			stats: [
				{ value: "12K+", label: "Active Members" },
				{ value: "18", label: "Certified Instructors" },
				{ value: "500+", label: "Total Classes" },
				{ value: "4.8★", label: "Google Rating" },
			],
			quote:
				"You don't have to live with stress and pain. Living can be a joyful adventure instead.",
			quoteAuthor: "— The YogaRoots Team",
			heroBadge: "Est. 2023",
			journeyEyebrow: "Our journey",
			journeyTitle: "Milestones",
			journeyTitleItalic: "along the way",
			milestones: [
				{
					year: "2023",
					title: "A small beginning",
					desc: "YogaRoots opens as a single room studio with a handful of mats and a big intention.",
				},
				{
					year: "2024",
					title: "Growing together",
					desc: "Our community reaches a thousand members who share the same love for mindful movement.",
				},
				{
					year: "2025",
					title: "A home in South Jakarta",
					desc: "We open our dedicated studio space, designed to feel calm, warm, and welcoming.",
				},
				{
					year: "Today",
					title: "Rooted & thriving",
					desc: "Over 12,000 members practice with us, and every class still feels like coming home.",
				},
			],
			whyEyebrow: "Why YogaRoots",
			whyTitle: "A practice that meets you",
			whyTitleItalic: "where you are",
			whyDesc:
				"Everything we do is designed to make yoga feel accessible, thoughtful, and genuinely yours.",
			whyFeatures: [
				{
					title: "Certified teachers",
					desc: "Experienced, certified instructors guide every session with care and attention.",
				},
				{
					title: "All levels welcome",
					desc: "From first-timers to advanced practitioners, there is always a class for you.",
				},
				{
					title: "Mindful spaces",
					desc: "Calm, thoughtfully designed rooms that help you disconnect and unwind.",
				},
				{
					title: "Community first",
					desc: "More than a studio — a place to belong, connect, and grow together.",
				},
			],
			galleryEyebrow: "Life at the studio",
			galleryTitle: "Moments",
			galleryTitleItalic: "worth keeping",
			galleryCta: "View Gallery",
			articlesEyebrow: "Stories & tips",
			articlesTitle: "From the",
			articlesTitleItalic: "blog",
			articlesCta: "View all articles",
		},

		// Footer
		footerExplore: "Explore",
		footerAbout: "About Us",
		footerOurClasses: "Our Classes",
		footerInstructors: "Instructors",
		footerPackages: "Packages",
		footerSupport: "Support",
		footerFAQs: "FAQs",
		footerArticles: "Articles",
		footerContact: "Contact",
		footerPackagesPricing: "Packages & Pricing",
		footerGetInTouch: "Get in Touch",
		footerCommunity: "Our Partners",
		siteDesc:
			"Through our guidance, you will not only gain a deeper understanding of the postures but also learn to cultivate mental clarity and inner peace.",
		ctaHelpTitle: "Need help finding your flow?",
		ctaHelpDesc:
			"Not sure which class is right for you? Chat with our team on WhatsApp and we'll help you find the perfect practice.",
		ctaHelpBtn: "Chat on WhatsApp →",
		whatsappAdmin: "WhatsApp Admin",
		copyright: "All rights reserved.",
		privacyPolicy: "Privacy Policy",
		termsConditions: "Terms & Conditions",
		contactPage: contactPageEn,
		artOfLiving: artOfLivingEn,
	},

	id: {
		nav: {
			home: "Beranda",
			about: "Tentang",
			artofliving: "Art of Living",
			classes: "Kelas",
			schedules: "Jadwal",
			packages: "Paket",
			instructors: "Instruktur",
			events: "Acara",
			articles: "Artikel",
			contact: "Kontak",
		},
		contactUs: "Hubungi Kami",
		login: "Masuk",
		// State setelah login (data dari backend GET /auth/me)
		signedInAs: "Masuk sebagai",
		signOut: "Keluar",
		signOutFailed: "Gagal keluar. Silakan coba lagi.",
		viewSchedule: "Lihat jadwal",
		bookingSignedInTitle: "Selamat datang kembali",
		bookingSignedInDesc:
			"Kamu sudah masuk. Pilih kelas untuk melanjutkan booking.",
		bookingSignedOutTitle: "Mulai Praktikmu",
		bookingSignedOutDesc:
			"Masuk dengan Google dan ambil langkah pertama menuju tubuh yang lebih sehat dan pikiran yang lebih seimbang.",
		syncedNote: "Nama dan email tersinkron otomatis.",
		loginWithGoogle: "Masuk dengan Google",
		closeModal: "Tutup",

		// --- detail kelas ---
		backToClasses: "Kembali ke Daftar Kelas",
		levelLabel: "Level",
		levelAdvanced: "Lanjutan",
		instructorLabel: "Instruktur",
		venueLabel: "Venue",
		scheduleLabel: "Jadwal Kelas",
		schedulePick: "Pilih jadwal yang kamu inginkan",
		scheduleHint:
			"Jadwal yang tersedia akan tampil saat kamu memesan kelas ini.",
		bookNow: "Pesan Sekarang →",
		classDescFallback:
			"Temukan praktik yoga yang sadar untuk menunjang perjalananmu.",

		// --- detail paket ---
		packageNotFoundTitle: "Paket tidak ditemukan",
		packageNotFoundDesc: "Paket yang kamu cari tidak tersedia.",
		backToMembership: "Kembali ke Membership",
		mostPopular: "Paling Populer",
		chooseYourOption: "Pilih Opsi",
		chooseYourOptionDesc: "Pilih opsi yang paling sesuai dengan praktikmu.",
		whatsIncluded: "Yang Didapatkan",
		chooseYourPackage: "Pilih Paketmu",
		chooseYourPackageDesc:
			"Temukan opsi yang paling cocok untuk praktikmu dan pastikan setiap sesi di YogaRoots berarti.",
		quotaClasses: "kelas",
		perMonth: "/bulan",
		durationDay: "hari",
		durationDays: "hari",
		durationWeek: "minggu",
		durationWeeks: "minggu",
		durationMonth: "bulan",
		durationMonths: "bulan",
		durationYear: "tahun",
		durationYears: "tahun",

		// --- jadwal ---
		filterTitle: "Filter",
		filterAllLevels: "Semua Level",
		filterAllTimes: "Semua Waktu",
		filterAllVenues: "Semua Venue",
		filterMorning: "Pagi",
		filterAfternoon: "Siang",
		filterEvening: "Sore",
		prevWeek: "Minggu sebelumnya",
		nextWeek: "Minggu berikutnya",
		today: "Hari ini",

		// --- daftar paket ---
		packagesSearchPlaceholder: "Cari paket membership...",
		pagination: "Navigasi halaman",
		prevPage: "Sebelumnya",
		nextPage: "Berikutnya",

		bookClassBtn: "Daftar Kelas →",

		// Hero
		heroLine1: "Akar Kuat. Kemajuan Serius.",
		heroLine2: "Mulai. Stabil. Transformasi.",
		heroSub: "Mulai dari awal atau tingkatkan — di sinilah praktikmu berakar.",
		heroBookClass: "Pesan Kelas Pertamamu",
		scroll: "Gulir",

		// Classes section
		whatWeOffer: "Yang kami tawarkan",
		homeClassesTitle: "Kelas untuk semua",
		homeClassesTitleItalic: "orang",
		viewAllClasses: "Lihat semua kelas",
		viewAllEvents: "Lihat semua acara",
		eventViewDetails: "Lihat Detail",
		eventReserve: "Pesan Tempatku",
		eventNoDesc: "Detail lebih lanjut akan segera diumumkan.",
		learnMore: "Selengkapnya",
		fallbackClasses: [
			{
				name: "Yoga Nidra",
				description:
					"Tidur yogi terpandu dalam suasana tenang — latihan tanpa gerakan untuk istirahat sadar yang mendalam.",
			},
			{
				name: "Vinyasa to Yin",
				description:
					"Aliran Vinyasa dinamis bertransisi ke peregangan Yin untuk keseimbangan kekuatan dan fleksibilitas.",
			},
			{
				name: "Pranayama & Meditasi",
				description:
					"Teknik pernapasan dan meditasi untuk keseimbangan emosional, fokus, dan kejernihan pikiran.",
			},
			{
				name: "Infra Detox Yoga",
				description:
					"Latihan pelan berbantuan panas untuk detoks dan stimulasi getah bening dengan inframerah.",
			},
		],

		// Approach section
		ourApproach: "Pendekatan kami",
		moreThanJust: "Lebih dari sekadar",
		moreThanJustItalic: "yoga",
		approachCards: [
			{
				title: "Pendekatan Terapeutik",
				desc: "Setiap kelas dirancang dengan memperhatikan kebutuhan pikiran dan tubuh Anda.",
			},
			{
				title: "Beragam Pilihan Kelas",
				desc: "Jelajahi lebih dari 50 kelas berbeda dengan berbagai tingkat dan intensitas.",
			},
			{
				title: "Komunitas Inklusif",
				desc: "Semua level dipersilakan. Praktik Anda, ritme Anda, perjalanan Anda.",
			},
		],

		// Events
		upcoming: "Mendatang",
		eventsTitle: "Acara &",
		eventsTitleItalic: "Pengumuman",

		// Final CTA
		readyTitle: "Siap memulai",
		readyTitleItalic: "perjalanan Anda",
		ctaDesc:
			"Hubungi kami via WhatsApp untuk mengetahui paket kami, atau daftar kelas jika Anda siap berlatih.",
		chatWhatsApp: "Chat via WhatsApp",
		bookClassCta: "Daftar Kelas",

		// Pages header
		classesEyebrow: "JADWAL KELAS",
		classesTitle: "Temukan ritme",
		classesDesc:
			"Pilih kelas, tentukan waktu favoritmu, dan pesan latihanmu berikutnya di YogaRoots.",
		classGuideEyebrow: "KELAS KAMI",
		classGuideTitle: "Panduan",
		classGuideTitleItalic: "Kelas",
		classGuideDesc:
			"Semua kelas dipandu instruktur tersertifikasi dengan perhatian personal.",
		classSearchPlaceholder: "Cari kelas...",
		classFilterLevel: "Level:",
		classFilterAll: "Semua Level",
		classFilterFoundation: "Foundation",
		classFilterIntermediate: "Intermediate",
		classFilterAdvance: "Advance",
		classJoinTitle: "Tertarik bergabung?",
		classJoinDesc: "Hubungi kami untuk info paket dan harga",
		classWhatsappUs: "WhatsApp Kami",
		tableOfContents: "Di halaman ini",
		ctaQuestions: "Ada pertanyaan tentang ketentuan ini?",
		instructorsTitleA: "Kenali",
		instructorsTitleB: "Instruktur Kami",
		instructorsDesc:
			"Kenali para instruktur yang membawa pengalaman, niat, dan kehangatan dalam setiap latihan YogaRoots.",
		eventsTitleA: "Sesuatu yang Baru",
		eventsTitleB: "Menantimu",
		eventsDesc:
			"Jelajahi acara dan workshop mendatang kami. Temukan cara baru untuk belajar, bergerak, terhubung, dan menikmati momen bermakna bersama.",
		eventsHeading: "Acara & Workshop",
		eventsCount: "acara tersedia",
		eventsSearchPlaceholder: "Cari acara, workshop, atau kelas...",
		eventsFilterAll: "Semua",
		eventsFilterToday: "Hari Ini",
		eventsFilterWeek: "Minggu Ini",
		eventsFilterMonth: "Bulan Ini",
		eventsSearchBtn: "Cari",
		packagesTitleA: "Paket",
		packagesTitleB: "Membership",
		packagesDesc:
			"Temukan membership yang sesuai dengan praktikmu, ritmemu, dan tujuanmu. Jelajahi paket fleksibel yang dirancang untuk membuat setiap sesi YogaRoots lebih bermakna.",
		blogTitleA: "Tips &",
		blogTitleB: "Inspirasi",
		blogDesc:
			"Temukan tips bermanfaat, ide inspiratif, dan praktik sederhana untuk mendukung tubuh yang lebih sehat, pikiran yang lebih tenang, dan hidup yang lebih seimbang.",
		contactTitle: "Terhubung Dengan Kami",
		shareArticle: "Bagikan artikel ini",
		copiedLink: "Tautan tersalin!",
		relatedArticles: "Artikel terkait",
		latestClasses: "Kelas terbaru",
		contactDesc:
			"Punya pertanyaan? Kami di sini untuk mendengarkan, membimbing, dan membantu menemukan apa yang terasa cocok bagi perjalananmu.",
		galleryTitle: "Galeri Kami",
		galleryDesc:
			"Jelajahi momen, ruang, dan pengalaman yang diciptakan untuk menginspirasi kenyamanan, keseimbangan, dan ketenangan. Klik untuk memperbesar.",
		notFoundTitle: "Tersesat? Tarik napas dulu.",
		notFoundDesc:
			"Halaman yang kamu cari tidak ada. Mungkin sudah berpindah seperti pose yang mengalir.",
		notFoundBack: "Kembali ke Beranda →",

		// About page
		about: {
			eyebrow: "Cerita Kami",
			titleA: "Berakar pada",
			titleB: "Praktik",
			heroDesc:
				"YogaRoots lahir dari keyakinan sederhana: setiap orang layak mendapatkan ruang untuk melambat, bernapas, dan kembali pada dirinya sendiri.",
			storyEyebrow: "Tentang kami",
			storyTitle: "Lebih dari sekadar studio",
			storyP1:
				"Yang dimulai dari sebuah ruangan kecil, beberapa matras, dan kecintaan pada gerakan sadar kini tumbuh menjadi komunitas tempat orang datang untuk bergerak, bernapas, dan terhubung kembali. Kami percaya yoga bukan tentang pose yang sempurna — melainkan tentang bertemu diri sendiri tepat di mana Anda berada.",
			storyP2:
				"Para pengajar kami memadukan praktik tradisional dengan pendekatan modern yang hangat, sehingga baik Anda baru pertama kali menggelar matras maupun memperdalam praktik seumur hidup, Anda selalu menemukan kelas yang terasa seperti rumah.",
			valuesEyebrow: "Yang kami yakini",
			valuesTitle: "Nilai",
			valuesTitleItalic: "inti kami",
			values: [
				{
					title: "Kehadiran",
					desc: "Kami berlatih melambat — menciptakan ruang untuk bernapas, merasakan, dan benar-benar hadir.",
				},
				{
					title: "Inklusif",
					desc: "Setiap tubuh, setiap level, setiap perjalanan diterima di atas matras. Tanpa terkecuali.",
				},
				{
					title: "Bertumbuh",
					desc: "Progres di atas kesempurnaan. Kami menemani Anda tumbuh bersama.",
				},
			],
			stats: [
				{ value: "12K+", label: "Member Aktif" },
				{ value: "18", label: "Instruktur Bersertifikat" },
				{ value: "500+", label: "Total Kelas" },
				{ value: "4.8★", label: "Rating Google" },
			],
			quote:
				"Anda tidak harus hidup dengan stres dan rasa sakit. Hidup bisa menjadi petualangan yang menyenangkan.",
			quoteAuthor: "— Tim YogaRoots",
			heroBadge: "Berdiri 2023",
			journeyEyebrow: "Perjalanan kami",
			journeyTitle: "Tonggak",
			journeyTitleItalic: "sepanjang jalan",
			milestones: [
				{
					year: "2023",
					title: "Awal yang kecil",
					desc: "YogaRoots dibuka sebagai studio satu ruangan dengan beberapa matras dan niat yang besar.",
				},
				{
					year: "2024",
					title: "Bertumbuh bersama",
					desc: "Komunitas kami mencapai seribu member yang berbagi kecintaan pada gerakan sadar.",
				},
				{
					year: "2025",
					title: "Rumah di Jakarta Selatan",
					desc: "Kami membuka studio khusus, dirancang agar terasa tenang, hangat, dan ramah.",
				},
				{
					year: "Kini",
					title: "Berakar & berkembang",
					desc: "Lebih dari 12.000 member berlatih bersama kami, dan setiap kelas tetap terasa seperti pulang.",
				},
			],
			whyEyebrow: "Mengapa YogaRoots",
			whyTitle: "Praktik yang menemui Anda",
			whyTitleItalic: "di mana pun Anda berada",
			whyDesc:
				"Semua yang kami lakukan dirancang agar yoga terasa mudah diakses, penuh perhatian, dan benar-benar milik Anda.",
			whyFeatures: [
				{
					title: "Pengajar bersertifikat",
					desc: "Instruktur berpengalaman dan bersertifikat menuntun setiap sesi dengan penuh perhatian.",
				},
				{
					title: "Semua level diterima",
					desc: "Dari pemula hingga praktisi mahir, selalu ada kelas yang cocok untuk Anda.",
				},
				{
					title: "Ruang yang nyaman",
					desc: "Ruangan yang tenang dan dirancang dengan baik membantu Anda melepas penat.",
				},
				{
					title: "Komunitas utama",
					desc: "Lebih dari sekadar studio — tempat untuk memiliki, terhubung, dan tumbuh bersama.",
				},
			],
			galleryEyebrow: "Keseharian di studio",
			galleryTitle: "Momen",
			galleryTitleItalic: "yang layak dikenang",
			galleryCta: "Lihat Galeri",
			articlesEyebrow: "Cerita & tips",
			articlesTitle: "Dari",
			articlesTitleItalic: "blog",
			articlesCta: "Lihat semua artikel",
		},

		// Footer
		footerExplore: "Eksplorasi",
		footerAbout: "Tentang Kami",
		footerOurClasses: "Kelas Kami",
		footerInstructors: "Instruktur",
		footerPackages: "Paket",
		footerSupport: "Dukungan",
		footerFAQs: "FAQ",
		footerArticles: "Artikel",
		footerContact: "Kontak",
		footerPackagesPricing: "Paket & Harga",
		footerGetInTouch: "Hubungi Kami",
		footerCommunity: "Mitra Kami",
		siteDesc:
			"Melalui bimbingan kami, Anda tidak hanya memahami postur secara lebih dalam, tetapi juga belajar menumbuhkan kejernihan pikiran dan kedamaian batin.",
		ctaHelpTitle: "Butuh bantuan menemukan aliranmu?",
		ctaHelpDesc:
			"Tidak yakin kelas mana yang cocok untukmu? Chat dengan tim kami di WhatsApp dan kami bantu menemukan praktik yang tepat.",
		ctaHelpBtn: "Chat via WhatsApp →",
		whatsappAdmin: "Admin WhatsApp",
		copyright: "Hak cipta dilindungi.",
		privacyPolicy: "Kebijakan Privasi",
		termsConditions: "Syarat & Ketentuan",
		contactPage: {
			badge: "Respon < 1×24 jam",
			titleA: "Mari",
			titleB: "terhubung",
			heroDesc:
				"Ada pertanyaan soal kelas, paket, atau jadwal? Ceritakan kebutuhanmu — tim kami siap mendengarkan dan membantumu menemukan praktik yang pas.",
			waCta: "Chat WhatsApp",
			mailCta: "Email",
			infoTitle: "Info Kontak",
			infoDesc: "Atau hubungi kami langsung lewat kanal di bawah ini.",
			addressLabel: "Alamat Venue",
			directions: "Petunjuk arah",
			formTitle: "Kirim Pesan",
			namePh: "Nama lengkap",
			emailPh: "Alamat email",
			phonePh: "Nomor WhatsApp aktif",
			msgPh: "Ceritakan kebutuhanmu...",
			captchaPh: "Masukkan jawaban",
			captchaUnavailable: "Verifikasi tidak tersedia",
			sendBtn: "Kirim Pesan",
			sendingBtn: "Mengirim...",
			mapCardTitle: "YogaRoots Studio",
			faqEyebrow: "FAQ",
			faqTitle: "Sering ditanyakan",
			faqEmpty: "Belum ada FAQ.",
		},
		artOfLiving: {
			badge: "Pendiri — Art of Living",
			titleA: "Gurudev Sri Sri",
			titleB: "Ravi Shankar",
			subtitle: "Duta Perdamaian & Pemimpin Kemanusiaan Global",
			heroDesc:
				"Transformasi kearifan kuno menjadi pesan sederhana untuk kehidupan sehari-hari. Visinya tentang masyarakat bebas stres dan bebas kekerasan telah menginspirasi jutaan orang di 180 negara.",
			learnMore: "Pelajari lebih lanjut",
			follow: "Ikuti Gurudev",
			stats: [
				{ v: "800M+", l: "Jiwa tersentuh" },
				{ v: "180", l: "Negara" },
				{ v: "1981", l: "Art of Living lahir" },
			],
			aboutEyebrow: "Tentang Gurudev",
			aboutTitle: "Guru, pemimpin & duta perdamaian",
			aboutP1:
				"Gurudev Sri Sri Ravi Shankar adalah pemimpin spiritual dan kemanusiaan yang diakui dunia. Melalui jaringan organisasi seperti Art of Living dan International Association for Human Values, ia memimpin gerakan global untuk masyarakat bebas stres dan bebas kekerasan — menjangkau lebih dari 800 juta orang.",
			aboutP2:
				"Lahir pada tahun 1956 di India Selatan, ia adalah anak berbakat yang sudah hafal Bhagavad Gita di usia empat tahun dan sering bermeditasi. Ia meraih gelar di bidang literatur Veda dan fisika. Pada tahun 1982, setelah sepuluh hari hening di Shimoga, lahirlah Sudarshan Kriya™ — teknik pernapasan dahsyat yang menjadi inti program Art of Living.",
			honorsEyebrow: "Penghargaan",
			honorsTitle: "Diakui",
			honorsTitleItalic: "dunia",
			bioEyebrow: "Biografi",
			bioTitle: "Perjalanan",
			bioTitleItalic: "yang menggerakkan dunia",
			kriyaTitle: "Sudarshan Kriya™ (SKY)",
			kriyaDesc:
				"Teknik pernapasan khas Gurudev yang terbukti membantu meredakan stres, menenangkan pikiran, dan memperkuat daya tahan tubuh — didokumentasikan oleh riset medis independen dari institusi ternama.",
			exploreClasses: "Lihat kelas YogaRoots",
			// Teks untuk area kosong di panorama gurudev-hero.webp
			missionEyebrow: "Misi Kami",
			mission: "Menuju masyarakat bebas stres dan bebas kekerasan.",
			quote:
				"Kegembiraan adalah tujuan hidup. Pelayanan adalah ekspresinya, dan pengetahuan adalah jalannya.",
			quoteAuthor: "— Gurudev Sri Sri Ravi Shankar",
			engage: "Kenali lebih dekat Gurudev",
			honors: [
				{
					t: "Warga Kehormatan",
					d: "Penghargaan sipil tertinggi di Kolombia, Mongolia, Paraguay, dan Suriname.",
				},
				{
					t: "Padma Vibhushan",
					d: "Penghargaan tahunan tertinggi bagi warga sipil di Republik India.",
				},
				{
					t: "Mediator Perdamaian",
					d: "Diakui pemerintah Sri Lanka, Irak, Kolombia, Venezuela, Pantai Gading, Kamerun, dan India.",
				},
				{
					t: "Doktor Honoris Causa",
					d: "Dari 26 institusi internasional di seluruh dunia.",
				},
				{
					t: "Penghargaan Institusi",
					d: "Penghormatan dari 43 institusi lainnya di berbagai negara.",
				},
				{
					t: "Hari Sri Sri Ravi Shankar",
					d: "Ditetapkan di berbagai kota dan negara sebagai bentuk penghormatan.",
				},
			],
			milestones: [
				{
					y: "1956",
					t: "Lahir di India Selatan",
					d: "Anak berbakat yang hafal Bhagavad Gita di usia 4 tahun dan gemar bermeditasi.",
				},
				{
					y: "1981",
					t: "Art of Living lahir",
					d: "Gerakan pendidikan dan kemanusiaan dimulai, bertumbuh ke 180 negara.",
				},
				{
					y: "1982",
					t: "Sudarshan Kriya™ ditemukan",
					d: "Setelah 10 hari hening di Shimoga — menjadi inti seluruh program.",
				},
				{
					y: "Kini",
					t: "800 juta+ tersentuh",
					d: "Program pengembangan diri, mediasi perdamaian, dan aksi kemanusiaan global.",
				},
			],
			peacePoints: [
				{
					t: "Mediasi konflik bersenjata",
					d: "Dari Kashmir, Assam, dan Bihar hingga Kolombia, Kosovo, Irak, Suriah, dan Pantai Gading — programnya terbukti mengarahkan pelaku konflik ke jalan damai.",
				},
				{
					t: "Relawan global",
					d: "Pesan kedamaian dari dalam diri melahirkan gelombang pelayanan oleh korps relawan raksasa di seluruh dunia.",
				},
				{
					t: "Berbasis riset",
					d: "Teknik Yoga kuno yang disesuaikan untuk kebutuhan modern — meredakan depresi, menurunkan kortisol, dan memperkuat imun.",
				},
			],
		},
	},
};

/* ---------- fallback per-key ke bahasa Inggris ---------- */

type Dict = Record<string, unknown>;

function isPlainObject(value: unknown): value is Dict {
	return typeof value === "object" && value !== null && !Array.isArray(value);
}

/**
 * Gabungkan locale dengan `en` secara rekursif: key yang hilang di locale
 * tersebut diambil dari Inggris.
 *
 * Tanpa ini, menambah key baru hanya ke `en` membuat `id` mencetak
 * "undefined" di template, karena template memakai `t.x` langsung.
 * Konten `id` yang belum lengkap dipulihkan otomatis dari `en`.
 */
function mergeWithEnglish(locale: Dict, english: Dict): Dict {
	const out: Dict = {};

	for (const [key, value] of Object.entries(locale)) {
		out[key] = isPlainObject(value)
			? mergeWithEnglish(value, isPlainObject(english[key]) ? english[key] : {})
			: value;
	}

	for (const [key, value] of Object.entries(english)) {
		if (!(key in out)) out[key] = value;
	}

	return out;
}

type LocaleKey = (typeof LOCALES)[number];

const english = (rawTranslations as Record<LocaleKey, Dict>).en;

const merged = {} as Record<LocaleKey, Dict>;
for (const locale of LOCALES) {
	const dict = (rawTranslations as Record<LocaleKey, Dict>)[locale];
	merged[locale] = locale === "en" ? english : mergeWithEnglish(dict, english);
}

export const translations = merged;
