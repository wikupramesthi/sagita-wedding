import { expect, test } from '@playwright/test';

const email = process.env.QA_EMAIL ?? 'qa.design@example.test';
const password = process.env.QA_PASSWORD ?? 'QaDesign123!';

test('package quick detail drawer is tidy', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/auth/login');
    await page.locator('#email').fill(email);
    await page.locator('#password').fill(password);
    await page.locator('#lanjutBtn').click();

    await page.goto('/backend/packages');
    await expect(page.locator('#package-table-card')).toBeVisible();
    expect(await page.locator('.pkg-cover-fallback').count()).toBe(0);
    await page.locator('.pkg-col-action [data-bs-toggle="dropdown"]').first().click();
    await page.getByRole('button', { name: 'Detail cepat' }).first().click();
    await expect(page.locator('#package-drawer.show')).toBeVisible();
    await expect(page.locator('#package-drawer-body')).not.toContainText('Foto Sampul');

    const layout = await page.evaluate(() => {
        const drawer = document.getElementById('package-drawer');
        const head = document.querySelector('.package-drawer-head');
        const body = document.getElementById('package-drawer-body');
        const footer = document.querySelector('.package-drawer-footer');
        const bodyStyle = body ? getComputedStyle(body) : null;

        return {
            viewportHeight: innerHeight,
            drawerBottom: drawer?.getBoundingClientRect().bottom ?? 0,
            footerBottom: footer?.getBoundingClientRect().bottom ?? 0,
            headDisplay: head ? getComputedStyle(head).display : '',
            bodyPadding: bodyStyle?.padding ?? '',
            bodyMinHeight: bodyStyle?.minHeight ?? '',
            bodyOverflowY: bodyStyle?.overflowY ?? '',
            bodyScrolls: Boolean(body && body.scrollHeight > body.clientHeight),
            docOverflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
            emptySections: document.querySelectorAll('#package-drawer-body .package-drawer-empty').length,
            sections: document.querySelectorAll('#package-drawer-body .package-drawer-section').length,
        };
    });

    expect(layout.headDisplay).toBe('flex');
    expect(layout.bodyPadding).toBe('18px 20px');
    expect(layout.bodyMinHeight).toBe('0px');
    expect(layout.bodyOverflowY).toBe('auto');
    expect(layout.bodyScrolls).toBe(true);
    expect(layout.footerBottom).toBeLessThanOrEqual(layout.viewportHeight + 1);
    expect(layout.drawerBottom).toBeLessThanOrEqual(layout.viewportHeight + 1);
    expect(layout.docOverflow).toBeLessThanOrEqual(1);
    expect(layout.emptySections).toBe(0);
    expect(layout.sections).toBeGreaterThan(0);
});
