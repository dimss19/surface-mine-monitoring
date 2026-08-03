import { test, expect } from '@playwright/test';

test.describe('Landing Page', () => {
  test('landing page loads', async ({ page }) => {
    await page.goto('/');
    const content = await page.content();
    expect(content.length).toBeGreaterThan(100);
  });
});

test.describe('Public Route Protection', () => {
  test('admin dashboard redirects to login when not authenticated', async ({ page }) => {
    await page.goto('/admin/dashboard');
    await page.waitForTimeout(2000);
    await expect(page).toHaveURL(/login/);
  });

  test('spv dashboard redirects to login when not authenticated', async ({ page }) => {
    await page.goto('/spv/dashboard');
    await page.waitForTimeout(2000);
    await expect(page).toHaveURL(/login/);
  });

  test('profile redirects to login when not authenticated', async ({ page }) => {
    await page.goto('/profile');
    await page.waitForTimeout(2000);
    await expect(page).toHaveURL(/login/);
  });
});

test.describe('Register Page', () => {
  test('register page renders', async ({ page }) => {
    await page.goto('/register');
    await expect(page.locator('input[name="username"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
  });
});

test.describe('Login Page - Removed Routes', () => {
  test('forgot-password route returns 404', async ({ page }) => {
    const response = await page.goto('/forgot-password');
    expect(response?.status()).toBe(404);
  });

  test('verify-email route returns 404', async ({ page }) => {
    const response = await page.goto('/verify-email');
    expect(response?.status()).toBe(404);
  });
});
