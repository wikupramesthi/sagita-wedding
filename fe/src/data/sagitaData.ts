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
		{ key: "portfolio", label: "Portfolio", href: "#" },
		{ key: "vendors", label: "Vendors", href: "/vendors" },
		{ key: "venues", label: "Venues", href: "/venues" },
		{ key: "faq", label: "FAQs", href: "/faq" },
	],
	contact: {
		mapEmbed:
			"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4880.551036860198!2d106.83020387586802!3d-6.20872306081883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f591a35191c3%3A0x255c635679625e83!2sYoga%20Roots!5e1!3m2!1sen!2sid!4v1791260834023!5m2!1sen!2sid",
	},
};
