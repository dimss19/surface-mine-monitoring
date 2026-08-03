import { test, expect } from '@playwright/test';

test.describe('Login Flow', () => {
  test('login page renders correctly', async ({ page }) => {
    await page.goto('/login');
    await expect(page.locator('input[name="login"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('admin can login successfully', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="login"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin/dashboard', { timeout: 10000 });
    await expect(page).toHaveURL(/admin\/dashboard/);
  });

  test('login with wrong password shows error', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="login"]', 'admin');
    await page.fill('input[name="password"]', 'wrongpassword');
    await page.click('button[type="submit"]');
    await page.waitForTimeout(2000);
    await expect(page).toHaveURL(/login/);
  });

  test('login with non-existent user shows error', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="login"]', 'nonexistent');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForTimeout(2000);
    await expect(page).toHaveURL(/login/);
  });
});

test.describe('SPV Login', () => {
  test('spv can login and reaches dashboard', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="login"]', 'spv1');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/spv/dashboard', { timeout: 10000 });
    await expect(page).toHaveURL(/spv\/dashboard/);
  });
});

test.describe('Pegawai Login', () => {
  test('pegawai can login and reaches input form', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="login"]', 'pegawai.1');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/pegawai/ritasi/create', { timeout: 10000 });
    await expect(page).toHaveURL(/pegawai\/ritasi\/create/);
  });
});
