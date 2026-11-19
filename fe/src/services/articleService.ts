import { DATE_LOCALES, DEFAULT_LOCALE } from "../config/locales.js";
import type { Article, BackendPayload, Locale } from "../types/index.js";
import apiRequest from "./apiClient.js";

const READ_TIME: Record<Locale, string> = {
	en: "5 min read",
	id: "5 mnt baca",
};

function formatArticle(
	article: Record<string, any>,
	lang: Locale = DEFAULT_LOCALE,
): Article {
	const locale = DATE_LOCALES[lang] || DATE_LOCALES[DEFAULT_LOCALE];
	return {
		id: article.uuid,
		slug: article.slug,
		title: article.title,
		content: article.content,

		// API Laravel menggunakan featured_image
		image: article.featured_image || "",

		category: article.category || "",

		date: article.created_at
			? new Date(article.created_at).toLocaleDateString(locale, {
					day: "2-digit",
					month: "short",
					year: "numeric",
				})
			: "",

		read: READ_TIME[lang] || READ_TIME[DEFAULT_LOCALE],

		excerpt: article.excerpt || "",
	};
}

export async function getArticles(
	lang: Locale = DEFAULT_LOCALE,
): Promise<Article[]> {
	const articles: BackendPayload = await apiRequest("/articles");
	return (Array.isArray(articles) ? articles : []).map((article) =>
		formatArticle(article, lang),
	);
}

export async function getArticle(
	slug: string,
	lang: Locale = DEFAULT_LOCALE,
): Promise<Article | null> {
	const article: BackendPayload = await apiRequest(
		`/articles/${encodeURIComponent(slug)}`,
	);
	if (!article) return null;
	return formatArticle(article, lang);
}
