@echo off
cd C:\xampp\htdocs\sagita-wedding\fe
node -e '
import ejs from "ejs";
import fs from "node:fs";

const content = fs.readFileSync(
	"views/pages/packages.ejs",
	"utf8",
);

const t = {
	packagesTitleA: "Wedding",
	packagesTitleB: "Packages",
	packagesDesc: "desc",
	packagesSearchPlaceholder: "Search...",
	pagination: "Pagination",
	prevPage: "Previous",
	nextPage: "Next",
};

try {
	const fn = ejs.compile(content, { filename: "packages.ejs" });
	for (const lang of ["en", "id"]) {
		fn({
			lang,
			t,
			packages: [],
			search: null,
			filter: null,
			sort: null,
			totalPackages: 0,
			currentPage: 1,
			totalPages: 1,
			error: null,
		});
	}
	console.log("EJS COMPILE + RENDER OK (en/id, empty)");
} catch (e) {
	console.error("EJS ERROR:", e.message);
}'