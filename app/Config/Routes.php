<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Auth::login');

// Authentication Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('profile', 'Auth::profile');
$routes->post('profile/update', 'Auth::updateProfile');

// Dashboard
$routes->get('dashboard', 'Dashboard::index');

// Admisi Routes
$routes->group('admisi', function($routes) {
    // Registrasi Pasien
    $routes->get('registrasi-pasien', 'Admisi::registrasiPasien');
    $routes->post('registrasi-pasien/save', 'Admisi::save');
    $routes->get('registrasi-pasien/step2', 'Admisi::registrasiPasienStep2');
    $routes->post('registrasi-pasien/save-step2', 'Admisi::saveStep2');
    $routes->get('registrasi-pasien/step3', 'Admisi::registrasiPasienStep3');
    $routes->post('registrasi-pasien/save-step3', 'Admisi::saveStep3');
    $routes->get('registrasi-pasien/step4', 'Admisi::registrasiPasienStep4');
    $routes->post('registrasi-pasien/save-step4', 'Admisi::saveStep4');
    $routes->get('registrasi-pasien/step5', 'Admisi::registrasiPasienStep5');
    $routes->post('registrasi-pasien/save-step5', 'Admisi::saveStep5');
    $routes->get('registrasi-sukses', 'Admisi::registrasiSukses');
});

// Shared Routes 
$routes->get('datapasien', 'Shared::datapasien');
$routes->get('shared/get-detail-pasien/(:num)', 'Shared::getDetailPasien/$1');
$routes->get('shared/edit-pasien/(:num)', 'Shared::editPasien/$1');
$routes->post('shared/update-pasien/(:num)', 'Shared::updatePasien/$1');
$routes->delete('shared/delete-pasien/(:num)', 'Shared::deletePasien/$1');


// Role-based Routes

// Admisi Routes
$routes->group('admisi', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admisi::index');
    $routes->get('dashboard', 'Admisi::index');
    
    // Registrasi Pasien
    $routes->get('registrasi-pasien', 'Admisi::registrasipasien');
    $routes->post('registrasi-pasien/save', 'Admisi::saveRegistrasiPasien');
    $routes->get('registrasi-pasien/step2', 'Admisi::registrasipasienStep2');
    $routes->get('cari-kodepos', 'Admisi::cariKodepos');
    $routes->get('registrasi-pasien/step3', 'Admisi::registrasipasienStep3');
    $routes->get('registrasi-pasien/step4', 'Admisi::registrasipasienStep4');
    $routes->get('registrasi-pasien/step5', 'Admisi::registrasipasienStep5');
    
    // Proses Penyimpanan Data
    $routes->post('registrasi-pasien/save-step1', 'Admisi::saveStep1');
    $routes->post('registrasi-pasien/save-step2', 'Admisi::saveStep2');
    $routes->post('registrasi-pasien/save-step3', 'Admisi::saveStep3');
    $routes->post('registrasi-pasien/save-step4', 'Admisi::saveStep4');
    $routes->post('registrasi-pasien/save-step5', 'Admisi::saveStep5');
    
    // Test route
    $routes->get('test-save', 'Admisi::testSaveData');
    
    // Halaman Sukses
    $routes->get('registrasi-sukses/(:segment)/(:segment)', 'Admisi::registrasiSukses/$1/$2');
    
    // Data Pasien
    $routes->get('datapasien', 'Admisi::datapasien');
    $routes->get('pasien-hari-ini', 'Admisi::pasienHariIni');
    $routes->get('get-detail-pasien/(:num)', 'Admisi::getDetailPasien/$1');
    $routes->get('edit-pasien/(:num)', 'Admisi::editPasien/$1');
    $routes->post('update-pasien/(:num)', 'Admisi::updatePasien/$1');
    $routes->delete('delete-pasien/(:num)', 'Admisi::deletePasien/$1');
});

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::index');
    //users
    $routes->get('users', 'Admin::users');
    $routes->get('users/add', 'Admin::addUser');
    $routes->post('users/save', 'Admin::saveUser');
    $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
    $routes->get('users/delete/(:num)', 'Admin::deleteUser/$1');
    $routes->post('users/toggle-status/(:num)', 'Admin::toggleUserStatus/$1');
    $routes->get('database', 'Admin::database');
    $routes->get('datapasien/delete/(:num)', 'Admisi::deletePasien/$1');
    //dokter
    $routes->get('datadokter', 'Admin::datadokter');
    $routes->get('tambahjadwaldokter', 'Admin::tambahJadwalDokter');
    $routes->post('tambahjadwaldokter/save', 'Admin::saveJadwalDokter');
    $routes->get('editjadwaldokter', 'Admin::editJadwalDokter');
    $routes->post('updatejadwaldokter', 'Admin::updateJadwalDokter');
    $routes->get('hapusjadwaldokter', 'Admin::hapusJadwalDokter');
});


// Perawat Routes
$routes->group('perawat', function($routes) {
    $routes->get('triase/(:num)', 'Perawat::triase/$1');
    $routes->post('simpanTriase', 'Perawat::simpanTriase');
    $routes->get('/', 'Perawat::index');

    // Antrian Pendaftaran Routes
    $routes->get('antrian-pendaftaran', 'Perawat::antrianPendaftaran');
    $routes->get('getAntrianPendaftaran', 'Perawat::getAntrianPendaftaran');
    $routes->get('get-antrian-list', 'Perawat::getAntrianList');
    $routes->get('get-jumlah-antrian', 'Perawat::getJumlahAntrian');
    $routes->get('get-pendaftaran/(:num)', 'Perawat::getPendaftaran/$1');
    $routes->post('simpan-pemeriksaan', 'Perawat::simpanPemeriksaan');
    $routes->get('detail-pendaftaran/(:num)', 'Perawat::detailPendaftaran/$1');

    // Catatan Pemeriksaan
    $routes->get('catatan-pemeriksaan', 'Perawat::catatanPemeriksaan');
    $routes->get('detail-pemeriksaan/(:num)', 'Perawat::detailPemeriksaan/$1');
    $routes->get('tandavitalpasien/(:num)', 'Perawat::tandavitalpasien/$1');
    $routes->get('antrian-poli-sukses', 'Perawat::antrianPoliSukses');
});

// Farmasi Routes
$routes->group('farmasi', function($routes) {
    $routes->get('/', 'Farmasi::index');
    $routes->get('dashboard', 'Farmasi::index');
    $routes->get('prescriptions', 'Farmasi::prescriptions');
    $routes->get('medicine-stock', 'Farmasi::medicineStock');
    $routes->get('reports', 'Farmasi::reports');
});

// Kasir Routes
$routes->group('kasir', function($routes) {
    $routes->get('/', 'Kasir::index');
    $routes->get('dashboard', 'Kasir::index');
    $routes->get('payments', 'Kasir::payments');
    $routes->get('billing', 'Kasir::billing');
    $routes->get('reports', 'Kasir::reports');
});

// Dokter Routes
$routes->group('dokter', function($routes) {
    $routes->get('/', 'Dokter::index');
    $routes->get('antrian-poli', 'Dokter::antrianPoli');
    $routes->get('pemeriksaan/(:num)', 'Dokter::pemeriksaanDokter/$1');
    $routes->get('hasil-pemeriksaan-dokter', 'Dokter::hasilPemeriksaanDokter');
});

// Manajemen Routes
$routes->group('manajemen', function($routes) {
    $routes->get('/', 'Manajemen::index');
    $routes->get('dashboard', 'Manajemen::index');
    $routes->get('reports', 'Manajemen::reports');
    $routes->get('statistics', 'Manajemen::statistics');
});

// ExportWord routes
$routes->get('exportword/pasien/(:segment)', 'ExportWord::pasien/$1');
$routes->get('exportword/antrian/(:segment)', 'ExportWord::antrian/$1');
$routes->get('exportword/pemeriksaan/(:num)', 'ExportWord::pemeriksaan/$1');
$routes->get('exportword/antrian-poli/(:segment)', 'ExportWord::antrianPoli/$1');
