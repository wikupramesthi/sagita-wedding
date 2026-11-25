/**
 * Data statis theme (non-CMS).
 * Konten dinamis (venue, paket, event, artikel, dst.) berasal dari backend via services/.
 * Jangan taruh konten CMS di sini � file ini hanya untuk identitas & navigasi.
 */
export const sagitaData = {
	site: {
		name: "Sagita Wedding",
		tagline: "Wedding Planner",
		description:
			"Thoughtful wedding planning, vendor coordination, and on-the-day support to help you celebrate with confidence.",
		phone: "+62 813 2122 1270",
		email: "",
		address: "",
		instagram: "",
		year: new Date().getFullYear(),
	},
	nav: [
		{ key: "home", label: "Home", href: "/" },
		{ key: "about", label: "About", href: "/about" },
		{ key: "packages", label: "Packages", href: "/packages" },
		{ key: "portfolio", label: "Portfolio", href: "/portofolio" },
		{ key: "vendors", label: "Vendors", href: "/vendors" },
		{ key: "venues", label: "Venues", href: "/venues" },
	],
};
