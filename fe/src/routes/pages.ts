import { Router } from "express";
import {
	getHomeFaqs,
	localizeFaqCategories,
	localizeFaqs,
} from "../data/faqContent.js";
import { getPageBatch } from "../services/batchService.js";
import type {
	BannerItem,
	CmsPage,
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

interface HomeArticle {
	slug: string;
	title: string;
	image: string;
	date: string;
}

interface HomePackage {
	slug: string;
	name: string;
	description: string;
	coverUrl: string;
	isPopular: boolean;
	startingPrice: number | null;
	features: string[];
}

interface ArticleView extends Record<string, unknown> {
	slug: string;
	title: string;
	excerpt: string;
	content: string;
	image: string;
	category: string;
	date: string;
	read: string;
	publishedAt: string;
	modifiedAt: string;
}

interface VenueLink {
	name: string;
	slug: string;
	address: string;
	image: string;
}

function firstString(...values: unknown[]): string {
	for (const value of values) {
		if (typeof value === "string" && value.trim()) return value.trim();
	}
	return "";
}

async function optionalCmsPage(slug: string): Promise<CmsPage | null> {
	const data = await getPageBatch("page-detail", { slug });
	const page = data?.page;
	if (!page || typeof page !== "object") return null;

	return {
		...(page as CmsPage),
		content: sanitizeRichHtml(String((page as CmsPage).content ?? "")),
	};
}

function metaFrom(value: unknown, max = 160): string {
	return firstString(String(value ?? "").replace(/<[^>]*>/g, " ")).slice(
		0,
		max,
	);
}

function isoArticleDate(value: unknown): string {
	const datePart = firstString(value).slice(0, 10);
	return /^\d{4}-\d{2}-\d{2}$/.test(datePart) ? datePart : "";
}

function formatArticleDate(value: unknown, locale: string): string {
	const datePart = isoArticleDate(value);
	if (!datePart) return "";
	const parsed = new Date(`${datePart}T00:00:00Z`);
	if (!parsed.toISOString().startsWith(datePart)) return "";
	return new Intl.DateTimeFormat(locale, {
		day: "numeric",
		month: "short",
		year: "numeric",
		timeZone: "UTC",
	}).format(parsed);
}

function readingTime(content: unknown, locale: string): string {
	const plain = firstString(content)
		.replace(/<[^>]*>/g, " ")
		.replace(/\s+/g, " ")
		.trim();
	const words = plain ? plain.split(" ").length : 0;
	const minutes = Math.max(1, Math.ceil(words / 200));
	return locale.toLowerCase().startsWith("id")
		? `${minutes} menit baca`
		: `${minutes} min read`;
}

function normalizeBlogPost(value: unknown, locale: string): ArticleView | null {
	if (!value || typeof value !== "object") return null;
	const article = value as Record<string, unknown>;
	const slug = firstString(article.slug);
	const title = firstString(article.title);
	if (!slug || !title) return null;

	const dateValue = firstString(
		article.published_at,
		article.scheduled_at,
		article.created_at,
		article.date,
	);
	const content = firstString(article.content);

	return {
		...article,
		slug,
		title,
		excerpt: firstString(article.excerpt),
		content,
		image: firstString(article.image, article.featured_image),
		category: firstString(article.category),
		date: formatArticleDate(dateValue, locale),
		read: firstString(article.read) || readingTime(content, locale),
		publishedAt: isoArticleDate(
			article.published_at || article.scheduled_at || article.created_at,
		),
		modifiedAt: isoArticleDate(article.updated_at),
	};
}

function normalizeBlogPosts(value: unknown, locale: string): ArticleView[] {
	if (!Array.isArray(value)) return [];
	return value.flatMap((item): ArticleView[] => {
		const post = normalizeBlogPost(item, locale);
		return post ? [post] : [];
	});
}

function normalizeVenueLinks(value: unknown): VenueLink[] {
	if (!Array.isArray(value)) return [];
	return value.flatMap((item): VenueLink[] => {
		if (!item || typeof item !== "object") return [];
		const venue = item as Record<string, unknown>;
		const name = firstString(venue.name);
		const slug = firstString(venue.slug, venue.uuid);
		if (!name || !slug) return [];
		return [
			{
				name,
				slug,
				address: firstString(venue.address),
				image: firstString(venue.image),
			},
		];
	});
}

function getHomeArticles(value: unknown, locale: string): HomeArticle[] {
	if (!Array.isArray(value)) return [];

	return value
		.flatMap((item): HomeArticle[] => {
			if (!item || typeof item !== "object") return [];
			const article = item as Record<string, unknown>;
			if (
				typeof article.slug !== "string" ||
				typeof article.title !== "string"
			) {
				return [];
			}

			const dateValue =
				typeof article.scheduled_at === "string"
					? article.scheduled_at
					: article.created_at;
			const datePart =
				typeof dateValue === "string" ? dateValue.slice(0, 10) : "";
			let date = "";
			if (/^\d{4}-\d{2}-\d{2}$/.test(datePart)) {
				const parsedDate = new Date(`${datePart}T00:00:00Z`);
				if (parsedDate.toISOString().startsWith(datePart)) {
					date = new Intl.DateTimeFormat(locale, {
						day: "numeric",
						month: "short",
						timeZone: "UTC",
						year: "numeric",
					}).format(parsedDate);
				}
			}

			return [
				{
					slug: article.slug,
					title: article.title,
					image:
						typeof article.featured_image === "string"
							? article.featured_image
							: "",
					date,
				},
			];
		})
		.slice(0, 3);
}

function getHomePackages(value: unknown): HomePackage[] {
	if (!Array.isArray(value)) return [];

	const homePackages = value.flatMap((item): HomePackage[] => {
		if (!item || typeof item !== "object") return [];
		const packageItem = item as Record<string, unknown>;
		if (
			typeof packageItem.slug !== "string" ||
			typeof packageItem.name !== "string"
		) {
			return [];
		}

		const options = Array.isArray(packageItem.options)
			? packageItem.options
			: [];
		const prices = options.flatMap((option): number[] => {
			if (!option || typeof option !== "object") return [];
			const optionItem = option as Record<string, unknown>;
			const price = Number(optionItem.final_price ?? optionItem.price);
			return Number.isFinite(price) && price > 0 ? [price] : [];
		});
		const features = Array.isArray(packageItem.features)
			? packageItem.features.flatMap((feature): string[] => {
					if (!feature || typeof feature !== "object") return [];
					const featureItem = feature as Record<string, unknown>;
					const text = featureItem.feature ?? featureItem.name;
					return typeof text === "string" && text.trim() ? [text] : [];
				})
			: [];

		return [
			{
				slug: packageItem.slug,
				name: packageItem.name,
				description:
					typeof packageItem.description === "string"
						? packageItem.description
						: "",
				coverUrl:
					typeof packageItem.cover_url === "string"
						? packageItem.cover_url
						: "",
				isPopular:
					packageItem.is_popular === true ||
					packageItem.is_popular === 1 ||
					packageItem.is_popular === "1",
				startingPrice: prices.length ? Math.min(...prices) : null,
				features: features.slice(0, 4),
			},
		];
	});

	const popularIndex = homePackages.findIndex((item) => item.isPopular);
	if (popularIndex >= 0 && popularIndex !== 1 && homePackages.length > 1) {
		const [popularPackage] = homePackages.splice(popularIndex, 1);
		homePackages.splice(1, 0, popularPackage);
	}

	return homePackages.slice(0, 3);
}

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
		const weddingContentReady = data?.weddingContentReady === true;
		const sliderBanners = weddingContentReady
			? getHomeSliderBanners(data?.sliderBanners)
			: [];

		res.render("pages/home", {
			title: isID
				? "Wedding Organizer & Paket Pernikahan | Sagita Wedding"
				: "Wedding Planner & Organizer | Sagita Wedding",
			metaDescription: isID
				? "Sagita Wedding Planner membantu merencanakan pernikahan, mengoordinasikan vendor, memilih venue, dan mendampingi hari perayaan Anda."
				: "Plan your wedding with Sagita Wedding Planner. Explore venues, coordinate trusted vendors, and get thoughtful support on your wedding day.",
			heroNav: sliderBanners.length > 0,
			heroImage: sliderBanners[0]?.gambar ?? "",
			pageScript: "home",
			assetV: `${res.locals.assetV}-home-preparation1`,
			weddingContentReady,
			articles: weddingContentReady
				? getHomeArticles(data?.articles, isID ? "id-ID" : "en-US")
				: [],
			packages: weddingContentReady ? getHomePackages(data?.packages) : [],
			sliderBanners,
			venueDataLoaded: weddingContentReady && Array.isArray(data?.venues),
			venues:
				weddingContentReady && Array.isArray(data?.venues)
					? data.venues.slice(0, 4)
					: [],
			testimonials:
				weddingContentReady && Array.isArray(data?.testimonials)
					? data.testimonials.slice(0, 10)
					: [],
			homeFaqs: getHomeFaqs(data?.faqs, isID ? "id" : "en"),
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

router.get(
	"/formulir-konsultasi",
	async (_req: Request, res: Response, next: NextFunction) => {
		try {
			const isID = res.locals.lang === "id";
			const page = await optionalCmsPage("formulir-konsultasi").catch(
				() => null,
			);
			res.render("pages/consultation-form", {
				title: firstString(
					page?.title,
					isID
						? "Konsultasi Wedding Organizer & Wedding Planner | Sagita"
						: "Wedding Planning Consultation | Sagita Wedding Planner",
				),
				metaDescription: firstString(
					metaFrom(page?.excerpt),
					isID
						? "Konsultasikan rencana pernikahan dengan Sagita Wedding Planner. Bahas layanan Wedding Organizer, paket, koordinasi hari pernikahan, vendor, dan anggaran."
						: "Plan your wedding with Sagita Wedding Planner. Discuss wedding planning, day-of coordination, vendor recommendations, packages, and budget.",
				),
				assetV: `${res.locals.assetV}-localcaptchaimage1`,
				pageScript: "consultation-form",
				cmsPage: page,
			});
		} catch (err) {
			next(err);
		}
	},
);

// ---------- About ----------
router.get(
	"/about",
	async (_req: Request, res: Response, next: NextFunction) => {
		try {
			const isID = res.locals.lang === "id";
			const page = await optionalCmsPage("about").catch(() => null);
			res.render("pages/about", {
				title: firstString(
					page?.title,
					isID
						? "Tentang Sagita Wedding Planner | Wedding Organizer"
						: "About Sagita Wedding Planner | Wedding Organizer",
				),
				metaDescription: firstString(
					metaFrom(page?.excerpt),
					isID
						? "Kenali Sagita Wedding Planner, wedding organizer yang membantu menyusun konsep, anggaran, vendor, dan koordinasi hari pernikahan sesuai cerita Anda."
						: "Meet Sagita Wedding Planner, a wedding organizer helping you plan your concept, budget, vendors, and wedding-day coordination around your story.",
				),
				assetV: `${res.locals.assetV}-about-wedding-icons1`,
				pageSchemaType: "AboutPage",
				heroNav: true,
				cmsPage: page,
			});
		} catch (err) {
			next(err);
		}
	},
);

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
			const rawPageDescription =
				firstString(page.excerpt, page.description) ||
				firstString(page.content)
					.replace(/<[^>]*>/g, " ")
					.replace(/\s+/g, " ")
					.trim()
					.slice(0, 160);
			const pageDescription =
				rawPageDescription.length >= 40
					? rawPageDescription
					: res.locals.lang === "id"
						? `Baca ${page.title} dan informasi lengkapnya dari Sagita Wedding Planner.`
						: `Read ${page.title} and learn more from Sagita Wedding Planner.`;

			res.render("pages/page-detail", {
				title: `${page.title} | Sagita Wedding`,
				metaDescription: pageDescription,
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
			const isID = res.locals.lang === "id";
			const posts = normalizeBlogPosts(
				data?.articles,
				isID ? "id-ID" : "en-US",
			);
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
			const isID = res.locals.lang === "id";
			const locale = isID ? "id-ID" : "en-US";
			const rawPost = normalizeBlogPost(data?.post, locale);
			if (!rawPost)
				return res.status(404).render("pages/404", {
					title: "Article Not Found",
					robots: "noindex, nofollow",
					heroNav: true,
				});

			const excerptText = rawPost.excerpt
				.replace(/<[^>]*>/g, " ")
				.replace(/\s+/g, " ")
				.trim();
			const descriptionText =
				excerptText.length >= 40
					? excerptText
					: isID
						? `Baca ${rawPost.title} — panduan dan inspirasi pernikahan dari Sagita Wedding Planner.`
						: `Read ${rawPost.title} — wedding planning guidance and inspiration from Sagita Wedding Planner.`;
			const articleImage = rawPost.image || undefined;
			const publishedAt = rawPost.publishedAt || undefined;

			res.render("pages/blog-detail", {
				title: `${rawPost.title} | Sagita Wedding`,
				metaDescription: descriptionText,
				ogType: "article",
				ogImage: articleImage,
				articleMeta: {
					headline: rawPost.title,
					description: descriptionText,
					image: articleImage,
					section: rawPost.category || undefined,
					datePublished: publishedAt,
					dateModified: rawPost.modifiedAt || publishedAt,
				},
				pageScript: "blog-detail",
				post: { ...rawPost, content: sanitizeRichHtml(rawPost.content) },
				related: normalizeBlogPosts(data?.related, locale).slice(0, 3),
				popularPosts: normalizeBlogPosts(data?.popular, locale).slice(0, 3),
				sidebarVenues: normalizeVenueLinks(data?.venues).slice(0, 3),
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
	["/gallery", "/portofolio"],
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
