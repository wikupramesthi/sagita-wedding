export function sanitizeSitaQuerySample(raw: string): string {
	return raw
		.replace(/https?:\/\/\S+/gi, "[tautan]")
		.replace(/\S+@\S+\.\S+/g, "[email]")
		.replace(/\b\d(?:[\s().-]?\d){9,}\b/g, "[nomor]")
		.replace(/[\p{Cc}]/gu, " ")
		.replace(/\s+/g, " ")
		.trim()
		.slice(0, 160);
}
