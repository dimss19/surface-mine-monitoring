# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: screenshots.spec.ts >> Application Screenshots >> Capture screenshots of public and authenticated pages
- Location: tests\e2e\screenshots.spec.ts:15:3

# Error details

```
Error: page.fill: Target page, context or browser has been closed
Call log:
  - waiting for locator('input[name="login"]')

```

# Test source

```ts
  1  | // tests/e2e/screenshots.spec.ts
  2  | import { test, expect, Page } from '@playwright/test';
  3  | 
  4  | // Function to log in a user
  5  | async function login(page: Page, username: string, password_str: string) {
  6  |   await page.goto('/login');
> 7  |   await page.fill('input[name="login"]', username);
     |              ^ Error: page.fill: Target page, context or browser has been closed
  8  |   await page.fill('input[name="password"]', password_str);
  9  |   await page.click('button[type="submit"]');
  10 |   // Wait for navigation or a specific element on the dashboard to ensure login is complete
  11 |   await page.waitForURL(/\/dashboard|\/pegawai/); // Adjust based on actual post-login redirect
  12 | }
  13 | 
  14 | test.describe('Application Screenshots', () => {
  15 |   test('Capture screenshots of public and authenticated pages', async ({ page }) => {
  16 |     // Public Pages
  17 |     await page.goto('/');
  18 |     await page.screenshot({ path: 'screenshots/01_landing_page.png', fullPage: true });
  19 | 
  20 |     await page.goto('/login');
  21 |     await page.screenshot({ path: 'screenshots/02_login_page.png', fullPage: true });
  22 | 
  23 |     // Admin Pages
  24 |     await test.step('Admin Pages', async () => {
  25 |       await login(page, 'admin', 'password');
  26 |       await page.screenshot({ path: 'screenshots/03_admin_dashboard.png', fullPage: true });
  27 | 
  28 |       await page.goto('/admin/master-data');
  29 |       await page.screenshot({ path: 'screenshots/04_admin_master_data_unit.png', fullPage: true });
  30 |       
  31 |       await page.goto('/admin/master-data?tab=material');
  32 |       await page.screenshot({ path: 'screenshots/05_admin_master_data_material.png', fullPage: true });
  33 | 
  34 |       await page.goto('/admin/master-data?tab=area');
  35 |       await page.screenshot({ path: 'screenshots/06_admin_master_data_area.png', fullPage: true });
  36 | 
  37 |       await page.goto('/admin/master-data?tab=user');
  38 |       await page.screenshot({ path: 'screenshots/07_admin_master_data_user.png', fullPage: true });
  39 | 
  40 |       await page.goto('/admin/master-data?tab=hak-akses');
  41 |       await page.screenshot({ path: 'screenshots/08_admin_master_data_hak_akses.png', fullPage: true });
  42 | 
  43 |       await page.goto('/admin/master-data?tab=target');
  44 |       await page.screenshot({ path: 'screenshots/09_admin_master_data_target.png', fullPage: true });
  45 | 
  46 |       await page.goto('/admin/laporan');
  47 |       await page.screenshot({ path: 'screenshots/10_admin_laporan.png', fullPage: true });
  48 | 
  49 |       // Logout before next user type
  50 |       await page.goto('/logout', { waitUntil: 'networkidle' }); // Ensure logout request finishes
  51 |     });
  52 | 
  53 |     // SPV Pages
  54 |     await test.step('SPV Pages', async () => {
  55 |       await login(page, 'spv1', 'password');
  56 |       await page.screenshot({ path: 'screenshots/11_spv_dashboard.png', fullPage: true });
  57 | 
  58 |       await page.goto('/spv/laporan');
  59 |       await page.screenshot({ path: 'screenshots/12_spv_laporan.png', fullPage: true });
  60 | 
  61 |       // Logout
  62 |       await page.goto('/logout', { waitUntil: 'networkidle' });
  63 |     });
  64 | 
  65 |     // Pegawai Pages
  66 |     await test.step('Pegawai Pages', async () => {
  67 |       await login(page, 'pegawai.1', 'password');
  68 |       await page.screenshot({ path: 'screenshots/13_pegawai_ritasi_create.png', fullPage: true });
  69 | 
  70 |       await page.goto('/pegawai/non-ritasi/create');
  71 |       await page.screenshot({ path: 'screenshots/14_pegawai_non_ritasi_create.png', fullPage: true });
  72 |       
  73 |       await page.goto('/pegawai/general/create');
  74 |       await page.screenshot({ path: 'screenshots/15_pegawai_general_create.png', fullPage: true });
  75 | 
  76 |       await page.goto('/pegawai/ritasi/riwayat');
  77 |       await page.screenshot({ path: 'screenshots/16_pegawai_ritasi_riwayat.png', fullPage: true });
  78 |       
  79 |       await page.goto('/pegawai/non-ritasi/riwayat');
  80 |       await page.screenshot({ path: 'screenshots/17_pegawai_non_ritasi_riwayat.png', fullPage: true });
  81 | 
  82 |       await page.goto('/profile');
  83 |       await page.screenshot({ path: 'screenshots/18_profile_page.png', fullPage: true });
  84 | 
  85 |       // Final logout
  86 |       await page.goto('/logout', { waitUntil: 'networkidle' });
  87 |     });
  88 |   });
  89 | });
```