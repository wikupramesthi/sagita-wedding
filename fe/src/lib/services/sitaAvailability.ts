import { apiEnvelopeRaw } from "./apiClient.js";

export interface SitaDateAvailability {
	reserved: boolean;
	blackouts: {
		packageName: string;
		reason: string | null;
	}[];
}

function asRecord(value: unknown): Record<string, unknown> | null {
	return value && typeof value === "object" && !Array.isArray(value)
		? (value as Record<string, unknown>)
		: null;
}

export async function fetchSitaDateAvailability(
	date: string,
): Promise<SitaDateAvailability> {
	const result = await apiEnvelopeRaw(
		`/sita/availability?date=${encodeURIComponent(date)}`,
	);
	const body = asRecord(result.body);
	const data = asRecord(body?.data);
	if (
		!data ||
		data.date !== date ||
		typeof data.reserved !== "boolean" ||
		!Array.isArray(data.blackouts)
	) {
		throw new Error("Backend returned invalid Sita availability data");
	}

	const blackouts = data.blackouts.map((item) => {
		const blackout = asRecord(item);
		if (
			!blackout ||
			typeof blackout.packageName !== "string" ||
			(blackout.reason !== null && typeof blackout.reason !== "string")
		) {
			throw new Error("Backend returned invalid package blackout data");
		}
		return {
			packageName: blackout.packageName,
			reason: blackout.reason,
		};
	});

	return { reserved: data.reserved, blackouts };
}
