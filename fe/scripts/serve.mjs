import { readFileSync } from "node:fs";
import { createServer as createHttpServer } from "node:http";
import { createServer as createHttpsServer } from "node:https";
import { gzipSync } from "node:zlib";

process.env.ASTRO_NODE_AUTOSTART = "disabled";
const { handler, options } = await import("../dist/server/entry.mjs");

const COMPRESSIBLE_TYPE =
	/^(?:text\/|application\/(?:javascript|json|xml|x-javascript)|image\/svg\+xml)/i;
const COMPRESSIBLE_PATH = /\.(?:html?|css|js|mjs|json|xml|svg|txt)(?:\?|$)/i;
const MIN_COMPRESS_BYTES = 1024;

function acceptsGzip(value) {
	return String(value ?? "")
		.split(",")
		.some((entry) => {
			const [name, ...params] = entry.trim().split(";");
			if (name.toLowerCase() !== "gzip") return false;
			const quality = params.find((param) => param.trim().startsWith("q="));
			return !quality || Number(quality.split("=")[1]) > 0;
		});
}

function shouldBuffer(req) {
	if (req.method === "HEAD" || !acceptsGzip(req.headers["accept-encoding"])) {
		return false;
	}
	const path = req.url ?? "";
	return path === "/" || !path.includes(".") || COMPRESSIBLE_PATH.test(path);
}

function toBuffer(chunk, encoding) {
	if (Buffer.isBuffer(chunk)) return chunk;
	if (typeof chunk === "string") return Buffer.from(chunk, encoding ?? "utf8");
	return Buffer.from(chunk);
}

function withVary(current) {
	const existing = Array.isArray(current)
		? current.join(", ")
		: String(current ?? "");
	if (/\baccept-encoding\b/i.test(existing)) return existing;
	return existing ? `${existing}, Accept-Encoding` : "Accept-Encoding";
}

function writeHeadThenEnd(
	originalWriteHead,
	originalEnd,
	status,
	reason,
	body,
	callback,
) {
	if (reason) originalWriteHead(status, reason);
	else originalWriteHead(status);
	originalEnd(body, callback);
}

function gzipResponse(req, res) {
	if (!shouldBuffer(req)) {
		handler(req, res);
		return;
	}

	const chunks = [];
	const originalWriteHead = res.writeHead.bind(res);
	const originalEnd = res.end.bind(res);
	let pendingStatus = res.statusCode;
	let pendingReason = "";
	let pendingHeaders = null;

	res.writeHead = (status, reasonOrHeaders, maybeHeaders) => {
		pendingStatus = status;
		res.statusCode = status;
		if (typeof reasonOrHeaders === "string") {
			pendingReason = reasonOrHeaders;
		} else {
			pendingHeaders =
				reasonOrHeaders && typeof reasonOrHeaders === "object"
					? reasonOrHeaders
					: maybeHeaders;
		}
		return res;
	};

	res.write = (chunk, encoding, callback) => {
		if (chunk)
			chunks.push(
				toBuffer(chunk, typeof encoding === "string" ? encoding : undefined),
			);
		const done = typeof encoding === "function" ? encoding : callback;
		if (typeof done === "function") done();
		return true;
	};

	res.end = (chunk, encoding, callback) => {
		if (chunk)
			chunks.push(
				toBuffer(chunk, typeof encoding === "string" ? encoding : undefined),
			);
		const done = typeof encoding === "function" ? encoding : callback;
		if (pendingHeaders) {
			for (const [key, value] of Object.entries(pendingHeaders)) {
				if (value !== undefined) res.setHeader(key, value);
			}
		}

		const body = Buffer.concat(chunks);
		const contentType = String(res.getHeader("content-type") ?? "");
		const canGzip =
			pendingStatus >= 200 &&
			pendingStatus < 300 &&
			!res.hasHeader("content-encoding") &&
			COMPRESSIBLE_TYPE.test(contentType) &&
			body.byteLength >= MIN_COMPRESS_BYTES;

		if (canGzip) {
			const compressed = gzipSync(body, { level: 6 });
			res.setHeader("content-encoding", "gzip");
			res.setHeader("vary", withVary(res.getHeader("vary")));
			res.removeHeader("content-length");
			res.setHeader("content-length", String(compressed.length));
			writeHeadThenEnd(
				originalWriteHead,
				originalEnd,
				pendingStatus,
				pendingReason,
				compressed,
				done,
			);
			return;
		}

		if (body.length && !res.hasHeader("content-length")) {
			res.setHeader("content-length", String(body.length));
		}
		writeHeadThenEnd(
			originalWriteHead,
			originalEnd,
			pendingStatus,
			pendingReason,
			body,
			done,
		);
	};

	handler(req, res);
}

const port = Number(process.env.PORT ?? options.port ?? 8080);
const host =
	process.env.HOST ??
	(typeof options.host === "string" ? options.host : "0.0.0.0");
const certPath = process.env.SERVER_CERT_PATH;
const keyPath = process.env.SERVER_KEY_PATH;
const server =
	certPath && keyPath
		? createHttpsServer(
				{ cert: readFileSync(certPath), key: readFileSync(keyPath) },
				gzipResponse,
			)
		: createHttpServer(gzipResponse);

server.listen(port, host, () => {
	if (process.env.ASTRO_NODE_LOGGING !== "disabled") {
		console.log(`Server ready on http://${host}:${port}`);
	}
});
