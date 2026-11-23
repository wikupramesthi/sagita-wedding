/**
 * Tipe bersama Sagita Wedding FE.
 *
 * Semua bentuk data yang dipakai lintas middleware / routes / services
 * didefinisikan di sini. Untuk objek Express, gunakan re-export di bawah
 * agar tidak ada file bisnis yang import dari "express" langsung.
 */
import type { NextFunction, Request, Response } from "express";

/** Locale yang didukung theme (sumber: src/config/locales.ts). */
export type { Locale } from "../config/locales.js";
export type { NextFunction, Request, Response };

/** Error dengan status HTTP + detail validasi (dilempar apiClient / validate). */
export interface ApiError extends Error {
	status?: number;
	errors?: Record<string, string[]>;
	fromBackend?: boolean;
}

/** Opsi request ke backend Laravel (subset RequestInit yang dipakai theme). */
export interface ApiRequestOptions {
	method?: string;
	headers?: Record<string, string>;
	body?: string;
}

/**
 * Payload mentah dari backend Laravel.
 *
 * Boundary sengaja longgar: tiap endpoint membungkus data dengan bentuk
 * berbeda (envelope `{ data }`, array, atau objek langsung), jadi
 * normalisasi dilakukan di service — bukan di boundary ini.
 */
export type BackendPayload = any;

/** Opsi cleanText() (src/utils/validate.ts). */
export interface CleanTextOptions {
	max?: number;
	label?: string;
}

/** Identitas website — bentuk normalisasi siap pakai views (`site.*`). */
export interface SiteIdentity {
	name: string;
	title: string;
	tagline: string;
	description: string;
	phone: string;
	phoneTel: string;
	email: string;
	address: string;
	instagramHandle: string;
	instagramHref: string;
	facebookHref: string;
	youtubeHref: string;
	tiktokHref: string;
	metaTitle: string;
	metaDescription: string;
	metaKeywords: string;
	gaId: string;
	siteVerification: string;
	logoUrl: string;
	logoWhiteUrl: string;
	faviconUrl: string;
	ogImage: string;
	year: number;
}

/** Artikel blog — bentuk siap pakai views. */
export interface Article {
	id: string;
	slug: string;
	title: string;
	content: string;
	image: string;
	category: string;
	date: string;
	read: string;
	excerpt: string;
}

/** Statistik agregat publik (tanpa PII). */
export interface SiteStats {
	members: number | null;
	vendors: number | null;
}

/** Kelas yoga dari backend. */
export interface YogaClass {
	slug?: string;
	name?: string;
	description?: string;
	level?: string;
	image?: string;
	vendor?: { name?: string; avatar?: string };
}

/** Instruktur yoga. */
export interface Vendor {
	uuid?: string;
	slug?: string;
	name?: string;
	avatar?: string;
	pengalaman?: string;
	biografi?: string;
	vendors_categories?: { name: string }[];
}

/** Event / workshop. */
export interface EventItem {
	slug?: string;
	gambar?: string;
	judul?: string;
	tanggal?: string;
	waktu_mulai?: string;
	waktu_selesai?: string;
	lokasi?: string;
	kapasitas?: string | number;
	deskripsi?: string;
}

/** Testimonial shown in the homepage story carousel. */
export interface TestimonialItem {
	id?: string;
	nama?: string;
	jabatan?: string | null;
	foto?: string | null;
	isi_testimoni?: string;
}

/** Opsi harga paket membership. */
export interface PackageOption {
	name?: string;
	quota?: string | number;
	duration?: string;
	duration_unit?: string;
	price?: number | string;
	discount_price?: number | string;
	final_price?: number | string;
}

/** Fitur yang termasuk dalam paket membership. */
export interface PackageFeature {
	category?: string;
	feature?: string;
	name?: string;
	[key: string]: unknown;
}

/** Paket membership. */
export interface PackageItem {
	slug?: string;
	name?: string;
	description?: string;
	is_popular?: boolean | number | string;
	options?: PackageOption[];
	features?: PackageFeature[];
}

/** Pertanyaan yang sering ditanyakan. */
export interface FaqItem {
	question: string;
	answer: string;
}

/** Banner / gambar galeri dari backend. */
export interface BannerItem {
	gambar: string;
	nama?: string;
	posisi?: string;
}

/** Halaman CMS (konten statis yang dikelola via backend). */
export interface CmsPage {
	slug?: string;
	title: string;
	excerpt?: string;
	content: string;
	published_at?: string;
	featured_image?: string;
}

/** Local arithmetic CAPTCHA for contact forms. */
export interface ContactCaptcha {
	question?: string;
	captcha_id?: string;
	image?: string;
}

/** Payload form kontak setelah validasi (src/utils/validate.ts). */
export interface ContactPayload {
	nama: string;
	email: string;
	no_telp: string;
	isi: string;
	captcha_id: string;
	captcha_answer: string;
}

export interface BookingPayload {
	nama: string;
	email: string;
	no_telp: string;
	paket: string;
	tanggal: string;
	isi: string;
	captcha_id: string;
	captcha_answer: string;
}

/**
 * User yang sedang login, diterjemahkan dari session Laravel oleh
 * backend `GET /auth/me` (lihat src/services/authService.ts).
 */
export interface SessionUser {
	uuid?: string;
	name?: string;
	email?: string;
	avatar?: string | null;
	roles?: string[];
}
