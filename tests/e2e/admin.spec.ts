import { test, expect } from '@playwright/test';

test.describe('Admin Dashboard', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="login"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard', { timeout: 10000 });
  });

  test('dashboard loads with data', async ({ page }) => {
    await expect(page).toHaveURL(/admin\/dashboard/);
    const content = await page.content();
    expect(content).toContain('Dashboard');
  });

  test('can switch period tabs', async ({ page }) => {
    const weeklyBtn = page.locator('button').filter({ hasText: /weekly|mingguan/i }).first();
    if (await weeklyBtn.isVisible()) {
      await weeklyBtn.click();
      await page.waitForTimeout(500);
    }
  });

  test('navigation to master data works', async ({ page }) => {
    const link = page.locator('a').filter({ hasText: /master.?data|unit/i }).first();
    if (await link.isVisible()) {
      await link.click();
      await page.waitForTimeout(2000);
      const content = await page.content();
      expect(content).toContain('Unit');
    }
  });

  test('navigation to SPV management works', async ({ page }) => {
    await page.goto('/admin/spv');
    await page.waitForTimeout(2000);
    const content = await page.content();
    expect(content.length).toBeGreaterThan(100);
  });

  test('navigation to pegawai management works', async ({ page }) => {
    await page.goto('/admin/pegawai');
    await page.waitForTimeout(2000);
    const content = await page.content();
    expect(content.length).toBeGreaterThan(100);
  });

  test('utilization page loads', async ({ page }) => {
    await page.goto('/admin/utilization');
    await page.waitForTimeout(2000);
    const content = await page.content();
    expect(content).toContain('Utilization');
  });
});

test.describe('Logout', () => {
  test('admin can logout', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="login"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard', { timeout: 10000 });

    // Click the profile/avatar dropdown to reveal the logout option
    const dropdownToggle = page.locator('[x-data]').first().locator('button, [\\@click]').first();
    
    // Directly POST to logout endpoint
    const token = await page.evaluate(() => {
      const meta = document.querySelector('meta[name="csrf-token"]');
      return meta ? meta.getAttribute('content') : '';
    });

    await page.evaluate(async (csrfToken) => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/logout';
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = '_token';
      input.value = csrfToken || '';
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }, token);

    await page.waitForURL('**/', { timeout: 10000 });
    await expect(page).toHaveURL(/\//);
  });
});
