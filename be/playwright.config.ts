import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: './tests/e2e',
    fullyParallel: false,
    reporter: 'list',
    use: {
        baseURL: process.env.QA_BASE_URL ?? 'http://127.0.0.1:8000',
        channel: process.env.QA_BROWSER_CHANNEL ?? 'chrome',
        headless: true,
        trace: 'retain-on-failure',
    },
});
