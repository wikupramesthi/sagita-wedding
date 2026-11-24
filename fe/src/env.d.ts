/// <reference types="astro/client" />

interface ImportMetaEnv {
	readonly PORT?: string;
}

interface ImportMeta {
	readonly env: ImportMetaEnv;
}

declare namespace App {
	type Translation = Record<string, any>;

	interface Locals {
		lang: import("./lib/config/locales.js").Locale;
		dateLocale: string;
		t: Translation;
		site: import("./lib/types/index.js").SiteIdentity;
		nav: typeof import("./lib/data/sagitaData.js").sagitaData.nav;
		languages: typeof import("./lib/config/locales.js").LANGUAGE_OPTIONS;
		contactLinks: Record<string, string>;
		currentPath: string;
		currentUrl: string;
		siteUrl: string;
		assetV: string;
		user: import("./lib/types/index.js").SessionUser | null;
		title: string;
		seo: {
			description?: string;
			keywords?: string;
			robots?: string;
			ogImage?: string;
		};
		nonce: string;
		visitorTracked?: boolean;
	}
}
