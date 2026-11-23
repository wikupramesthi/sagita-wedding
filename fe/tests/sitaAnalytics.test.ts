import { beforeEach, describe, expect, it } from "vitest";
import {
	getSitaAnalytics,
	recordSitaTelemetry,
	resetSitaAnalytics,
} from "../src/services/sitaAnalytics.js";

beforeEach(() => {
	resetSitaAnalytics();
});

describe("Sita analytics", () => {
	it("aggregates answers, feedback, and privacy-safe no-match hashes", () => {
		recordSitaTelemetry({
			kind: "event",
			name: "sita.answer",
			path: "/",
			topic: "packages",
			confidence: 0.9,
			fallback: false,
			clarify: false,
			responseMs: 320,
			turnId: "turn-1",
		});
		recordSitaTelemetry({
			kind: "event",
			name: "sita.answer",
			path: "/",
			topic: "vendors",
			confidence: 0.15,
			fallback: true,
			clarify: false,
			responseMs: 180,
			turnId: "turn-2",
		});
		recordSitaTelemetry({
			kind: "event",
			name: "sita.feedback",
			path: "/",
			helpful: true,
			topic: "packages",
		});
		recordSitaTelemetry({
			kind: "event",
			name: "sita.feedback",
			path: "/",
			helpful: false,
			topic: "vendors",
		});
		recordSitaTelemetry({
			kind: "event",
			name: "sita.no_match",
			path: "/",
			topic: "packages",
			queryHash: "abc123",
		});
		recordSitaTelemetry({
			kind: "event",
			name: "sita.no_match",
			path: "/",
			topic: "packages",
			queryHash: "abc123",
		});

		const analytics = getSitaAnalytics();

		expect(analytics.totals).toMatchObject({
			answers: 2,
			feedback: 2,
			helpful: 1,
			unhelpful: 1,
			noMatch: 2,
			fallback: 1,
			lowConfidence: 1,
		});
		expect(analytics.rates.helpful).toBe(50);
		expect(analytics.rates.fallback).toBe(50);
		expect(analytics.performance.avgResponseMs).toBe(250);
		expect(
			analytics.topics.find((entry) => entry.topic === "packages"),
		).toMatchObject({ answers: 1, helpful: 1, unhelpful: 0 });
		expect(analytics.noMatchPatterns[0]).toMatchObject({
			queryHash: "abc123",
			count: 2,
		});
		expect(JSON.stringify(analytics)).not.toContain("rahasia");
	});

	it("ignores error/vital telemetry and unknown event names", () => {
		recordSitaTelemetry({
			kind: "error",
			name: "client.error",
			path: "/",
			message: "rahasia",
		});
		recordSitaTelemetry({
			kind: "event",
			name: "sita.secret",
			path: "/",
		});

		expect(getSitaAnalytics().totals.answers).toBe(0);
		expect(getSitaAnalytics().window.events).toBe(0);
	});

	it("keeps only the latest 20 events in the recent sample", () => {
		for (let index = 0; index < 30; index += 1) {
			recordSitaTelemetry({
				kind: "event",
				name: "sita.answer",
				path: "/",
				confidence: 0.5,
				fallback: false,
				clarify: false,
				responseMs: index,
			});
		}

		expect(getSitaAnalytics().recent).toHaveLength(20);
	});
});
