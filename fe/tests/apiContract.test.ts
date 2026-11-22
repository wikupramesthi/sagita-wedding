import { describe, expect, it } from "vitest";
import { SUPPORTED_BATCH_PAGES } from "../src/services/batchService.js";

describe("API contract", () => {
	it("only exposes batch pages implemented by the backend", () => {
		expect(SUPPORTED_BATCH_PAGES).toEqual([
			"home",
			"venues",
			"about",
			"blog",
			"blog-detail",
			"packages",
			"package-detail",
			"vendors",
			"events",
			"gallery",
			"contact",
			"faq",
			"page-detail",
		]);
	});
});
