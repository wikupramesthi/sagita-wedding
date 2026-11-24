import { sanitizeSitaQuerySample } from "../utils/sitaQuery.js";

type TelemetryPayload =
	| {
			kind: "error";
			name: string;
			path: string;
			message: string;
	  }
	| {
			kind: "vital";
			name: string;
			path: string;
			value: number;
	  }
	| {
			kind: "event";
			name: "sita.no_match";
			path: "/";
			topic?: string;
			queryHash?: string;
			sample?: string;
			fallback?: boolean;
	  }
	| {
			kind: "event";
			name: "sita.answer";
			path: "/";
			topic?: string;
			confidence: number;
			fallback: boolean;
			clarify: boolean;
			responseMs: number;
			queryHash?: string;
			turnId?: string;
	  }
	| {
			kind: "event";
			name: "sita.feedback";
			path: "/";
			helpful: boolean;
			turnId?: string;
			topic?: string;
	  };

const ENDPOINT = "/api/telemetry";
const seen = new Set<string>();

function cleanName(value: string): string {
	return value.replace(/[^a-zA-Z0-9_.:-]/g, "").slice(0, 80) || "unknown";
}

function cleanTopic(value: unknown): string | undefined {
	return value === "packages" || value === "venues" || value === "vendors"
		? value
		: undefined;
}

export function hashSitaQuestion(raw: string): string {
	const normalized = raw
		.normalize("NFKD")
		.toLowerCase()
		.replace(/[\u0300-\u036f]/g, "")
		.replace(/[^a-z0-9\s]/g, "")
		.replace(/\s+/g, " ")
		.trim()
		.slice(0, 200);
	let hash = 2166136261;
	for (let index = 0; index < normalized.length; index += 1) {
		hash ^= normalized.charCodeAt(index);
		hash = Math.imul(hash, 16777619);
	}
	return (hash >>> 0).toString(36);
}

function send(payload: TelemetryPayload) {
	const body = JSON.stringify(payload);
	if (typeof navigator !== "undefined" && navigator.sendBeacon) {
		navigator.sendBeacon(
			ENDPOINT,
			new Blob([body], { type: "application/json" }),
		);
		return;
	}
	void fetch(ENDPOINT, {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body,
		keepalive: true,
	}).catch(() => undefined);
}

function once(key: string) {
	if (seen.has(key)) return false;
	seen.add(key);
	if (seen.size > 100) {
		const first = seen.values().next().value;
		if (first) seen.delete(first);
	}
	return true;
}

function reportError(name: string, message: string) {
	const key = `error:${name}:${message.slice(0, 40)}`;
	if (!once(key)) return;
	send({
		kind: "error",
		name: cleanName(name),
		path: location.pathname,
		message: message.slice(0, 200),
	});
}

function reportVital(name: string, value: number) {
	if (!Number.isFinite(value) || value < 0) return;
	const rounded = Math.round(value * 100) / 100;
	const key = `vital:${name}:${Math.round(rounded)}`;
	if (!once(key)) return;
	send({
		kind: "vital",
		name: cleanName(name),
		value: rounded,
		path: location.pathname,
	});
}

window.addEventListener("sita:no-match", (event) => {
	const detail = (
		event as CustomEvent<{
			topic?: unknown;
			queryHash?: unknown;
			sample?: unknown;
			fallback?: unknown;
		}>
	).detail;
	const topic = cleanTopic(detail?.topic);
	const queryHash =
		typeof detail?.queryHash === "string" &&
		/^[a-z0-9]{1,32}$/.test(detail.queryHash)
			? detail.queryHash
			: undefined;
	const sample =
		typeof detail?.sample === "string"
			? sanitizeSitaQuerySample(detail.sample)
			: "";
	if (!once(`event:sita.no_match:${queryHash ?? "unknown"}`)) return;
	send({
		kind: "event",
		name: "sita.no_match",
		path: "/",
		...(topic ? { topic } : {}),
		...(queryHash ? { queryHash } : {}),
		...(sample ? { sample } : {}),
		...(typeof detail?.fallback === "boolean"
			? { fallback: detail.fallback }
			: {}),
	});
});

window.addEventListener("sita:answer", (event) => {
	const detail = (
		event as CustomEvent<{
			topic?: unknown;
			confidence?: unknown;
			fallback?: unknown;
			clarify?: unknown;
			responseMs?: unknown;
			queryHash?: unknown;
			turnId?: unknown;
		}>
	).detail;
	if (
		!detail ||
		typeof detail.confidence !== "number" ||
		typeof detail.responseMs !== "number"
	) {
		return;
	}
	const turnId =
		typeof detail.turnId === "string" ? cleanName(detail.turnId) : undefined;
	const queryHash =
		typeof detail.queryHash === "string" &&
		/^[a-z0-9]{1,32}$/.test(detail.queryHash)
			? detail.queryHash
			: undefined;
	if (turnId && !once(`event:sita.answer:${turnId}`)) return;
	send({
		kind: "event",
		name: "sita.answer",
		path: "/",
		topic: cleanTopic(detail.topic),
		confidence: detail.confidence,
		fallback: detail.fallback === true,
		clarify: detail.clarify === true,
		responseMs: detail.responseMs,
		...(queryHash ? { queryHash } : {}),
		...(turnId ? { turnId } : {}),
	});
});

window.addEventListener("sita:feedback", (event) => {
	const detail = (
		event as CustomEvent<{
			helpful?: unknown;
			turnId?: unknown;
			topic?: unknown;
		}>
	).detail;
	const helpful = detail?.helpful === true;
	const turnId =
		typeof detail?.turnId === "string" ? cleanName(detail.turnId) : undefined;
	const topic =
		typeof detail?.topic === "string" &&
		["packages", "venues", "vendors"].includes(detail.topic)
			? detail.topic
			: undefined;
	send({
		kind: "event",
		name: "sita.feedback",
		path: "/",
		helpful,
		...(turnId ? { turnId } : {}),
		...(topic ? { topic } : {}),
	});
});

window.addEventListener("error", (event) => {
	reportError(event.message || "Error", event.message || "unknown");
});

window.addEventListener("unhandledrejection", (event) => {
	const reason = event.reason;
	const message =
		reason instanceof Error
			? reason.message
			: typeof reason === "string"
				? reason
				: "Unhandled rejection";
	reportError("unhandledrejection", message);
});

(() => {
	if (typeof PerformanceObserver === "undefined") return;

	try {
		new PerformanceObserver((list) => {
			for (const entry of list.getEntries())
				reportVital("LCP", entry.startTime);
		}).observe({ type: "largest-contentful-paint", buffered: true });
	} catch {
		return;
	}

	try {
		new PerformanceObserver((list) => {
			let total = 0;
			for (const entry of list.getEntries()) {
				const shift = entry as PerformanceEntry & {
					hadRecentInput?: boolean;
					value?: number;
				};
				if (!shift.hadRecentInput) total += shift.value ?? 0;
			}
			if (total > 0) reportVital("CLS", total);
		}).observe({ type: "layout-shift", buffered: true });
	} catch {}

	try {
		new PerformanceObserver((list) => {
			for (const entry of list.getEntries()) {
				const event = entry as PerformanceEntry & { duration?: number };
				if (typeof event.duration === "number") {
					reportVital("INP", event.duration);
				}
			}
		}).observe({ type: "event", buffered: true });
	} catch {}
})();
