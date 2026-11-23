import request from "supertest";
import { beforeEach, describe, expect, it, vi } from "vitest";
import { resetSitaAnalytics } from "../src/services/sitaAnalytics.js";

/* ---------- mock service, backend tidak disentuh ---------- */

const USER = {
	uuid: "dd9d6231-c532-4e9e-a954-959fe814b3b9",
	name: "Budi Santoso",
	email: "budi@example.com",
	avatar: null,
	roles: ["user"],
};

const getSessionUser = vi.fn<(c?: string) => Promise<typeof USER | null>>();
const logoutSession = vi.fn<(c?: string) => Promise<boolean>>();

vi.mock("../src/services/authService.js", async () => {
	// Ikuti implementasi asli untuk hasSessionCookie (memakai env)
	const actual = await vi.importActual<
		typeof import("../src/services/authService.js")
	>("../src/services/authService.js");
	return {
		...actual,
		getSessionUser: (c?: string) => getSessionUser(c),
		logoutSession: (c?: string) => logoutSession(c),
	};
});

const {
	fetchSitaAnalyticsMock,
	fetchSitaSynonymsMock,
	createSitaLeadMock,
	forwardSitaTelemetryMock,
} = vi.hoisted(() => ({
	fetchSitaAnalyticsMock:
		vi.fn<() => Promise<Record<string, unknown> | null>>(),
	fetchSitaSynonymsMock: vi.fn<() => Promise<unknown[]>>(),
	createSitaLeadMock: vi.fn<(payload: unknown) => Promise<string | null>>(),
	forwardSitaTelemetryMock: vi.fn<(payload: unknown) => Promise<void>>(),
}));

fetchSitaAnalyticsMock.mockResolvedValue(null);
fetchSitaSynonymsMock.mockResolvedValue([]);
createSitaLeadMock.mockResolvedValue("lead-uuid");
forwardSitaTelemetryMock.mockResolvedValue(undefined);

vi.mock("../src/services/sitaAnalytics.js", async () => {
	const actual = await vi.importActual<
		typeof import("../src/services/sitaAnalytics.js")
	>("../src/services/sitaAnalytics.js");
	return {
		...actual,
		forwardSitaTelemetry: forwardSitaTelemetryMock,
		fetchSitaAnalytics: fetchSitaAnalyticsMock,
		fetchSitaSynonyms: fetchSitaSynonymsMock,
		createSitaLead: createSitaLeadMock,
	};
});

vi.mock("../src/services/siteIdentityService.js", () => ({
	resolveSite: () => ({
		name: "Sagita Wedding",
		tagline: "Yoga",
		description: "d",
		address: "a",
		phone: "p",
		email: "e@e.com",
	}),
}));
const { env } = await import("../src/config/env.js");
const { clearSessionCache } = await import("../src/middleware/locals.js");

const { createApp } = await import("../src/app.js");
const app = createApp();
const SESSION = `${env.SESSION_COOKIE}=test-session`;

function setSession(user: typeof USER | null): void {
	clearSessionCache();
	getSessionUser.mockResolvedValue(user);
}

beforeEach(() => {
	vi.clearAllMocks();
	setSession(USER);
});

/* ============================ anonim ============================ */

describe("state anonim (belum login)", () => {
	it("tidak memanggil backend bila tidak ada cookie session", async () => {
		await request(app).get("/");
		expect(getSessionUser).not.toHaveBeenCalled();
	});

	it("menampilkan tombol Google di modal", async () => {
		const res = await request(app).get("/");
		expect(res.text).toContain('id="googleLoginBtn"');
		expect(res.text).toContain("Let’s plan your wedding");
	});

	it("tidak menampilkan dropdown user", async () => {
		const res = await request(app).get("/");
		expect(res.text).not.toContain('id="userMenu"');
		expect(res.text).not.toContain('data-action="logout"');
	});

	it("link Google membawa redirect ke FE", async () => {
		const res = await request(app).get("/");
		const href = /href="([^"]*auth\/google[^"]*)"/.exec(res.text)?.[1] || "";
		expect(href).toContain("redirect=");
		expect(decodeURIComponent(href)).toContain("localhost:3000");
	});
});

/* ============================ sudah login ============================ */

describe("state login", () => {
	beforeEach(() => {
		setSession(USER);
	});

	it("cookie session diteruskan ke authService apa adanya", async () => {
		await request(app).get("/").set("Cookie", `${SESSION}; locale=id`);
		expect(getSessionUser).toHaveBeenCalledWith(`${SESSION}; locale=id`);
	});

	it("navbar menampilkan dropdown user, bukan tombol Login", async () => {
		const res = await request(app).get("/").set("Cookie", SESSION);
		expect(res.text).toContain('id="userMenu"');
		expect(res.text).toContain('id="userMenuBtn"');
		expect(res.text).toContain("Budi Santoso");
		expect(res.text).toContain("budi@example.com");
		expect(res.text).toContain('data-action="logout"');
	});

	it("modal menampilkan state login, bukan tombol Google", async () => {
		const res = await request(app).get("/").set("Cookie", SESSION);
		expect(res.text).toContain("Welcome back");
		expect(res.text).not.toContain('id="googleLoginBtn"');
		expect(res.text).toContain('href="/venues"');
	});

	it("memakai inisial sebagai avatar cadangan", async () => {
		const res = await request(app).get("/").set("Cookie", SESSION);
		// 'B' dari "Budi Santoso"
		expect(res.text).toMatch(/>B</);
	});

	it("semua halaman konsisten menampilkan login", async () => {
		for (const p of [
			"/",
			"/venues",
			"/packages",
			"/vendors",
			"/faq",
			"/blog",
		]) {
			const res = await request(app).get(p).set("Cookie", SESSION);
			expect(res.text, p).toContain('id="userMenu"');
		}
	});

	it("tanpa 'undefined' di halaman mana pun", async () => {
		for (const p of [
			"/",
			"/venues",
			"/packages",
			"/vendors",
			"/faq",
			"/blog",
		]) {
			const res = await request(app).get(p).set("Cookie", SESSION);
			expect(res.text, p).not.toContain(">undefined<");
		}
	});
});

/* ============================ backend mati ============================ */

describe("ketika backend session tidak merespons", () => {
	it("halaman tetap render (fail-open ke anonim)", async () => {
		setSession(null);
		const res = await request(app).get("/").set("Cookie", SESSION);
		expect(res.status).toBe(200);
		expect(res.text).toContain('id="googleLoginBtn"');
		expect(res.text).not.toContain('id="userMenu"');
	});
});

/* ============================ /api/session ============================ */

describe("GET /api/session", () => {
	it("401 dan anonymous bila tidak login", async () => {
		setSession(null);
		const res = await request(app).get("/api/session");
		expect(res.status).toBe(401);
		expect(res.body.authenticated).toBe(false);
		expect(res.headers["cache-control"]).toBe("no-store");
	});

	it("200 dengan user bila login", async () => {
		setSession(USER);
		const res = await request(app).get("/api/session").set("Cookie", SESSION);
		expect(res.status).toBe(200);
		expect(res.body.authenticated).toBe(true);
		expect(res.body.user.email).toBe("budi@example.com");
	});
});

/* ============================ /api/sita/analytics ============================ */

describe("GET /api/sita/analytics", () => {
	beforeEach(() => {
		resetSitaAnalytics();
	});

	it("403 untuk member dan anonim", async () => {
		setSession(USER);
		const member = await request(app)
			.get("/api/sita/analytics")
			.set("Cookie", SESSION);
		expect(member.status).toBe(403);

		setSession(null);
		const anonymous = await request(app).get("/api/sita/analytics");
		expect(anonymous.status).toBe(403);
	});

	it("200 untuk admin dengan ringkasan tanpa teks pertanyaan", async () => {
		setSession({ ...USER, roles: ["admin"] });
		const res = await request(app)
			.get("/api/sita/analytics")
			.set("Cookie", SESSION);
		expect(res.status).toBe(200);
		expect(res.body.success).toBe(true);
		expect(res.body.totals.answers).toBe(0);
		expect(res.body.rates.helpful).toBe(0);
		expect(res.headers["cache-control"]).toBe("no-store");
	});

	it("memakai agregat backend bila tersedia", async () => {
		fetchSitaAnalyticsMock.mockResolvedValueOnce({
			success: true,
			totals: { answers: 7 },
			rates: { helpful: 42 },
		});
		setSession({ ...USER, roles: ["admin"] });
		const res = await request(app)
			.get("/api/sita/analytics")
			.set("Cookie", SESSION);
		expect(res.status).toBe(200);
		expect(res.body.totals.answers).toBe(7);
		expect(res.body.rates.helpful).toBe(42);
	});
});

/* ============================ /api/sita/knowledge ============================ */

describe("GET /api/sita/knowledge", () => {
	it("mengembalikan sinonim aktif dari backend", async () => {
		const synonyms = [
			{ locale: "id", term: "venu", canonical: "venue", topic: "venues" },
		];
		fetchSitaSynonymsMock.mockResolvedValueOnce(synonyms);
		const res = await request(app).get("/api/sita/knowledge");
		expect(res.status).toBe(200);
		expect(res.body).toEqual({ success: true, data: synonyms });
		expect(res.headers["cache-control"]).toBe("public, max-age=300");
	});
});

/* ============================ /api/sita/leads ============================ */

describe("POST /api/sita/leads", () => {
	const payload = {
		name: "Rina",
		phone: "+62 812-3456-7890",
		topic: "packages",
		criteria: { city: "Jakarta", guestCount: 150 },
		score: 85,
		message: "Butuh rekomendasi paket",
		website: "",
	};

	it("menyimpan lead dan mengembalikan uuid", async () => {
		const res = await request(app).post("/api/sita/leads").send(payload);
		expect(res.status).toBe(201);
		expect(res.body).toEqual({ success: true, uuid: "lead-uuid" });
		expect(createSitaLeadMock).toHaveBeenCalledWith(
			expect.objectContaining({
				name: "Rina",
				topic: "packages",
				score: 85,
			}),
		);
	});

	it("menolak payload di luar kontrak dan honeypot", async () => {
		const invalid = await request(app)
			.post("/api/sita/leads")
			.send({ ...payload, score: 101 });
		expect(invalid.status).toBe(400);
		const bot = await request(app)
			.post("/api/sita/leads")
			.send({ ...payload, website: "spam" });
		expect(bot.status).toBe(400);
		expect(createSitaLeadMock).not.toHaveBeenCalled();
	});

	it("502 bila backend gagal menyimpan", async () => {
		createSitaLeadMock.mockResolvedValueOnce(null);
		const res = await request(app).post("/api/sita/leads").send(payload);
		expect(res.status).toBe(502);
		expect(res.body.success).toBe(false);
	});
});

/* ============================ /api/logout ============================ */

describe("POST /api/logout", () => {
	it("502 bila backend tidak terjangkau", async () => {
		logoutSession.mockResolvedValue(false);
		const res = await request(app).post("/api/logout");
		expect(res.status).toBe(502);
		expect(res.body.success).toBe(false);
	});

	it("403 untuk cross-origin", async () => {
		const res = await request(app)
			.post("/api/logout")
			.set("Origin", "https://evil.test");
		expect(res.status).toBe(403);
	});

	it("200 bila backend logout berhasil", async () => {
		logoutSession.mockResolvedValue(true);
		const res = await request(app).post("/api/logout").set("Cookie", SESSION);
		expect(res.status).toBe(200);
		expect(res.body.success).toBe(true);
		expect(logoutSession).toHaveBeenCalledWith(SESSION);
	});
});

/* ====================== fallback terjemahan ====================== */

describe("fallback i18n per-key", () => {
	it("locale yang didukung maupun tidak tidak mencetak undefined", async () => {
		for (const loc of ["en", "id", "ja", "ko", "zh"]) {
			const res = await request(app).get("/").set("Cookie", `locale=${loc}`);
			expect(res.text, `locale ${loc}`).not.toContain(">undefined<");
		}
	});

	it("kunci baru ada di locale id saat login", async () => {
		setSession(USER);
		const res = await request(app)
			.get("/")
			.set("Cookie", `${SESSION}; locale=id`);
		expect(res.text).toContain("Masuk sebagai");
		expect(res.text).toContain("Selamat datang kembali");
		expect(res.text).toContain("Keluar");
	});

	it("kunci baru ada di locale en saat login", async () => {
		setSession(USER);
		const res = await request(app)
			.get("/")
			.set("Cookie", `${SESSION}; locale=en`);
		expect(res.text).toContain("Signed in as");
		expect(res.text).toContain("Welcome back");
		expect(res.text).toContain("Sign out");
	});

	it("locale tak didukung jatuh ke en", async () => {
		setSession(USER);
		for (const loc of ["ja", "ko", "zh", "fr"]) {
			const res = await request(app)
				.get("/")
				.set("Cookie", `${SESSION}; locale=${loc}`);
			expect(res.text, `locale ${loc}`).toContain("Welcome back");
			expect(res.text, `locale ${loc}`).toContain("Signed in as");
		}
	});
});
