import { expect, test } from '@playwright/test';

const email = process.env.QA_EMAIL ?? 'qa.design@example.test';
const password = process.env.QA_PASSWORD ?? 'QaDesign123!';

async function expectNoHorizontalScroll(page: import('@playwright/test').Page, label: string) {
    const metrics = await page.evaluate(() => ({
        scroll: document.documentElement.scrollWidth,
        client: document.documentElement.clientWidth,
        containers: Array.from(document.querySelectorAll('.table-responsive')).map((el) => ({
            overflowX: getComputedStyle(el).overflowX,
        })),
    }));

    expect(metrics.scroll, `${label}: scrollWidth ${metrics.scroll} > clientWidth ${metrics.client}`)
        .toBeLessThanOrEqual(metrics.client + 1);

    for (const container of metrics.containers) {
        expect(['hidden', 'clip']).toContain(container.overflowX);
    }
}

async function login(page: import('@playwright/test').Page) {
    await page.goto('/auth/login');
    await page.locator('#email').fill(email);
    await page.locator('#password').fill(password);
    await page.locator('#lanjutBtn').click();
}

test('konsultasi module smoke', async ({ page }) => {
    await login(page);

    await page.goto('/backend/konsultasi');
    await expect(page.getByText('Monitoring Konsultasi')).toBeVisible();
    await expect(page.getByRole('columnheader', { name: 'Penanggung Jawab' })).toHaveCount(0);
    await expect(page.locator('.lead-table td.col-customer select[name="handled_by"]').first()).toBeVisible();
    for (const tone of ['blue', 'indigo', 'cyan', 'purple', 'warning', 'success', 'danger']) {
        await expect(page.locator('.konsultasi-toolbar .btn-soft-' + tone).first()).toBeVisible();
    }
    const actionButtonsPerRow = await page.evaluate(() =>
        Array.from(document.querySelectorAll('.lead-table td.col-action')).map(
            (cell) => cell.querySelectorAll(':scope > .dropdown > button').length
        )
    );
    expect(actionButtonsPerRow.length).toBeGreaterThan(0);
    expect(actionButtonsPerRow.every((count) => count === 1)).toBe(true);

    const badgeFontSizes = await page.evaluate(() =>
        Array.from(document.querySelectorAll('.lead-table .badge')).map((el) =>
            Number.parseFloat(getComputedStyle(el).fontSize)
        )
    );
    expect(badgeFontSizes.length).toBeGreaterThan(0);
    expect(Math.min(...badgeFontSizes)).toBeGreaterThanOrEqual(11);

    await page.locator('.konsultasi-action-menu > .btn').first().click();
    await expect(page.locator('.konsultasi-action-menu .dropdown-menu.show').first()).toBeVisible();
    await page.keyboard.press('Escape');
    await expectNoHorizontalScroll(page, 'index');

    await page.getByRole('link', { name: 'Impor' }).click();
    await expect(page.getByText('Impor Konsultasi')).toBeVisible();
    await expectNoHorizontalScroll(page, 'import');

    await page.goto('/backend/konsultasi/kanban');
    await expect(page.getByRole('heading', { name: 'Pipeline Kanban Konsultasi' }).first()).toBeVisible();
    await expect(page.getByText('Total Lead')).toBeVisible();
    await expectNoHorizontalScroll(page, 'kanban');

    await page.goto('/backend/konsultasi/duplicates');
    await expect(page.getByRole('heading', { name: 'Deteksi Duplikat Konsultasi' }).first()).toBeVisible();
    await expectNoHorizontalScroll(page, 'duplicates');
});

test('konsultasi mobile layout has no horizontal scroll', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await login(page);

    for (const [path, label] of [
        ['/backend/konsultasi', 'index'],
        ['/backend/konsultasi/kanban', 'kanban'],
        ['/backend/konsultasi/duplicates', 'duplicates'],
    ]) {
        await page.goto(path);
        await expect(page.locator('#main-content')).toBeVisible();
        if (label === 'index') {
            await expect(page.locator('.konsultasi-aging-grid').first()).toBeHidden();
            await expect(page.locator('.konsultasi-mobile-fold > summary').first()).toBeVisible();
            const mobileMetrics = await page.evaluate(() => ({
                tableTop: document.querySelector('.lead-table')?.getBoundingClientRect().top ?? 0,
                scrollHeight: document.documentElement.scrollHeight,
                clientHeight: document.documentElement.clientHeight,
            }));
            expect(mobileMetrics.tableTop).toBeLessThan(500);
            expect(mobileMetrics.scrollHeight).toBeLessThanOrEqual(mobileMetrics.clientHeight + 1);
        }
        await expectNoHorizontalScroll(page, `mobile ${label}`);
    }
});
