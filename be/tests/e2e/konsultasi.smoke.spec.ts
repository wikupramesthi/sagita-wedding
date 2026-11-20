import { expect, test } from '@playwright/test';

const email = process.env.QA_EMAIL ?? 'qa.design@example.test';
const password = process.env.QA_PASSWORD ?? 'QaDesign123!';

async function expectNoHorizontalScroll(
    page: import('@playwright/test').Page,
    label: string,
    options: { allowAutoContainers?: boolean } = {}
) {
    await expect(page.locator('#app-loader')).toBeHidden();

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
        if (options.allowAutoContainers) {
            continue;
        }
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

    const statGrid = await page.locator('.konsultasi-stat-grid').evaluate((el) => {
        const style = getComputedStyle(el);
        return {
            columns: style.gridTemplateColumns.split(' ').filter(Boolean).length,
            rows: style.gridTemplateRows.split(' ').filter(Boolean).length,
            cards: el.querySelectorAll(':scope > div').length,
        };
    });
    expect(statGrid.columns).toBe(4);
    expect(statGrid.rows).toBe(2);
    expect(statGrid.cards).toBe(8);

    await expect(page.locator('.konsultasi-followup-control input[type="date"]').first()).toBeVisible();

    await page.getByRole('button', { name: /Kolom/ }).click();
    await page.locator('[data-konsultasi-column="contact"]').uncheck();
    await expect(page.locator('.lead-table th.col-contact').first()).toBeHidden();
    await page.locator('[data-konsultasi-column="contact"]').check();
    await expect(page.locator('.lead-table th.col-contact').first()).toBeVisible();

    await page.evaluate(() => (document.activeElement as HTMLElement | null)?.blur());
    await page.keyboard.press('/');
    await expect(page.locator('#filter-q')).toBeFocused();

    await page.goto('/backend/konsultasi?lead=New');
    await expect(page.locator('.konsultasi-active-filters .badge').first()).toBeVisible();
    await page.goto('/backend/konsultasi');

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
            await expect(page.locator('#app-loader')).toBeHidden();
            await expect(page.locator('.konsultasi-aging-grid').first()).toBeHidden();
            await expect(page.locator('.konsultasi-mobile-fold > summary').first()).toBeVisible();
            const mobileMetrics = await page.evaluate(() => ({
                tableTop: document.querySelector('.lead-table')?.getBoundingClientRect().top ?? 0,
            }));
            expect(mobileMetrics.tableTop).toBeLessThan(500);
        }
        await expectNoHorizontalScroll(page, `mobile ${label}`);
    }
});

test('dashboard highlights konsultasi tanpa card lama', async ({ page }) => {
    await login(page);

    await page.goto('/backend/dashboard');
    await expect(page.getByText('Pipeline konsultasi')).toBeVisible();
    await expect(page.getByText('Calon Konsumen Baru')).toBeVisible();
    const statHeight = await page.locator('.dashboard-stat-grid').evaluate((el) => el.getBoundingClientRect().height);
    expect(statHeight).toBeLessThan(420);
    await expect(page.getByText('Kategori vendor aktif')).toHaveCount(0);
    await expect(page.getByText('Pertanyaan masuk', { exact: true })).toHaveCount(0);
    await expect(page.getByText('Check-in menunggu')).toHaveCount(0);
    await expect(page.getByText('Keanggotaan segera habis')).toHaveCount(0);
    await expect(page.getByText('Kelas mendatang')).toHaveCount(0);
    await expect(page.locator('.dashboard-date-filter .btn-success').first()).toBeVisible();
    await expect(page.locator('.dashboard-date-filter .btn-danger').first()).toBeVisible();
    await expect(page.locator('.dashboard-date-filter .btn-warning').first()).toBeVisible();

    const avatar = page.locator('.dashboard-avatar .avatar-content').first();
    await expect(avatar).toBeVisible();
    const avatarStyle = await avatar.evaluate((el) => {
        const parent = el as HTMLElement;
        const icon = parent.querySelector('i') as HTMLElement | null;
        return {
            width: parent.getBoundingClientRect().width,
            height: parent.getBoundingClientRect().height,
            background: getComputedStyle(parent).backgroundColor,
            color: icon ? getComputedStyle(icon).color : '',
        };
    });
    expect(avatarStyle.width).toBeGreaterThanOrEqual(36);
    expect(avatarStyle.height).toBeGreaterThanOrEqual(36);
    expect(avatarStyle.color).not.toBe(avatarStyle.background);
    await expectNoHorizontalScroll(page, 'dashboard desktop', { allowAutoContainers: true });

    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/backend/dashboard');
    await expect(page.locator('.dashboard-details-fold')).not.toHaveAttribute('open', '');
    await page.locator('.dashboard-details-summary').click();
    await expect(page.getByText('Pipeline konsultasi')).toBeVisible();
    await expect(page.getByText('Calon Konsumen Baru')).toBeVisible();
    await expectNoHorizontalScroll(page, 'dashboard mobile', { allowAutoContainers: true });
});
