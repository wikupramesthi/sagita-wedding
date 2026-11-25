import { type ChildProcess, spawn } from "node:child_process";
import { get, type IncomingHttpHeaders } from "node:http";
import { afterAll, beforeAll, describe, expect, it } from "vitest";

const PORT = "4399";
const BASE = `http://127.0.0.1:${PORT}`;
let server: ChildProcess | undefined;
let serverLog = "";

async function waitForServer(): Promise<void> {
	const deadline = Date.now() + 30_000;
	let lastError = "";
	while (Date.now() < deadline) {
		try {
			const response = await fetch(`${BASE}/robots.txt`, {
				signal: AbortSignal.timeout(1000),
			});
			if (response.ok) return;
			lastError = `HTTP ${response.status}`;
		} catch (error) {
			lastError = error instanceof Error ? error.message : String(error);
		}
		await new Promise((resolve) => setTimeout(resolve, 250));
	}
	throw new Error(`Server tidak siap: ${lastError}\n${serverLog.slice(-2000)}`);
}

function rawHeaders(
	path: string,
	acceptEncoding: string,
): Promise<IncomingHttpHeaders> {
	return new Promise((resolve, reject) => {
		get(
			`${BASE}${path}`,
			{ headers: { "accept-encoding": acceptEncoding } },
			(response) => {
				response.resume();
				resolve(response.headers);
			},
		).on("error", reject);
	});
}

beforeAll(async () => {
	server = spawn(process.execPath, ["scripts/serve.mjs"], {
		cwd: process.cwd(),
		env: {
			...process.env,
			NODE_ENV: "production",
			PORT,
			HOST: "127.0.0.1",
			SITE_URL: "https://sagita.test",
		},
		stdio: ["ignore", "pipe", "pipe"],
	});
	server.stdout?.on("data", (chunk) => {
		serverLog += String(chunk);
	});
	server.stderr?.on("data", (chunk) => {
		serverLog += String(chunk);
	});
	await waitForServer();
}, 40_000);

afterAll(async () => {
	if (!server || server.exitCode !== null) return;
	const exited = new Promise<void>((resolve) => {
		server?.once("exit", () => resolve());
	});
	server.kill("SIGTERM");
	await Promise.race([
		exited,
		new Promise<void>((resolve) => setTimeout(resolve, 3000)),
	]);
});

describe("server Astro produksi", () => {
	it("memakai nonce CSP yang sama pada script inline", async () => {
		const response = await fetch(`${BASE}/`);
		expect(response.status).toBe(200);
		const csp = response.headers.get("content-security-policy") ?? "";
		const nonce = csp.match(/'nonce-([^']+)'/)?.[1];
		expect(nonce).toBeTruthy();
		const html = await response.text();
		expect(html).toContain(`nonce="${nonce}"`);
		const inlineScripts = html.match(/<script(?![^>]*\bsrc=)[^>]*>/g) ?? [];
		expect(inlineScripts.length).toBeGreaterThan(0);
		for (const tag of inlineScripts) {
			expect(tag).toMatch(/\bnonce=/);
		}
		expect(html).toContain('"@type":"LocalBusiness"');
		expect(html).toContain("https://sagita.test/img/og-default.png");
		const assetPath = html.match(/"(\/_astro\/[^"]+)"/)?.[1];
		if (assetPath) {
			const assetResponse = await fetch(`${BASE}${assetPath}`);
			expect(assetResponse.headers.get("cache-control")).toContain("immutable");
		}
	});

	it("sitemap dan robots memakai cache control", async () => {
		for (const path of ["/sitemap.xml", "/robots.txt"]) {
			const response = await fetch(`${BASE}${path}`);
			expect(response.status).toBe(200);
			expect(response.headers.get("cache-control")).toContain("max-age=3600");
		}
	});

	it("mengompres HTML, CSS, dan JS dengan gzip saat diminta", async () => {
		const htmlHeaders = await rawHeaders("/", "gzip");
		expect(htmlHeaders["content-encoding"]).toBe("gzip");
		expect(String(htmlHeaders["vary"])).toContain("Accept-Encoding");

		const cssHeaders = await rawHeaders("/css/output.css", "gzip");
		expect(cssHeaders["content-encoding"]).toBe("gzip");

		const html = await (await fetch(`${BASE}/`)).text();
		const assetPath = html.match(/"(\/_astro\/[^"]+)"/)?.[1];
		expect(assetPath).toBeTruthy();
		if (assetPath) {
			const jsHeaders = await rawHeaders(assetPath, "gzip");
			expect(jsHeaders["content-encoding"]).toBe("gzip");
		}

		const identityHeaders = await rawHeaders("/", "identity");
		expect(identityHeaders["content-encoding"]).toBeUndefined();
	});

	it("menolak origin asing pada API", async () => {
		const response = await fetch(`${BASE}/api/telemetry`, {
			method: "POST",
			headers: {
				origin: "https://evil.example",
				"content-type": "application/json",
			},
			body: "{}",
		});
		expect(response.status).toBe(403);
	});

	it("menerima origin kanonik pada API", async () => {
		const response = await fetch(`${BASE}/api/telemetry`, {
			method: "POST",
			headers: {
				origin: "https://sagita.test",
				"content-type": "application/json",
			},
			body: "{}",
		});
		expect(response.status).not.toBe(403);
	});

	it("menolak body JSON yang besar", async () => {
		const response = await fetch(`${BASE}/api/contact`, {
			method: "POST",
			headers: {
				origin: "https://sagita.test",
				"content-type": "application/json",
			},
			body: JSON.stringify({ payload: "x".repeat(70 * 1024) }),
		});
		expect(response.status).toBe(400);
	});

	it("rute legacy 404 dan rute kanonik 200", async () => {
		const legacy = await fetch(`${BASE}/classes`);
		const canonical = await fetch(`${BASE}/`);
		expect(legacy.status).toBe(404);
		expect(canonical.status).toBe(200);
	});
});
