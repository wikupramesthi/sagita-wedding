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
	  };

const ENDPOINT = "/api/telemetry";
const seen = new Set<string>();

function cleanName(value: string): string {
	return value.replace(/[^a-zA-Z0-9_.:-]/g, "").slice(0, 80) || "unknown";
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

window.addEventListener("sita:no-match", () => {
	if (!once("event:sita.no_match")) return;
	send({ kind: "event", name: "sita.no_match", path: "/" });
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
