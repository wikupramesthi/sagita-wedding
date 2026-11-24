import { beforeEach, describe, expect, it, vi } from "vitest";

const { apiEnvelopeRawMock } = vi.hoisted(() => ({
	apiEnvelopeRawMock: vi.fn(),
}));

vi.mock("../src/services/apiClient.js", async (importOriginal) => {
	const actual =
		await importOriginal<typeof import("../src/services/apiClient.js")>();
	return { ...actual, apiEnvelopeRaw: apiEnvelopeRawMock };
});

const { clearBatchCache, getBatchContentUpdatedAt, getPageBatch } =
	await import("../src/services/batchService.js");

beforeEach(() => {
	clearBatchCache();
	apiEnvelopeRawMock.mockReset();
});

describe("batch cache dengan revalidasi ETag", () => {
	it("membuka amplop data sebelum dikembalikan ke halaman", async () => {
		apiEnvelopeRawMock.mockResolvedValueOnce({
			status: 200,
			body: {
				status: "success",
				page: "venues",
				data: { venues: [{ name: "Venue A" }] },
			},
			etag: '"v1"',
		});

		expect(await getPageBatch("venues")).toEqual({
			venues: [{ name: "Venue A" }],
		});
	});

	it("memakai body cache saat backend membalas 304", async () => {
		const body = { venues: [{ name: "Venue A" }] };
		apiEnvelopeRawMock
			.mockResolvedValueOnce({ status: 200, body, etag: '"v1"' })
			.mockResolvedValueOnce({ status: 304, body: null, etag: '"v1"' });

		expect(await getPageBatch("venues")).toEqual(body);
		expect(await getPageBatch("venues")).toEqual(body);
		expect(apiEnvelopeRawMock).toHaveBeenCalledTimes(2);
		expect(apiEnvelopeRawMock.mock.calls[1]).toEqual([
			"/batch/venues",
			{ headers: { "If-None-Match": '"v1"' } },
		]);
	});

	it("meneruskan kebaruan konten dari payload batch termasuk respons 304", async () => {
		const updatedAt = "2026-03-10T12:30:00.000000Z";
		const body = {
			status: "success",
			page: "venues",
			data: { venues: [{ name: "Venue A" }], contentUpdatedAt: updatedAt },
		};
		apiEnvelopeRawMock
			.mockResolvedValueOnce({ status: 200, body, etag: '"v1"' })
			.mockResolvedValueOnce({ status: 304, body: null, etag: '"v1"' });

		await getPageBatch("venues");
		expect(getBatchContentUpdatedAt()).toBe(updatedAt);

		await getPageBatch("venues");
		expect(getBatchContentUpdatedAt()).toBe(updatedAt);
	});

	it("menggantikan cache begitu ETag berubah setelah perubahan admin", async () => {
		apiEnvelopeRawMock
			.mockResolvedValueOnce({
				status: 200,
				body: { venues: [{ name: "Lama" }] },
				etag: '"v1"',
			})
			.mockResolvedValueOnce({
				status: 200,
				body: { venues: [{ name: "Baru" }] },
				etag: '"v2"',
			})
			.mockResolvedValueOnce({ status: 304, body: null, etag: '"v2"' });

		expect(await getPageBatch("venues")).toEqual({
			venues: [{ name: "Lama" }],
		});
		expect(await getPageBatch("venues")).toEqual({
			venues: [{ name: "Baru" }],
		});
		expect(await getPageBatch("venues")).toEqual({
			venues: [{ name: "Baru" }],
		});
		expect(apiEnvelopeRawMock.mock.calls[2][1]).toEqual({
			headers: { "If-None-Match": '"v2"' },
		});
	});

	it("menggabungkan request bersamaan untuk halaman dan query yang sama", async () => {
		let resolveRequest: (value: unknown) => void = () => {};
		apiEnvelopeRawMock.mockImplementationOnce(
			() =>
				new Promise((resolve) => {
					resolveRequest = resolve;
				}),
		);

		const first = getPageBatch("packages", { page: 2 });
		const second = getPageBatch("packages", { page: 2 });
		expect(apiEnvelopeRawMock).toHaveBeenCalledTimes(1);

		resolveRequest({
			status: 200,
			body: { packages: [{ name: "Paket Uji" }] },
			etag: '"v1"',
		});

		expect(await first).toEqual(await second);
		expect(apiEnvelopeRawMock).toHaveBeenCalledTimes(1);
		expect(apiEnvelopeRawMock.mock.calls[0][0]).toBe("/batch/packages?page=2");
	});

	it("membedakan cache untuk query yang berbeda", async () => {
		apiEnvelopeRawMock
			.mockResolvedValueOnce({ status: 200, body: { page: "a" }, etag: '"a"' })
			.mockResolvedValueOnce({ status: 200, body: { page: "b" }, etag: '"b"' });

		expect(await getPageBatch("page-detail", { slug: "a" })).toEqual({
			page: "a",
		});
		expect(await getPageBatch("page-detail", { slug: "b" })).toEqual({
			page: "b",
		});
		expect(apiEnvelopeRawMock.mock.calls.map((call) => call[0])).toEqual([
			"/batch/page-detail?slug=a",
			"/batch/page-detail?slug=b",
		]);
	});

	it("tidak menyimpan body kosong ketika request gagal", async () => {
		apiEnvelopeRawMock
			.mockResolvedValueOnce({
				status: 200,
				body: { venues: [{ name: "A" }] },
				etag: '"v1"',
			})
			.mockRejectedValueOnce(new Error("backend down"));

		await getPageBatch("venues");
		await expect(getPageBatch("venues")).rejects.toThrow("backend down");
	});
});
