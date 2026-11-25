import { readdirSync, statSync } from "node:fs";
import { join } from "node:path";

const LIMITS = {
	"public/css/output.css": 120 * 1024,
	"public/css/custom.css": 80 * 1024,
};

const JS_TOTAL_LIMIT = 150 * 1024;
const JS_FILE_LIMIT = 60 * 1024;

function sizeOf(path) {
	try {
		return statSync(path).size;
	} catch {
		return 0;
	}
}

function listJs(dir) {
	try {
		return readdirSync(dir, { recursive: true })
			.filter((entry) => String(entry).endsWith(".js"))
			.map((entry) => join(dir, String(entry)));
	} catch {
		return [];
	}
}

const failures = [];

for (const [path, limit] of Object.entries(LIMITS)) {
	const size = sizeOf(path);
	if (!size) failures.push(`${path} tidak ditemukan`);
	else if (size > limit) {
		failures.push(
			`${path} ${(size / 1024).toFixed(1)}KB melebihi batas ${(limit / 1024).toFixed(0)}KB`,
		);
	}
}

const jsFiles = [...new Set(listJs("dist/client"))];
const jsTotal = jsFiles.reduce((sum, path) => sum + statSync(path).size, 0);

for (const path of jsFiles) {
	const size = statSync(path).size;
	if (size > JS_FILE_LIMIT) {
		failures.push(
			`${path} ${(size / 1024).toFixed(1)}KB melebihi batas ${(JS_FILE_LIMIT / 1024).toFixed(0)}KB`,
		);
	}
}

if (jsTotal > JS_TOTAL_LIMIT) {
	failures.push(
		`total JS ${(jsTotal / 1024).toFixed(1)}KB melebihi batas ${(JS_TOTAL_LIMIT / 1024).toFixed(0)}KB`,
	);
}

console.log(
	`Asset budget: CSS ${(sizeOf("public/css/output.css") / 1024).toFixed(1)}KB, JS ${(jsTotal / 1024).toFixed(1)}KB (${jsFiles.length} file, dist/client)`,
);

if (failures.length) {
	console.error(failures.join("\n"));
	process.exitCode = 1;
}
