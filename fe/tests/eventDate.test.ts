import { describe, expect, it } from "vitest";
import {
	formatEventDate,
	formatEventDateInput,
	parseEventDate,
} from "../src/lib/utils/eventDate.js";

describe("event date parsing", () => {
	it.each([
		["10-12-2026", "2026-12-10"],
		["10/12/2026", "2026-12-10"],
		["10.12.2026", "2026-12-10"],
		["10 des 2026", "2026-12-10"],
		["10 desember 2026", "2026-12-10"],
		["10 Dec 2026", "2026-12-10"],
		["December 10, 2026", "2026-12-10"],
		["2026-12-10", "2026-12-10"],
	])("parses %s", (input, expected) => {
		expect(parseEventDate(input)).toBe(expected);
	});

	it.each(["31-02-2026", "10-13-2026", "32 Desember 2026"])(
		"rejects invalid date %s",
		(input) => {
			expect(parseEventDate(input)).toBeUndefined();
		},
	);

	it("formats normalized dates for display", () => {
		expect(formatEventDateInput("10 desember 2026")).toBe("10-12-2026");
		expect(formatEventDate("2026-12-10", "id")).toBe("10 Desember 2026");
	});
});
