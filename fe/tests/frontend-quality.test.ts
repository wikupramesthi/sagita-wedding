import { readFileSync } from "node:fs";
import { describe, expect, it } from "vitest";

const read = (path: string) =>
	readFileSync(new URL(path, import.meta.url), "utf8");

const customCss = read("../public/css/custom.css");
const inputCss = read("../public/css/input.css");
const head = read("../src/components/Head.astro");
const baseLayout = read("../src/layouts/BaseLayout.astro");
const navbar = read("../src/components/Navbar.astro");
const blogDetail = read("../src/pages/blog/[slug].astro");
const portfolio = read("../src/pages/portofolio.astro");
const vendors = read("../src/pages/vendors.astro");
const portfolioScript = read("../src/lib/browser/pages/portofolio.ts");
const homeScript = read("../src/lib/browser/pages/home.ts");
const analyticsConsent = read("../src/components/AnalyticsConsent.astro");
const exploreLinks = read("../src/components/ExploreLinks.astro");
const middleware = read("../src/middleware.ts");
const sitemap = read("../src/pages/sitemap.xml.ts");

describe("kualitas frontend", () => {
	it("punya breakpoint mobile dan desktop untuk halaman utama", () => {
		expect(customCss).toContain("@media (max-width: 640px)");
		expect(customCss).toContain("@media (min-width: 1024px)");
		expect(customCss).toContain(".home-packages-section");
		expect(customCss).toContain(".blog-grid");
		expect(customCss).toContain(".blog-detail-layout");
	});

	it("carousel testimoni membesarkan foto aktif dengan lima foto pilihan", () => {
		expect(customCss).toContain("flex: 1 0 calc(11% - 0.275rem)");
		expect(customCss).toContain("flex: 4 0 calc(45% - 1.125rem)");
		expect(customCss).toContain("flex: 1 0 calc(15% - 0.225rem)");
		expect(customCss).toContain("flex: 4 0 calc(55% - 0.825rem)");
		expect(customCss).toContain("flex: 0 0 100%");
		expect(customCss).toContain("scroll-snap-type: x mandatory");
		expect(customCss).toMatch(
			/\.home-testimonial-panel:hover \.home-testimonial-panel__image\s*\{[^}]*filter:\s*grayscale\(0\)/,
		);
		expect(homeScript).toContain("activePanel.scrollIntoView");
		expect(homeScript).not.toContain("orderedPanels");
	});

	it("header hero transparan sampai halaman discroll", () => {
		expect(customCss).toContain(
			"rgba(255, 254, 252, var(--nav-surface-opacity, 0))",
		);
		expect(customCss).not.toContain(
			"body.site-public header[data-home] > div > nav",
		);
		expect(portfolio).toContain("heroNav={true}");
		expect(portfolio).not.toContain("html.sw-lp-html #navbar nav");
		expect(portfolio).not.toContain("rgba(255, 254, 252, 0.94)");
	});

	it("drawer mobile memakai latar terang dan teks gelap di semua halaman", () => {
		expect(navbar).toContain("border border-sand-200 bg-white");
		expect(navbar).toContain("border-sand-100");
		expect(navbar).toContain("hover:bg-sand-100");
		expect(navbar).not.toContain("hover:bg-white/10");
		expect(navbar).not.toContain("border-white/15");
		expect(navbar).not.toContain("text-white/30");
		expect(customCss).toContain(".mobile-drawer a");
		expect(customCss).toContain(".mobile-drawer a.bg-sage-800");
	});

	it("video hero portofolio baru dimuat setelah halaman idle", () => {
		expect(portfolio).toContain('preload="none"');
		expect(portfolio).toContain('fetchpriority="high"');
		expect(portfolioScript).toContain("let izinVideo = false");
		expect(portfolioScript).toContain("requestIdleCallback");
		expect(portfolioScript).toContain('video.preload = "metadata"');
		expect(portfolioScript).not.toContain('video.preload = "auto"');
		expect(portfolioScript).toContain(
			"if (!video || !bolehVideo || !izinVideo) return null;",
		);
	});

	it("spacing section memakai rhythm global yang seragam", () => {
		expect(customCss).toContain("main:not(.consultation-shell)");
		expect(customCss).toContain(
			"> section:not(.relative.overflow-hidden):not(.blog-hero)",
		);
		expect(customCss).toContain("> section.site-section--compact");
		expect(customCss).not.toContain(':not([class*="py-"])');
		expect(vendors).toContain("pb-14 pt-28 sm:pb-20 sm:pt-32");
		expect(vendors).not.toContain("padding-top:clamp(7rem,14vh,8rem)");
		expect(vendors).not.toContain("lg:py-14");
	});

	it("tautan WhatsApp portofolio menggunakan kontak terkonfigurasi", () => {
		expect(
			portfolio.match(/href=\{contactLinks\.whatsappLink\}/g),
		).toHaveLength(2);
		expect(portfolio).not.toContain("6289666613131");
	});

	it("galeri penerima tamu dan referensi asetnya dihapus dari portofolio", () => {
		expect(portfolio).not.toContain("sw-bt__kolase");
		expect(portfolio).not.toContain("gb-selfie-checkin.webp");
		expect(portfolio).not.toContain("gb-print-ticket.webp");
		expect(portfolio).not.toContain("gb-pencatatan-hadiah.webp");
		expect(portfolio).not.toContain("gb-table-food-souvenir.webp");
	});

	it("sidebar detail blog sticky dan tetap turun di bawah konten di mobile", () => {
		expect(customCss).toContain(".blog-detail-aside");
		expect(customCss).toContain("position: sticky");
		expect(blogDetail).toContain('class="blog-detail-main"');
		expect(blogDetail).toContain('class="blog-detail-aside"');
	});

	it("aset critical memakai viewport, preload, cache-busting, font swap, dan script bundling", () => {
		expect(head).toContain('name="viewport"');
		expect(head).toContain(
			'href={`/css/output.css?v=${styleVersion}`} as="style"',
		);
		expect(head).toContain(
			'href={`/css/custom.min.css?v=${styleVersion}`} as="style"',
		);
		expect(head).toContain('href={heroPreload} fetchpriority="high"');
		expect(head).toContain("?v=${styleVersion}");
		expect(inputCss).toContain("font-display: swap");
		expect(inputCss).toContain('font-family: "Cormorant Garamond"');
		expect(inputCss).toContain('font-family: "Plus Jakarta Sans"');
		expect(inputCss).toContain('font-family: "Pinyon Script"');
		expect(head).toContain('href="/fonts/plus-jakarta-sans-latin.woff2');
		expect(head).toContain('href="/fonts/cormorant-garamond-latin.woff2');
		expect(head).not.toContain("/js/telemetry.js");
		expect(baseLayout).toContain('import "../lib/browser/telemetry"');
		expect(baseLayout).toContain('import "../lib/browser/main"');
		expect(baseLayout).toContain('import "../lib/browser/accessibility"');
	});

	it("detail blog punya share native, copy link, berita populer, dan list venue", () => {
		expect(blogDetail).toContain('id="nativeShareBtn"');
		expect(blogDetail).toContain('id="copyLinkBtn"');
		expect(blogDetail).toContain("blog-popular-list");
		expect(blogDetail).toContain("blog-venue-list");
	});

	it("SEO lanjutan memakai lastmod backend, schema lokal, OG 1200x630, dan consent", () => {
		expect(sitemap).toContain("getPageContentUpdatedAt");
		expect(sitemap).toContain("Cache-Control");
		expect(head).toContain('"@type": "LocalBusiness"');
		expect(head).toContain('"@type": "BreadcrumbList"');
		expect(head).toContain("/img/og-default.png");
		expect(head).toContain('content="1200"');
		expect(analyticsConsent).toContain("sagita_analytics");
		expect(baseLayout).toContain("<AnalyticsConsent />");
		expect(exploreLinks).toContain('href: "/packages"');
		expect(middleware).toContain("immutable");
	});
});
