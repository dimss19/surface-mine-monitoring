import { test, expect } from '@playwright/test';

async function loginAs(page: any, username: string) {
  await page.goto('/login');
  await page.fill('input[name="login"]', username);
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
}

test.describe('SPV Dashboard', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'spv1');
    await page.waitForURL('**/spv/dashboard', { timeout: 10000 });
  });

  test('spv dashboard loads', async ({ page }) => {
    await expect(page).toHaveURL(/spv\/dashboard/);
    const content = await page.content();
    expect(content).toContain('Dashboard');
  });

  test('spv laporan page loads', async ({ page }) => {
    await page.goto('/spv/laporan');
    await page.waitForTimeout(2000);
    const content = await page.content();
    expect(content.length).toBeGreaterThan(100);
  });

  test('spv utilization page loads', async ({ page }) => {
    await page.goto('/spv/utilization');
    await page.waitForTimeout(2000);
    const content = await page.content();
    expect(content).toContain('Utilization');
  });
});

test.describe('Pegawai Ritasi Flow', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, 'pegawai.1');
    await page.waitForURL('**/pegawai/ritasi/create', { timeout: 10000 });
  });

  test('ritasi form loads', async ({ page }) => {
    await expect(page).toHaveURL(/pegawai\/ritasi\/create/);
    const content = await page.content();
    expect(content.length).toBeGreaterThan(100);
  });
});

test.describe('Pegawai Non-Ritasi Flow', () => {
  test('non-ritasi form loads', async ({ page }) => {
    await loginAs(page, 'pegawai.1');
    await page.waitForURL('**/pegawai/ritasi/create', { timeout: 10000 });

    await page.goto('/pegawai/non-ritasi/create');
    await page.waitForTimeout(2000);
    const content = await page.content();
    expect(content.length).toBeGreaterThan(100);
  });
});
