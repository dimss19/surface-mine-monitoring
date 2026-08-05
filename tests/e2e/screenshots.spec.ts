// tests/e2e/screenshots.spec.ts
import { test, expect, Page } from '@playwright/test';

// Function to log in a user
async function login(page: Page, username: string, password_str: string) {
  await page.goto('/login');
  await page.fill('input[name="login"]', username);
  await page.fill('input[name="password"]', password_str);
  await page.click('button[type="submit"]');
  // Wait for navigation or a specific element on the dashboard to ensure login is complete
  await page.waitForURL(/\/dashboard|\/pegawai/); // Adjust based on actual post-login redirect
}

test.describe('Application Screenshots', () => {
  test('Capture screenshots of public and authenticated pages', async ({ page }) => {
    // Public Pages
    await page.goto('/');
    await page.screenshot({ path: 'screenshots/01_landing_page.png', fullPage: true });

    await page.goto('/login');
    await page.screenshot({ path: 'screenshots/02_login_page.png', fullPage: true });

    // Admin Pages
    await test.step('Admin Pages', async () => {
      await login(page, 'admin', 'password');
      await page.screenshot({ path: 'screenshots/03_admin_dashboard.png', fullPage: true });

      await page.goto('/admin/master-data');
      await page.screenshot({ path: 'screenshots/04_admin_master_data_unit.png', fullPage: true });
      
      await page.goto('/admin/master-data?tab=material');
      await page.screenshot({ path: 'screenshots/05_admin_master_data_material.png', fullPage: true });

      await page.goto('/admin/master-data?tab=area');
      await page.screenshot({ path: 'screenshots/06_admin_master_data_area.png', fullPage: true });

      await page.goto('/admin/master-data?tab=user');
      await page.screenshot({ path: 'screenshots/07_admin_master_data_user.png', fullPage: true });

      await page.goto('/admin/master-data?tab=hak-akses');
      await page.screenshot({ path: 'screenshots/08_admin_master_data_hak_akses.png', fullPage: true });

      await page.goto('/admin/master-data?tab=target');
      await page.screenshot({ path: 'screenshots/09_admin_master_data_target.png', fullPage: true });

      await page.goto('/admin/laporan');
      await page.screenshot({ path: 'screenshots/10_admin_laporan.png', fullPage: true });

      // Logout before next user type
      await page.goto('/logout', { waitUntil: 'networkidle' }); // Ensure logout request finishes
    });

    // SPV Pages
    await test.step('SPV Pages', async () => {
      await login(page, 'spv1', 'password');
      await page.screenshot({ path: 'screenshots/11_spv_dashboard.png', fullPage: true });

      await page.goto('/spv/laporan');
      await page.screenshot({ path: 'screenshots/12_spv_laporan.png', fullPage: true });

      // Logout
      await page.goto('/logout', { waitUntil: 'networkidle' });
    });

    // Pegawai Pages
    await test.step('Pegawai Pages', async () => {
      await login(page, 'pegawai.1', 'password');
      await page.screenshot({ path: 'screenshots/13_pegawai_ritasi_create.png', fullPage: true });

      await page.goto('/pegawai/non-ritasi/create');
      await page.screenshot({ path: 'screenshots/14_pegawai_non_ritasi_create.png', fullPage: true });
      
      await page.goto('/pegawai/general/create');
      await page.screenshot({ path: 'screenshots/15_pegawai_general_create.png', fullPage: true });

      await page.goto('/pegawai/ritasi/riwayat');
      await page.screenshot({ path: 'screenshots/16_pegawai_ritasi_riwayat.png', fullPage: true });
      
      await page.goto('/pegawai/non-ritasi/riwayat');
      await page.screenshot({ path: 'screenshots/17_pegawai_non_ritasi_riwayat.png', fullPage: true });

      await page.goto('/profile');
      await page.screenshot({ path: 'screenshots/18_profile_page.png', fullPage: true });

      // Final logout
      await page.goto('/logout', { waitUntil: 'networkidle' });
    });
  });
});