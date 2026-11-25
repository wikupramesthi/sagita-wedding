import { describe, expect, it } from "vitest";
import {
	clientIp,
	rateLimitHeaders,
	readJson,
	takeRateLimit,
} from "../src/lib/http.js";
import { sanitizeRichHtml } from "../src/lib/utils/sanitize.js";

describe("http hardening", () => {
	it("membatasi ukuran body JSON", async () => {
		const request = new Request("http://localhost/", {
			method: "POST",
			body: "x".repeat(64 * 1024 + 1),
			headers: { "content-type": "application/json" },
		});
		expect(await readJson(request)).toBeNull();
	});

	it("memakai IP proxy terakhir hanya dari koneksi loopback", () => {
		const request = new Request("http://localhost/", {
			headers: {
				"x-forwarded-for": "1.1.1.1, 203.0.113.9",
				"x-real-ip": "198.51.100.2",
			},
		});
		expect(clientIp(request, "127.0.0.1")).toBe("203.0.113.9");
		expect(clientIp(request, "203.0.113.5")).toBe("203.0.113.5");
		expect(clientIp(request)).toBe("198.51.100.2");
	});

	it("menggunakan nilai limit asli pada header rate limit", () => {
		const result = takeRateLimit(`security-test:${Date.now()}`, 7);
		const headers = rateLimitHeaders(result, 7);
		expect(headers["RateLimit-Limit"]).toBe("7");
		expect(Number(headers["RateLimit-Reset"])).toBeGreaterThan(0);
	});
});

describe("sanitizer", () => {
	it("membuang atribut style dari HTML CMS", () => {
		const html = sanitizeRichHtml('<p class="x" style="color:red">Halo</p>');
		expect(html).not.toContain("style=");
		expect(html).toContain('class="x"');
	});
});
