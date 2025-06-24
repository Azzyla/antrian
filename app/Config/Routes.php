<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'PengunjungController::index');
$routes->get('pengunjung', 'PengunjungController::index');
$routes->post('pengunjung/submit', 'PengunjungController::submit');
$routes->get('layanan', 'LayananController::index');
$routes->post('layanan/simpan', 'LayananController::simpan');
$routes->get('rekap', 'RekapController::index');
$routes->post('rekap/loadData', 'RekapController::loadData');

$routes->get('antrian', 'AntrianController::index');
$routes->get('antrian/ambil/(:segment)', 'AntrianController::ambil/$1');
$routes->get('/antrian/panggil', 'AntrianController::panggilBerikutnya');
$routes->get('/kepuasan', 'KepuasanController::index');
$routes->post('/kepuasan/simpan', 'KepuasanController::simpan');
$routes->get('/panggilan', 'PanggilanController::index');
$routes->get('/panggilan/panggil/(:num)', 'PanggilanController::panggil/$1');
$routes->get('/panggilan/mulaiLayanan/(:num)', 'PanggilanController::mulaiLayanan/$1');
$routes->get('/panggilan/selesaiLayanan/(:num)', 'PanggilanController::selesaiLayanan/$1');
$routes->get('/panggilan/ulang/(:num)', 'PanggilanController::ulang/$1');


$routes->get('register', 'AuthController::register');
$routes->post('save', 'AuthController::save');
$routes->get('login', 'AuthController::login');
$routes->post('authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

//tambahkan untuk kepala 
$routes->get('kepala/login', 'KepalaController::login');
$routes->post('kepala/login', 'KepalaController::loginPost');
$routes->get('kepala/logout', 'KepalaController::logout');
$routes->get('kepala/dashboard', 'KepalaController::dashboard');
$routes->get('kepala/rekap_antrian', 'KepalaController::rekap_antrian');
$routes->get('kepala/rekap_kepuasan', 'KepalaController::rekap_kepuasan');





// Tambahan menu dashboard:
$routes->get('/kepala/rekap-antrian', 'KepalaController::rekapAntrian');
$routes->get('/kepala/rekap-kepuasan', 'KepalaController::rekapKepuasan');


// Gunakan filter auth pada dashboard

$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('panggilan', 'PanggilanController::index', ['filter' => 'auth']);
$routes->get('referensi', 'ReferensiController::index', ['filter' => 'auth']);
$routes->get('referensi/audio', 'ReferensiController::show', ['filter' => 'auth']);
$routes->get('layar', 'LayarController::index', ['filter' => 'auth']);
$routes->get('/rekap-kepuasan', 'Kepuasan::rekap', ['filter' => 'auth']);


