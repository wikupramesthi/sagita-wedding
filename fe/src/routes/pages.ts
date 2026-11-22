import { Router } from "express";
import { localizeFaqCategories, localizeFaqs } from "../data/faqContent.js";
import { getPageBatch } from "../services/batchService.js";
import type {
	BannerItem,
	NextFunction,
	Request,
	Response,
} from "../types/index.js";
import { toApiError } from "../utils/errors.js";
import { sanitizeRichHtml } from "../utils/sanitize.js";
import {
	cleanDate,
	cleanPage,
	cleanSlug,
	cleanText,
} from "../utils/validate.js";

const router = Router();

function getHomeSliderBanners(value: unknown): BannerItem[] {
	if (!Array.isArray(value)) return [];

	return value
		.filter((item): item is BannerItem => {
			if (!item || typeof item !== "object" || !("gambar" in item)) {
				return false;
			}

			const banner = item as Record<string, unknown>;
			const imageUrl = banner.gambar;
			return (
				banner.posisi === "slider" &&
				typeof imageUrl === "string" &&
				/^(https?:\/\/|\/(?!\/))/i.test(imageUrl) &&
				!/\/images\/default\.png(?:[?#]|$)/i.test(imageUrl)
			);
		})
		.slice(0, 5);
}

// ---------- Home ----------
router.get("/", async (_req: Request, res: Response, next: NextFunction) => {
	try {
		const data = await getPageBatch("home").catch(() => null);
		const isID = res.locals.lang === "id";
		const sliderBanners = getHomeSliderBanners(data?.sliderBanners);

		res.render("pages/home", {
			title: isID
				? "Wedding Organizer & Paket Pernikahan | Sagita Wedding"
				: "Wedding Planner & Organizer | Sagita Wedding",
			metaDescription: isID
				? "Sagita Wedding Planner membantu merencanakan pernikahan, mengoordinasikan vendor, memilih venue, dan mendampingi hari perayaan Anda."
				: "Plan your wedding with Sagita Wedding Planner. Explore venues, coordinate trusted vendors, and get thoughtful support on your wedding day.",
			heroNav: sliderBanners.length > 0,
			pageScript: "home",
			assetV: `${res.locals.assetV}-home-slider21`,
			sliderBanners,
			venueDataLoaded: Array.isArray(data?.venues),
			venues: Array.isArray(data?.venues) ? data.venues.slice(0, 4) : [],
			packages: data?.packages || [],
			events: data?.events || [],
			testimonials: Array.isArray(data?.testimonials)
				? data.testimonials.slice(0, 10)
				: [],
		});
	} catch (err) {
		next(err);
	}
});

router.get(
	"/venues",
	async (_req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getPageBatch("venues");
			res.render("pages/venues", {
				title:
					res.locals.lang === "id"
						? "Venue Pernikahan | Sagita Wedding Planner"
						: "Wedding Venues | Sagita Wedding Planner",
				metaDescription: res.locals.t.venuesDescription,
				assetV: `${res.locals.assetV}-venues1`,
				pageScript: "venues",
				heroNav: true,
				heroNavLight: true,
				venues: Array.isArray(data?.venues) ? data.venues : [],
			});
		} catch (err) {
			next(err);
		}
	},
);

router.get(["/classes", "/schedules"], (_req: Request, res: Response) => {
	res.redirect(301, "/venues");
});

router.get("/formulir-konsultasi", (_req: Request, res: Response) => {
	const isID = res.locals.lang === "id";
	res.render("pages/consultation-form", {
		title: isID
			? "Konsultasi Wedding Organizer & Wedding Planner | Sagita"
			: "Wedding Planning Consultation | Sagita Wedding Planner",
		metaDescription: isID
			? "Konsultasikan rencana pernikahan dengan Sagita Wedding Planner. Bahas layanan Wedding Organizer, paket, koordinasi hari pernikahan, vendor, dan anggaran."
			: "Plan your wedding with Sagita Wedding Planner. Discuss wedding planning, day-of coordination, vendor recommendations, packages, and budget.",
		assetV: `${res.locals.assetV}-localcaptchaimage1`,
		pageScript: "consultation-form",
	});
});

// ---------- About ----------
router.get("/about", (_req: Request, res: Response) => {
	const isID = res.locals.lang === "id";
	res.render("pages/about", {
		title: isID
			? "Tentang Sagita Wedding Planner | Wedding Organizer"
			: "About Sagita Wedding Planner | Wedding Organizer",
		metaDescription: isID
			? "Kenali Sagita Wedding Planner, wedding organizer yang membantu menyusun konsep, anggaran, vendor, dan koordinasi hari pernikahan sesuai cerita Anda."
			: "Meet Sagita Wedding Planner, a wedding organizer helping you plan your concept, budget, vendors, and wedding-day coordination around your story.",
		pageSchemaType: "AboutPage",
	});
});

router.get("/art-of-living", (_req: Request, res: Response) => {
	res.redirect(301, "/about");
});

router.get("/classes/:slug", (_req: Request, res: Response) => {
	res.redirect(301, "/venues");
});

// ---------- CMS page ----------
router.get(
	"/pages/:slug",
	async (req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getPageBatch("page-detail", {
				slug: cleanSlug(req.params.slug, "page"),
			});
			const page = data?.page;
			if (!page) {
				throw Object.assign(new Error("Page not found"), { status: 404 });
			}
			res.render("pages/page-detail", {
				title: page.title,
				pageScript: "page-detail",
				page: { ...page, content: sanitizeRichHtml(page.content) },
			});
		} catch (e) {
			const err = toApiError(e);
			next(
				err.status === 404
					? Object.assign(new Error("Page not found"), { status: 404 })
					: err,
			);
		}
	},
);

// ---------- Vendors ----------
router.get(
	"/vendors",
	async (_req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getPageBatch("vendors");
			const isID = res.locals.lang === "id";
			res.render("pages/vendors", {
				title: isID
					? "Vendor Pernikahan Pilihan | Sagita Wedding Planner"
					: "Wedding Vendors | Sagita Wedding Planner",
				metaDescription: isID
					? "Jelajahi vendor pernikahan berdasarkan kategori dan konsultasikan kebutuhan Anda bersama tim Sagita Wedding Planner."
					: "Explore wedding vendors by category and discuss your plans with the Sagita Wedding Planner team.",
				vendors: data?.vendors || [],
				assetV: `${res.locals.assetV}-vendors-warm4`,
				pageScript: "vendors",
				heroNav: true,
				heroNavLight: true,
			});
		} catch (err) {
			next(err);
		}
	},
);

// ---------- Legacy route (301) ----------
router.get("/instructors", (_req: Request, res: Response) => {
	res.redirect(301, "/vendors");
});

// ---------- Blog ----------
router.get(
	"/blog",
	async (_req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getPageBatch("blog");
			const posts = Array.isArray(data?.articles) ? data.articles : [];
			const isID = res.locals.lang === "id";
			res.render("pages/blog", {
				title: isID
					? "Artikel Pernikahan & Tips Wedding | Sagita Wedding"
					: "Wedding Articles & Planning Tips | Sagita Wedding",
				metaDescription: isID
					? "Baca artikel dan tips pernikahan dari Sagita Wedding Planner, mulai dari perencanaan, venue, vendor, hingga persiapan hari istimewa."
					: "Read wedding planning articles from Sagita Wedding Planner, with ideas for venues, vendors, preparation, and your wedding day.",
				pageScript: "blog",
				posts,
				totalPosts: data?.total || posts.length,
			});
		} catch (err) {
			next(err);
		}
	},
);

router.get(
	"/blog/:slug",
	async (req: Request, res: Response, next: NextFunction) => {
		try {
			const slug = cleanSlug(req.params.slug, "article");
			const data = await getPageBatch("blog-detail", { slug });
			const post = data?.post;
			if (!post)
				return res.status(404).render("pages/404", {
					title: "Article Not Found",
					robots: "noindex, nofollow",
					heroNav: true,
				});
			res.render("pages/blog-detail", {
				title: `${post.title} | Sagita Wedding`,
				metaDescription:
					typeof post.excerpt === "string" && post.excerpt.trim()
						? post.excerpt
								.replace(/<[^>]*>/g, " ")
								.replace(/\s+/g, " ")
								.trim()
						: undefined,
				pageScript: "blog-detail",
				post: { ...post, content: sanitizeRichHtml(post.content) },
				related: Array.isArray(data?.related) ? data.related : [],
			});
		} catch (e) {
			const err = toApiError(e);
			if (err.status === 404 || err.status === 400) {
				return res.status(404).render("pages/404", {
					title: "Article Not Found",
					robots: "noindex, nofollow",
					heroNav: true,
				});
			}
			next(err);
		}
	},
);

// ---------- Packages ----------
router.get(
	"/packages",
	async (req: Request, res: Response, next: NextFunction) => {
		try {
			const params = {
				search: cleanText(req.query.search, { max: 80, label: "Search" }),
				filter: cleanText(req.query.filter, { max: 40, label: "Filter" }),
				sort: cleanText(req.query.sort, { max: 40, label: "Sort" }),
				page: cleanPage(req.query.page),
				per_page: 10,
			};
			const data = await getPageBatch("packages", params);
			const packages = Array.isArray(data?.packages) ? data.packages : [];
			const meta = data?.meta || {};
			const isID = res.locals.lang === "id";
			res.render("pages/packages", {
				title: isID
					? "Paket Pernikahan | Sagita Wedding Planner"
					: "Wedding Planning Packages | Sagita Wedding Planner",
				metaDescription: isID
					? "Jelajahi paket pernikahan Sagita Wedding Planner dan temukan layanan yang sesuai dengan rencana, kebutuhan, dan hari istimewa Anda."
					: "Explore Sagita Wedding Planner packages and find wedding planning services that fit your plans, priorities, and celebration.",
				packages,
				search: params.search,
				filter: params.filter,
				sort: params.sort,
				totalPackages: Number(meta.total) || packages.length,
				currentPage: Number(meta.current_page) || params.page,
				totalPages: Number(meta.last_page) || 1,
				error: null,
			});
		} catch (err) {
			next(err);
		}
	},
);

router.get("/packages/:slug", async (req: Request, res: Response) => {
	try {
		const data = await getPageBatch("package-detail", {
			slug: cleanSlug(req.params.slug, "package"),
		});
		const packageDetail = data?.package;
		if (!packageDetail) throw new Error("Package not found");
		res.render("pages/packages-detail", {
			title: `${packageDetail.name} | Wedding Package | Sagita Wedding`,
			metaDescription:
				typeof packageDetail.description === "string"
					? packageDetail.description
							.replace(/<[^>]*>/g, " ")
							.replace(/\s+/g, " ")
							.trim()
					: undefined,
			package: packageDetail,
			error: null,
		});
	} catch (_err) {
		return res.status(404).render("pages/packages-detail", {
			title: "Package Not Found",
			robots: "noindex, nofollow",
			package: null,
			error: "Package not found.",
		});
	}
});

// ---------- Events ----------
router.get(
	"/event",
	async (req: Request, res: Response, next: NextFunction) => {
		try {
			const params = {
				search: cleanText(req.query.search, { max: 80, label: "Search" }),
				filter: cleanText(req.query.filter, { max: 40, label: "Filter" }),
				date_from: req.query.date_from ? cleanDate(req.query.date_from) : "",
				date_to: req.query.date_to ? cleanDate(req.query.date_to) : "",
				page: cleanPage(req.query.page),
				per_page: 10,
			};
			const data = await getPageBatch("events", params);
			const events = Array.isArray(data?.events) ? data.events : [];
			const meta = data?.meta || {};
			const isID = res.locals.lang === "id";
			res.render("pages/events", {
				title: isID
					? "Acara & Workshop | Sagita Wedding Planner"
					: "Events & Workshops | Sagita Wedding Planner",
				metaDescription: isID
					? "Lihat acara dan workshop Sagita Wedding Planner untuk mendapatkan inspirasi dan informasi seputar persiapan pernikahan."
					: "Explore Sagita Wedding Planner events and workshops for ideas and guidance on preparing for your wedding.",
				events,
				search: params.search,
				filter: params.filter,
				date_from: params.date_from,
				date_to: params.date_to,
				totalEvents: Number(meta.total) || events.length,
				totalPages: Number(meta.last_page) || 1,
				currentPage: Number(meta.current_page) || params.page,
				error: null,
			});
		} catch (err) {
			next(err);
		}
	},
);

// ---------- Gallery (hanya foto asli API; tanpa placeholder) ----------
router.get(
	"/gallery",
	async (_req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getPageBatch("gallery").catch(() => null);
			const gallery = Array.isArray(data?.gallery) ? data.gallery : [];
			const isID = res.locals.lang === "id";
			res.render("pages/gallery", {
				title: isID
					? "Galeri Pernikahan | Sagita Wedding Planner"
					: "Wedding Gallery | Sagita Wedding Planner",
				metaDescription: isID
					? "Lihat galeri Sagita Wedding Planner untuk inspirasi suasana dan detail perayaan pernikahan."
					: "Explore the Sagita Wedding Planner gallery for inspiration from wedding celebrations and thoughtful details.",
				gallery,
			});
		} catch (err) {
			next(err);
		}
	},
);

// ---------- FAQ ----------
router.get("/faq", async (_req: Request, res: Response) => {
	const data = await getPageBatch("faq").catch(() => null);
	const locale = res.locals.lang === "id" ? "id" : "en";
	res.render("pages/faq", {
		title:
			locale === "id"
				? "Pertanyaan Umum Seputar Pernikahan | Sagita Wedding Planner"
				: "Wedding Planning FAQs | Sagita Wedding Planner",
		metaDescription:
			locale === "id"
				? "Temukan jawaban seputar layanan Wedding Organizer, paket pernikahan, pembayaran, vendor, dan koordinasi hari pernikahan."
				: "Find answers about wedding planning, packages, payments, vendors, and wedding-day coordination with Sagita Wedding Planner.",
		heroNav: true,
		pageScript: "faq",
		faqs: localizeFaqs(data?.faqs, locale),
		categories: localizeFaqCategories(data?.categories, locale),
	});
});

export default router;
