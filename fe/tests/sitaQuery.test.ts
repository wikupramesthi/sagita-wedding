import { describe, expect, it } from "vitest";
import { sanitizeSitaQuerySample } from "../src/lib/utils/sitaQuery.js";

describe("sanitizeSitaQuerySample", () => {
	it("removes contact details and keeps a readable snippet", () => {
		const sample = sanitizeSitaQuerySample(
			"  Foto booth untuk https://contoh.test xy di aku@email.test atau 0812 3456 7890  ",
		);

		expect(sample).toBe("Foto booth untuk [tautan] xy di [email] atau [nomor]");
		expect(sample).not.toContain("0812");
		expect(sample).not.toContain("email.test");
	});

	it("limits the stored sample length", () => {
		expect(sanitizeSitaQuerySample("a".repeat(500))).toHaveLength(160);
	});
});
