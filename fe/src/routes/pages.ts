import { Router } from "express";
import { localizeFaqCategories, localizeFaqs } from "../data/faqContent.js";
import { getPageBatch } from "../services/batchService.js";
import type { NextFunction, Request, Response } from "../types/index.js";
import { toApiError } from "../utils/errors.js";
import { sanitizeRichHtml } from "../utils/sanitize.js";
import {
	cleanDate,
	cleanPage,
	cleanSlug,
	cleanText,
} from "../utils/validate.js";

const router = Router();

// ---------- Home ----------
router.get("/", async (_req: Request, res: Response, next: NextFunction) => {
	try {
		const data = await getPageBatch("home").catch(() => null);

		res.render("pages/home", {
			title: "YogaRoots � Find Balance in Every Breath",
			pageScript: "home",
			classes: data?.classes || [],
			packages: data?.packages || [],
			events: data?.events || [],
			testimonials: Array.isArray(data?.testimonials)
				? data.testimonials.slice(0, 3)
				: [],
		});
	} catch (err) {
		next(err);
	}
});

router.get(
	"/classes",
	async (_req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getPageBatch("classes").catch(() => null);
			const classes = Array.isArray(data?.classes) ? data.classes : [];
			res.render("pages/classes", {
				title: "YogaRoots � Class Guide",
				metaDescription: res.locals.t.classGuideDesc,
				pageScript: "classes",
				classes,
			});
		} catch (err) {
			next(err);
		}
	},
);

// ---------- Schedules (weekly timetable) ----------
router.get("/schedules", (_req: Request, res: Response) => {
	res.render("pages/schedules", {
		title: "YogaRoots � Class Schedules",
		metaDescription: res.locals.t.classesDesc,
		pageScript: "schedules",
	});
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

// ---------- About (Art of Living / Gurudev) ----------
router.get("/about", (_req: Request, res: Response) => {
	const isID = res.locals.lang === "id";
	res.render("pages/art-of-living", {
		title: isID
			? "Art of Living � Gurudev Sri Sri Ravi Shankar | YogaRoots"
			: "Art of Living � Gurudev Sri Sri Ravi Shankar | YogaRoots",
		metaDescription: isID
			? "Mengenal Gurudev Sri Sri Ravi Shankar, pendiri Art of Living � duta perdamaian, pencipta Sudarshan Kriya, menjangkau 800 juta+ jiwa di 180 negara."
			: "Meet Gurudev Sri Sri Ravi Shankar, founder of Art of Living � ambassador of peace, creator of Sudarshan Kriya, reaching 800M+ lives across 180 countries.",
		heroNav: true,
	});
});

router.get("/art-of-living", (_req: Request, res: Response) => {
	res.redirect(301, "/about");
});

// ---------- Class detail ----------
router.get(
	"/classes/:slug",
	async (req: Request, res: Response, next: NextFunction) => {
		try {
			const data = await getPageBatch("class-detail", {
				slug: cleanSlug(req.params.slug, "class"),
			});
			const cls = data?.class;
			if (!cls)
				return res.status(404).render("pages/404", {
					title: "Class Not Found",
					robots: "noindex, nofollow",
					heroNav: true,
				});
			res.render("pages/class-detail", { title: cls.name, cls });
		} catch (e) {
			const err = toApiError(e);
			if (err.status === 404 || err.status === 400) {
				return res.status(404).render("pages/404", {
					title: "Class Not Found",
					robots: "noindex, nofollow",
					heroNav: true,
				});
			}
			next(err);
		}
	},
);

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
			res.render("pages/blog", {
				title: "Yoga Articles & Tips",
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
				title: post.title,
				pageScript: "blog-detail",
				post: { ...post, content: sanitizeRichHtml(post.content) },
				related: Array.isArray(data?.related) ? data.related : [],
				latestClasses: Array.isArray(data?.latest_classes)
					? data.latest_classes
					: [],
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
			res.render("pages/packages", {
				title: "Membership Packages",
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
			title: packageDetail.name,
			package: packageDetail,
			error: null,
		});
	} catch (_err) {
		return res.status(404).render("pages/packages-detail", {
			title: "Package Not Found",
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
			res.render("pages/events", {
				title: "Event & Workshop",
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
			res.render("pages/gallery", { title: "Our Gallery", gallery });
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
