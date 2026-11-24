const MONTHS: Record<string, number> = {
	jan: 1,
	januari: 1,
	january: 1,
	feb: 2,
	februari: 2,
	february: 2,
	mar: 3,
	maret: 3,
	march: 3,
	apr: 4,
	april: 4,
	mei: 5,
	may: 5,
	jun: 6,
	juni: 6,
	june: 6,
	jul: 7,
	juli: 7,
	july: 7,
	agu: 8,
	agt: 8,
	agustus: 8,
	aug: 8,
	august: 8,
	sep: 9,
	sept: 9,
	september: 9,
	okt: 10,
	oktober: 10,
	oct: 10,
	october: 10,
	nov: 11,
	november: 11,
	des: 12,
	desember: 12,
	dec: 12,
	december: 12,
};

function createIsoDate(
	day: number,
	month: number,
	year: number,
): string | null {
	if (day < 1 || day > 31 || month < 1 || month > 12) return null;
	const date = new Date(Date.UTC(year, month - 1, day));
	if (
		date.getUTCFullYear() !== year ||
		date.getUTCMonth() !== month - 1 ||
		date.getUTCDate() !== day
	) {
		return null;
	}
	return `${year}-${String(month).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
}

function monthNumber(value: string): number | undefined {
	const token = value
		.normalize("NFKD")
		.replace(/[\u0300-\u036f]/g, "")
		.toLocaleLowerCase()
		.replace(/\.$/, "");
	return MONTHS[token];
}

export function parseEventDate(value: string): string | undefined {
	const input = value.trim();
	const iso = input.match(/\b(20\d{2})-(\d{1,2})-(\d{1,2})\b/);
	if (iso) {
		return (
			createIsoDate(Number(iso[3]), Number(iso[2]), Number(iso[1])) ?? undefined
		);
	}

	const numeric = input.match(/\b(\d{1,2})[./-](\d{1,2})[./-](20\d{2})\b/);
	if (numeric) {
		return (
			createIsoDate(
				Number(numeric[1]),
				Number(numeric[2]),
				Number(numeric[3]),
			) ?? undefined
		);
	}

	const dayFirst = input.match(
		/\b(\d{1,2})(?:st|nd|rd|th)?[\s,./-]+([a-z.]+)[\s,.-]+(20\d{2})\b/i,
	);
	if (dayFirst) {
		const month = monthNumber(dayFirst[2]);
		return month
			? (createIsoDate(Number(dayFirst[1]), month, Number(dayFirst[3])) ??
					undefined)
			: undefined;
	}

	const monthFirst = input.match(
		/\b([a-z.]+)[\s,./-]+(\d{1,2})(?:st|nd|rd|th)?[,]?[\s,.-]+(20\d{2})\b/i,
	);
	if (monthFirst) {
		const month = monthNumber(monthFirst[1]);
		return month
			? (createIsoDate(Number(monthFirst[2]), month, Number(monthFirst[3])) ??
					undefined)
			: undefined;
	}

	return undefined;
}

export function formatEventDateInput(value: string): string {
	const iso = parseEventDate(value);
	if (!iso) return value;
	const [year, month, day] = iso.split("-");
	return `${day}-${month}-${year}`;
}

export function formatEventDate(value: string, locale: "id" | "en"): string {
	const iso = parseEventDate(value);
	if (!iso) return value;
	return new Intl.DateTimeFormat(locale === "id" ? "id-ID" : "en-US", {
		day: "numeric",
		month: "long",
		timeZone: "UTC",
		year: "numeric",
	}).format(new Date(`${iso}T00:00:00Z`));
}

export function isPastEventDate(value: string): boolean {
	const iso = parseEventDate(value);
	if (!iso) return false;
	const parts = new Map(
		new Intl.DateTimeFormat("en-CA", {
			timeZone: "Asia/Jakarta",
			year: "numeric",
			month: "2-digit",
			day: "2-digit",
		})
			.formatToParts(new Date())
			.map(({ type, value }) => [type, value]),
	);
	const year = parts.get("year");
	const month = parts.get("month");
	const day = parts.get("day");
	if (!year || !month || !day) {
		throw new Error("Unable to determine the current date in Asia/Jakarta");
	}
	return iso < `${year}-${month}-${day}`;
}
