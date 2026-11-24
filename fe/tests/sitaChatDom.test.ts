import zlib from "node:zlib";
import { afterEach, beforeEach, describe, expect, it, vi } from "vitest";

if (typeof zlib.createZstdDecompress !== "function") {
	zlib.createZstdDecompress = () => {
		throw new Error("zstd decompression is unavailable in this Node runtime");
	};
}

const { JSDOM } = await import("jsdom");

type Detail = Record<string, unknown>;

const html = `
<div id="sitaChatWidget" data-locale="id" data-whatsapp-link="https://wa.test/">
  <section id="sitaChatPanel" class="hidden">
    <button id="sitaChatReset" type="button">Reset</button>
    <button id="sitaChatClose" type="button">Tutup</button>
    <div id="sitaChatMessages"></div>
    <div id="sitaChatOnboarding">
      <button type="button" data-sita-example="Paket pernikahan">Harga paket</button>
    </div>
    <div id="sitaChatChoices">
      <button type="button" data-sita-topic="packages">Paket &amp; harga</button>
      <button type="button" data-sita-topic="booking">Cara booking</button>
      <button type="button" data-sita-topic="contact">Hubungi admin</button>
    </div>
    <form id="sitaChatLead">
      <input name="name" type="text">
      <input name="phone" type="tel">
      <input name="consent" type="checkbox">
      <input name="website" type="text">
      <button type="submit">Lanjut</button>
      <button type="button" data-sita-skip>Lewati</button>
    </form>
    <form id="sitaChatComposer">
      <input id="sitaChatInput" type="text">
      <button type="submit">Kirim</button>
    </form>
  </section>
  <button id="sitaChatToggle" type="button">Sita</button>
</div>`;

function response(payload: unknown): Response {
	return {
		ok: true,
		status: 200,
		headers: { get: () => null },
		json: async () => payload,
	} as unknown as Response;
}

describe("SITA chat widget", () => {
	let dom: InstanceType<typeof JSDOM>;
	let events: Record<string, Detail[]>;
	let telemetry: Detail[];
	let sitaRequests: Detail[];
	let leadRequests: Detail[];
	let fetchMock: ReturnType<typeof vi.fn>;

	function setGlobal(key: string, value: unknown): void {
		Object.defineProperty(globalThis, key, {
			configurable: true,
			writable: true,
			value,
		});
	}

	beforeEach(async () => {
		vi.resetModules();
		dom = new JSDOM(html, { url: "http://localhost:3000/" });
		Object.defineProperty(dom.window.navigator, "sendBeacon", {
			configurable: true,
			value: undefined,
		});

		for (const key of [
			"window",
			"document",
			"HTMLElement",
			"HTMLButtonElement",
			"HTMLInputElement",
			"HTMLFormElement",
			"Element",
			"CustomEvent",
			"Event",
			"FormData",
			"sessionStorage",
			"localStorage",
			"navigator",
			"location",
		]) {
			setGlobal(key, (dom.window as unknown as Record<string, unknown>)[key]);
		}

		events = {};
		telemetry = [];
		sitaRequests = [];
		leadRequests = [];
		for (const name of ["sita:answer", "sita:no-match", "sita:feedback"]) {
			events[name] = [];
			dom.window.addEventListener(name, (event) => {
				events[name].push((event as CustomEvent<Detail>).detail ?? {});
			});
		}

		fetchMock = vi.fn(async (url: string, init?: RequestInit) => {
			if (url.startsWith("/api/telemetry")) {
				telemetry.push(JSON.parse(String(init?.body ?? "{}")) as Detail);
				return response({});
			}
			if (url === "/api/sita/knowledge") return response({ data: [] });
			if (url === "/api/sita/leads") {
				leadRequests.push(JSON.parse(String(init?.body ?? "{}")) as Detail);
				return response({ success: true });
			}
			if (url === "/api/sita/chat") {
				return response({
					success: true,
					answer: "Paket mulai 25 juta.",
					cards: [],
					offerContact: true,
					confidence: 0.9,
					topic: "packages",
				});
			}
			throw new Error(`Unexpected fetch: ${url}`);
		});
		setGlobal("fetch", fetchMock);

		await import("../src/browser/lib/sita-chat.js");
	});

	afterEach(() => {
		dom.window.close();
		vi.unstubAllGlobals();
	});

	function element(selector: string): HTMLElement {
		const found = dom.window.document.querySelector<HTMLElement>(selector);
		if (!found) throw new Error(`Missing element: ${selector}`);
		return found;
	}

	function submitQuestion(question: string, source?: Detail): void {
		setGlobal(
			"fetch",
			vi.fn(async (url: string, init?: RequestInit) => {
				if (url.startsWith("/api/telemetry")) {
					telemetry.push(JSON.parse(String(init?.body ?? "{}")) as Detail);
					return response({});
				}
				if (url === "/api/sita/knowledge") return response({ data: [] });
				if (url === "/api/sita/leads") {
					leadRequests.push(JSON.parse(String(init?.body ?? "{}")) as Detail);
					return response({ success: true });
				}
				if (url === "/api/sita/chat") {
					const body = JSON.parse(String(init?.body ?? "{}")) as {
						message?: string;
					};
					sitaRequests.push(body);
					return response({
						success: true,
						answer:
							body.message === "Fotobooth dong"
								? "Aku belum punya data vendor itu."
								: "Paket mulai 25 juta.",
						cards: [],
						offerContact: true,
						confidence: body.message === "Fotobooth dong" ? 0.35 : 0.9,
						topic: body.message === "Fotobooth dong" ? "vendors" : "packages",
						...(source ? { source } : {}),
					});
				}
				throw new Error(`Unexpected fetch: ${url}`);
			}),
		);

		const input = element("#sitaChatInput") as HTMLInputElement;
		input.value = question;
		element("#sitaChatComposer").dispatchEvent(
			new dom.window.Event("submit", { bubbles: true, cancelable: true }),
		);
	}

	function submitGuideAnswer(answer: string): void {
		const input = element("#sitaChatInput") as HTMLInputElement;
		input.value = answer;
		element("#sitaChatComposer").dispatchEvent(
			new dom.window.Event("submit", { bubbles: true, cancelable: true }),
		);
	}

	it("opens and closes the panel and opens the contact form", () => {
		const toggle = element("#sitaChatToggle") as HTMLButtonElement;
		const panel = element("#sitaChatPanel");

		toggle.click();
		expect(panel.classList.contains("hidden")).toBe(false);
		expect(toggle.getAttribute("aria-expanded")).toBe("true");

		element("[data-sita-topic='contact']").click();
		expect(element("#sitaChatLead").classList.contains("hidden")).toBe(false);
		expect(panel.classList.contains("sita-chat-panel--lead-open")).toBe(true);
		expect(element("#sitaChatMessages").textContent).toContain(
			"Silakan isi nama",
		);

		element("[data-sita-skip]").click();
		expect(element("#sitaChatLead").classList.contains("hidden")).toBe(true);
		expect(panel.classList.contains("sita-chat-panel--lead-open")).toBe(false);

		element("[data-sita-topic='contact']").click();
		element("#sitaChatClose").click();
		expect(panel.classList.contains("hidden")).toBe(true);
		expect(toggle.getAttribute("aria-expanded")).toBe("false");
	});

	it("accepts readable event date formats and rejects past dates", () => {
		const futureYear = new Date().getUTCFullYear() + 2;
		const futureDate = `10 Desember ${futureYear}`;
		element("#sitaChatToggle").click();
		element("[data-sita-topic='booking']").click();

		expect(element("#sitaChatMessages").textContent).toContain(
			"10-12-2026, 10 Des 2026, atau 10 Desember 2026",
		);

		submitGuideAnswer("01/01/2020");
		expect(element("#sitaChatMessages").textContent).toContain(
			"Tanggal tersebut sudah lewat",
		);

		submitGuideAnswer(futureDate);
		expect(element("#sitaChatMessages").textContent).toContain(
			"Di kota mana acaranya?",
		);
	});

	it("carries booking criteria into the admin handoff", async () => {
		element("#sitaChatToggle").click();
		element("[data-sita-topic='booking']").click();
		submitGuideAnswer("10 Desember 2027");
		submitGuideAnswer("Bekasi");
		submitGuideAnswer("100 juta");
		submitGuideAnswer("150 tamu");

		await vi.waitFor(
			() =>
				expect(element("#sitaChatMessages").textContent).toContain(
					"Paket mulai 25 juta.",
				),
			{ timeout: 3000 },
		);
		element("[data-sita-topic='contact']").click();
		(element("#sitaChatLead input[name='name']") as HTMLInputElement).value =
			"Rina";
		(element("#sitaChatLead input[name='phone']") as HTMLInputElement).value =
			"08123456789";
		(
			element("#sitaChatLead input[name='consent']") as HTMLInputElement
		).checked = true;
		element("#sitaChatLead").dispatchEvent(
			new dom.window.Event("submit", { bubbles: true, cancelable: true }),
		);

		await vi.waitFor(() => expect(leadRequests).toHaveLength(1), {
			timeout: 3000,
		});
		await vi.waitFor(
			() =>
				expect(
					dom.window.document.querySelector(".sita-chat-handoff"),
				).not.toBeNull(),
			{ timeout: 3000 },
		);
		expect(leadRequests[0]).toMatchObject({
			topic: "packages",
			criteria: {
				eventDate: "2027-12-10",
				city: "Bekasi",
				budgetLimit: 100_000_000,
				guestCount: 150,
			},
		});
		const handoff = element(".sita-chat-handoff") as HTMLAnchorElement;
		const handoffText = new URL(handoff.href).searchParams.get("text") || "";
		expect(handoffText).toContain("10 Desember 2027");
		expect(handoffText).toContain("Bekasi");
		expect(handoffText).toContain("100.000.000");
		expect(handoffText).toContain("150");
	});

	it("preserves topic and collected filters for a contextual follow-up", async () => {
		element("#sitaChatToggle").click();
		submitQuestion("Paket budget 50 juta untuk 200 tamu");
		await vi.waitFor(() => expect(sitaRequests).toHaveLength(1), {
			timeout: 5000,
		});
		await vi.waitFor(
			() =>
				expect((element("#sitaChatInput") as HTMLInputElement).disabled).toBe(
					false,
				),
			{ timeout: 5000 },
		);

		submitQuestion("yang lebih murah");
		await vi.waitFor(() => expect(sitaRequests).toHaveLength(2), {
			timeout: 5000,
		});

		expect(sitaRequests[1]).toMatchObject({
			message: "yang lebih murah",
			context: {
				topic: "packages",
				criteria: {
					budgetLimit: 50_000_000,
					guestCount: 200,
				},
			},
		});
	});

	it("answers a submitted question and emits answer telemetry", async () => {
		element("#sitaChatToggle").click();
		submitQuestion("Paket pernikahan");

		await vi.waitFor(
			() =>
				expect(element("#sitaChatMessages").textContent).toContain(
					"Paket mulai 25 juta.",
				),
			{ timeout: 3000 },
		);

		expect(events["sita:answer"]).toHaveLength(1);
		expect(events["sita:answer"][0]).toMatchObject({
			topic: "packages",
			confidence: 0.9,
			fallback: false,
		});
		expect(events["sita:no-match"]).toHaveLength(0);
		expect(
			telemetry.some(
				(payload) =>
					payload.name === "sita.answer" && payload.confidence === 0.9,
			),
		).toBe(true);
	});

	it("shows the site content update time next to a sourced answer", async () => {
		element("#sitaChatToggle").click();
		submitQuestion("Paket pernikahan", {
			label: "Paket",
			href: "/paket",
			updatedAt: "2026-03-10T12:30:00.000Z",
		});

		await vi.waitFor(
			() =>
				expect(element(".sita-chat-source-updated").textContent).toContain(
					"Konten situs terakhir diperbarui",
				),
			{ timeout: 3000 },
		);
	});

	it("records low-confidence questions with a sanitized sample", async () => {
		element("#sitaChatToggle").click();
		submitQuestion("Fotobooth dong");

		await vi.waitFor(() => expect(events["sita:no-match"]).toHaveLength(1), {
			timeout: 3000,
		});

		expect(events["sita:no-match"][0]).toMatchObject({
			topic: "vendors",
			fallback: false,
			sample: "Fotobooth dong",
		});
		expect(element("#sitaChatMessages").textContent).toContain(
			"Informasi ini belum cukup terverifikasi",
		);
		expect(
			dom.window.document.querySelector("[data-sita-suggestion='contact']"),
		).not.toBeNull();
		expect(String(events["sita:no-match"][0].queryHash)).toMatch(
			/^[a-z0-9]{1,32}$/,
		);
		const noMatch = telemetry.find(
			(payload) => payload.name === "sita.no_match",
		);
		expect(noMatch).toMatchObject({
			name: "sita.no_match",
			sample: "Fotobooth dong",
			fallback: false,
		});
	});

	it("emits feedback once when a helpful vote is clicked", async () => {
		element("#sitaChatToggle").click();
		submitQuestion("Paket pernikahan");

		await vi.waitFor(
			() =>
				expect(
					dom.window.document.querySelector("[data-sita-feedback='true']"),
				).not.toBeNull(),
			{ timeout: 3000 },
		);

		const helpful = element("[data-sita-feedback='true']") as HTMLButtonElement;
		helpful.click();
		helpful.click();

		expect(events["sita:feedback"]).toHaveLength(1);
		expect(events["sita:feedback"][0]).toMatchObject({ helpful: true });
		expect(helpful.disabled).toBe(true);
	});
});
