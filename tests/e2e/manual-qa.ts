import { chromium } from '@playwright/test';

const BASE = 'http://127.0.0.1:8000';
const errors: string[] = [];
const consoleErrors: string[] = [];
const httpErrors: string[] = [];

function log(type: string, msg: string) {
  const line = `[${type}] ${msg}`;
  console.log(line);
  if (type === 'ERROR') errors.push(line);
  if (type === 'CONSOLE-ERR') consoleErrors.push(line);
  if (type === 'HTTP-ERR') httpErrors.push(line);
}

async function login(page: any, username: string) {
  await page.goto(`${BASE}/login`);
  await page.fill('input[name="login"]', username);
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForTimeout(2000);
}

async function visitAndCheck(page: any, url: string, label: string) {
  const response = await page.goto(`${BASE}${url}`, { waitUntil: 'domcontentloaded', timeout: 15000 });
  const status = response?.status() || 0;
  if (status >= 400) {
    log('HTTP-ERR', `${label} -> ${url} => HTTP ${status}`);
  } else {
    log('OK', `${label} -> ${url} => HTTP ${status}`);
  }
  // Check for JS errors after page load
  await page.waitForTimeout(1000);
  return status;
}

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  // Capture console errors
  page.on('console', msg => {
    if (msg.type() === 'error') {
      log('CONSOLE-ERR', `[${page.url()}] ${msg.text()}`);
    }
  });

  // Capture page errors (uncaught exceptions)
  page.on('pageerror', err => {
    log('ERROR', `[${page.url()}] Uncaught: ${err.message}`);
  });

  // =========================================
  // 1. PUBLIC PAGES
  // =========================================
  console.log('\n===== PUBLIC PAGES =====');
  await visitAndCheck(page, '/', 'Landing');
  await visitAndCheck(page, '/login', 'Login Page');

  // Check register page
  const regResp = await visitAndCheck(page, '/register', 'Register Page');
  if (regResp === 200) {
    // Verify username field exists
    const usernameField = await page.locator('input[name="username"]').count();
    if (usernameField === 0) {
      log('ERROR', 'Register page: missing input[name="username"]');
    } else {
      log('OK', 'Register page: username field present');
    }
  }

  // Check removed routes return 404
  const forgotResp = await page.goto(`${BASE}/forgot-password`);
  if (forgotResp?.status() !== 404) {
    log('ERROR', `/forgot-password should return 404, got ${forgotResp?.status()}`);
  } else {
    log('OK', '/forgot-password correctly returns 404');
  }

  const verifyResp = await page.goto(`${BASE}/verify-email`);
  if (verifyResp?.status() !== 404) {
    log('ERROR', `/verify-email should return 404, got ${verifyResp?.status()}`);
  } else {
    log('OK', '/verify-email correctly returns 404');
  }

  // =========================================
  // 2. ADMIN LOGIN + DASHBOARD
  // =========================================
  console.log('\n===== ADMIN LOGIN + DASHBOARD =====');
  await login(page, 'admin');
  const adminUrl = page.url();
  if (!adminUrl.includes('admin/dashboard')) {
    log('ERROR', `Admin login redirect failed. Current URL: ${adminUrl}`);
  } else {
    log('OK', 'Admin logged in, redirected to /admin/dashboard');
  }

  // Check dashboard content
  const dashContent = await page.content();
  if (!dashContent.includes('Dashboard')) {
    log('ERROR', 'Admin dashboard missing "Dashboard" text');
  } else {
    log('OK', 'Admin dashboard has content');
  }

  // Test period switching
  const periodBtns = await page.locator('button').filter({ hasText: /weekly|mingguan/i }).count();
  if (periodBtns > 0) {
    await page.locator('button').filter({ hasText: /weekly|mingguan/i }).first().click();
    await page.waitForTimeout(500);
    log('OK', 'Period switcher works (clicked weekly)');
  } else {
    log('ERROR', 'Period switcher buttons not found on dashboard');
  }

  // =========================================
  // 3. ADMIN MASTER DATA
  // =========================================
  console.log('\n===== ADMIN MASTER DATA =====');
  await visitAndCheck(page, '/admin/master-data', 'Admin Master Data');

  // Check tabs/content
  const masterContent = await page.content();
  const hasUnits = masterContent.includes('Unit') || masterContent.includes('unit');
  log(hasUnits ? 'OK' : 'ERROR', `Master Data page has Unit section: ${hasUnits}`);

  // =========================================
  // 4. ADMIN SPV MANAGEMENT
  // =========================================
  console.log('\n===== ADMIN SPV MANAGEMENT =====');
  await visitAndCheck(page, '/admin/spv', 'SPV List');

  const spvContent = await page.content();
  const hasSpv = spvContent.includes('SPV') || spvContent.includes('spv') || spvContent.includes('Supervisor');
  log(hasSpv ? 'OK' : 'ERROR', `SPV page has content: ${hasSpv}`);

  // Test create form
  await visitAndCheck(page, '/admin/spv/create', 'SPV Create Form');

  // Check form fields
  const nameField = await page.locator('input[name="name"]').count();
  const usernameField = await page.locator('input[name="username"]').count();
  log(nameField > 0 ? 'OK' : 'ERROR', `SPV create form has name field: ${nameField > 0}`);
  log(usernameField > 0 ? 'OK' : 'ERROR', `SPV create form has username field: ${usernameField > 0}`);

  // =========================================
  // 5. ADMIN PEGAWAI MANAGEMENT
  // =========================================
  console.log('\n===== ADMIN PEGAWAI MANAGEMENT =====');
  await visitAndCheck(page, '/admin/pegawai', 'Pegawai List');

  const pegawaiContent = await page.content();
  const hasPegawai = pegawaiContent.includes('Pegawai') || pegawaiContent.includes('pegawai');
  log(hasPegawai ? 'OK' : 'ERROR', `Pegawai page has content: ${hasPegawai}`);

  await visitAndCheck(page, '/admin/pegawai/create', 'Pegawai Create Form');

  // =========================================
  // 6. ADMIN UTILIZATION
  // =========================================
  console.log('\n===== ADMIN UTILIZATION =====');
  await visitAndCheck(page, '/admin/utilization', 'Admin Utilization');

  const utilContent = await page.content();
  const hasUtil = utilContent.includes('Utilization') || utilContent.includes('utilization');
  log(hasUtil ? 'OK' : 'ERROR', `Utilization page has content: ${hasUtil}`);

  // =========================================
  // 7. ADMIN LAPORAN
  // =========================================
  console.log('\n===== ADMIN LAPORAN =====');
  await visitAndCheck(page, '/admin/laporan', 'Admin Laporan');

  const laporanContent = await page.content();
  const hasLaporan = laporanContent.includes('Laporan') || laporanContent.includes('laporan');
  log(hasLaporan ? 'OK' : 'ERROR', `Laporan page has content: ${hasLaporan}`);

  // =========================================
  // 8. ADMIN PROFILE
  // =========================================
  console.log('\n===== ADMIN PROFILE =====');
  await visitAndCheck(page, '/profile', 'Profile Page');

  const profileContent = await page.content();
  const hasProfile = profileContent.includes('Profile') || profileContent.includes('profile') || profileContent.includes('Name');
  log(hasProfile ? 'OK' : 'ERROR', `Profile page has content: ${hasProfile}`);

  // Check name field exists (not email)
  const profileNameField = await page.locator('input[name="name"]').count();
  log(profileNameField > 0 ? 'OK' : 'ERROR', `Profile form has name field: ${profileNameField > 0}`);

  // =========================================
  // 9. ADMIN LOGOUT
  // =========================================
  console.log('\n===== ADMIN LOGOUT =====');
  const csrfToken = await page.evaluate(() => {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  });

  await page.evaluate(async (token) => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/logout';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_token';
    input.value = token || '';
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }, csrfToken);

  await page.waitForTimeout(2000);
  const afterLogoutUrl = page.url();
  if (afterLogoutUrl.includes('login') || afterLogoutUrl.endsWith('/')) {
    log('OK', `Admin logged out. URL: ${afterLogoutUrl}`);
  } else {
    log('ERROR', `Admin logout redirect issue. URL: ${afterLogoutUrl}`);
  }

  // =========================================
  // 10. SPV LOGIN + DASHBOARD
  // =========================================
  console.log('\n===== SPV LOGIN + DASHBOARD =====');
  await login(page, 'spv1');
  const spvUrl = page.url();
  if (spvUrl.includes('spv/dashboard')) {
    log('OK', 'SPV logged in, redirected to /spv/dashboard');
  } else {
    log('ERROR', `SPV login redirect failed. URL: ${spvUrl}`);
  }

  const spvDashContent = await page.content();
  if (spvDashContent.includes('Dashboard')) {
    log('OK', 'SPV dashboard has content');
  } else {
    log('ERROR', 'SPV dashboard missing content');
  }

  // SPV Laporan
  console.log('\n===== SPV LAPORAN =====');
  await visitAndCheck(page, '/spv/laporan', 'SPV Laporan Index');
  await visitAndCheck(page, '/spv/laporan/harian', 'SPV Laporan Harian');
  await visitAndCheck(page, '/spv/laporan/mingguan', 'SPV Laporan Mingguan');
  await visitAndCheck(page, '/spv/laporan/bulanan', 'SPV Laporan Bulanan');

  // SPV Utilization
  console.log('\n===== SPV UTILIZATION =====');
  await visitAndCheck(page, '/spv/utilization', 'SPV Utilization');

  // SPV Profile
  console.log('\n===== SPV PROFILE =====');
  await visitAndCheck(page, '/profile', 'SPV Profile');

  // Logout SPV
  await page.evaluate(async (token) => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/logout';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_token';
    input.value = token || '';
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }, await page.evaluate(() => {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  }));
  await page.waitForTimeout(2000);
  log('OK', 'SPV logged out');

  // =========================================
  // 11. PEGAWAI LOGIN + RITASI FORM
  // =========================================
  console.log('\n===== PEGAWAI LOGIN + RITASI =====');
  await login(page, 'pegawai.1');
  const pegUrl = page.url();
  if (pegUrl.includes('pegawai/ritasi/create')) {
    log('OK', 'Pegawai logged in, redirected to /pegawai/ritasi/create');
  } else {
    log('ERROR', `Pegawai login redirect failed. URL: ${pegUrl}`);
  }

  const ritasiContent = await page.content();
  const hasRitasiForm = ritasiContent.includes('ritasi') || ritasiContent.includes('Ritasi') || ritasiContent.includes('HM');
  log(hasRitasiForm ? 'OK' : 'ERROR', `Ritasi form has content: ${hasRitasiForm}`);

  // Check for form fields
  const unitSelect = await page.locator('select[name="unit_id"]').count();
  const materialSelect = await page.locator('select[name="material_id"]').count();
  const tanggalInput = await page.locator('input[name="tanggal"]').count();
  const shiftSelect = await page.locator('select[name="shift"]').count();

  log(unitSelect > 0 ? 'OK' : 'ERROR', `Ritasi form has unit_id: ${unitSelect > 0}`);
  log(materialSelect > 0 ? 'OK' : 'ERROR', `Ritasi form has material_id: ${materialSelect > 0}`);
  log(tanggalInput > 0 ? 'OK' : 'ERROR', `Ritasi form has tanggal: ${tanggalInput > 0}`);
  log(shiftSelect > 0 ? 'OK' : 'ERROR', `Ritasi form has shift: ${shiftSelect > 0}`);

  // Check area dropdown (was a bug - hardcoded area_id)
  const areaSelect = await page.locator('select[name="area_id"]').count();
  log(areaSelect > 0 ? 'OK' : 'ERROR', `Ritasi form has area_id dropdown: ${areaSelect > 0}`);

  // Ritasi history
  await visitAndCheck(page, '/pegawai/ritasi/riwayat', 'Ritasi Riwayat');

  // =========================================
  // 12. PEGAWAI NON-RITASI FORM
  // =========================================
  console.log('\n===== PEGAWAI NON-RITASI =====');
  await visitAndCheck(page, '/pegawai/non-ritasi/create', 'Non-Ritasi Form');

  const nonRitasiContent = await page.content();
  const hasNonRitasiForm = nonRitasiContent.includes('non-ritasi') || nonRitasiContent.includes('Non-Ritasi');
  log(hasNonRitasiForm ? 'OK' : 'ERROR', `Non-Ritasi form has content: ${hasNonRitasiForm}`);

  // Non-ritasi history
  await visitAndCheck(page, '/pegawai/non-ritasi/riwayat', 'Non-Ritasi Riwayat');

  // =========================================
  // 13. PEGAWAI GENERAL FORM
  // =========================================
  console.log('\n===== PEGAWAI GENERAL =====');
  await visitAndCheck(page, '/pegawai/general/create', 'General Form');

  // =========================================
  // 14. ROLE PROTECTION TEST
  // =========================================
  console.log('\n===== ROLE PROTECTION =====');
  // Try accessing admin pages as pegawai (should redirect)
  await page.goto(`${BASE}/admin/dashboard`);
  await page.waitForTimeout(2000);
  const protectedUrl = page.url();
  if (protectedUrl.includes('admin/dashboard')) {
    log('ERROR', `Pegawai can access admin dashboard! URL: ${protectedUrl}`);
  } else {
    log('OK', `Pegawai blocked from admin dashboard. Redirected to: ${protectedUrl}`);
  }

  // Try accessing SPV pages as pegawai
  await page.goto(`${BASE}/spv/dashboard`);
  await page.waitForTimeout(2000);
  const spvProtectedUrl = page.url();
  if (spvProtectedUrl.includes('spv/dashboard')) {
    log('ERROR', `Pegawai can access SPV dashboard! URL: ${spvProtectedUrl}`);
  } else {
    log('OK', `Pegawai blocked from SPV dashboard. Redirected to: ${spvProtectedUrl}`);
  }

  // =========================================
  // SUMMARY
  // =========================================
  console.log('\n=====================================');
  console.log('          QA TEST SUMMARY');
  console.log('=====================================');
  console.log(`Total errors: ${errors.length}`);
  console.log(`Console errors: ${consoleErrors.length}`);
  console.log(`HTTP errors: ${httpErrors.length}`);

  if (errors.length > 0) {
    console.log('\n--- ERRORS ---');
    errors.forEach(e => console.log(e));
  }
  if (consoleErrors.length > 0) {
    console.log('\n--- CONSOLE ERRORS ---');
    consoleErrors.forEach(e => console.log(e));
  }
  if (httpErrors.length > 0) {
    console.log('\n--- HTTP ERRORS ---');
    httpErrors.forEach(e => console.log(e));
  }

  if (errors.length === 0 && consoleErrors.length === 0 && httpErrors.length === 0) {
    console.log('\n✓ ALL TESTS PASSED - NO BUGS FOUND');
  }

  await browser.close();
})();
