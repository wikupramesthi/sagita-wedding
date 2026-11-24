import { readFileSync } from "node:fs";
import { describe, expect, it } from "vitest";

const read = (path: string) =>
	readFileSync(new URL(path, import.meta.url), "utf8");

const customCss = read("../public/css/custom.css");
const inputCss = read("../public/css/input.css");
const head = read("../src/components/Head.astro");
const baseLayout = read("../src/layouts/BaseLayout.astro");
const blogDetail = read("../src/pages/blog/[slug].astro");

describe("kualitas frontend", () => {
	it("punya breakpoint mobile dan desktop untuk halaman utama", () => {
		expect(customCss).toContain("@media (max-width: 640px)");
		expect(customCss).toContain("@media (min-width: 1024px)");
		expect(customCss).toContain(".home-packages-section");
		expect(customCss).toContain(".blog-grid");
		expect(customCss).toContain(".blog-detail-layout");
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
			'href={`/css/custom.css?v=${styleVersion}`} as="style"',
		);
		expect(head).toContain('href={heroImage} fetchpriority="high"');
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
});
