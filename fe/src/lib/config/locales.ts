/** Bahasa yang didukung theme. Tambah bahasa = tambah entri di sini + translations. */
export const LOCALES = ["en", "id"] as const;

export type Locale = (typeof LOCALES)[number];

export const DEFAULT_LOCALE: Locale = "en";

export interface LanguageOption {
	code: Locale;
	label: string;
	short: string;
	flag: string;
}

export const LANGUAGE_OPTIONS: readonly LanguageOption[] = [
	{ code: "en", label: "English", short: "EN", flag: "\u{1F1EC}\u{1F1E7}" },
	{ code: "id", label: "Indonesia", short: "ID", flag: "\u{1F1EE}\u{1F1E9}" },
];

export const DATE_LOCALES: Record<Locale, string> = {
	en: "en-US",
	id: "id-ID",
};

export function isLocale(value: unknown): value is Locale {
	return (
		typeof value === "string" && (LOCALES as readonly string[]).includes(value)
	);
}
