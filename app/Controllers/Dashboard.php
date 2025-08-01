<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PasienModel;
use App\Models\AntrianModel;
use App\Models\PemeriksaanSoapModel;
use App\Models\ObatModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();
        
        // Check if logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        $userRole = $session->get('role');
        $stats = $this->getStats();
        
        $data = [
            'title' => 'Dashboard - SIMRS',
            'pageTitle' => $this->getPageTitle($userRole),
            'userName' => $session->get('nama_lengkap') ?? 'User',
            'userRole' => ucfirst($userRole ?? 'Role'),
            'currentRole' => $userRole,
            'stats' => $stats
        ];
        
        return view('dashboard', $data);
    }
    
    private function getStats()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        $thisYear = date('Y');
        
        // Total Pasien
        $totalPasien = $db->table('pasien')->countAll();
        
        // Pasien Hari Ini (berdasarkan registrasi hari ini)
        $pasienHariIni = $db->table('antrian')
            ->where('DATE(created_at)', $today)
            ->countAllResults();
        
        // Total Dokter
        $totalDokter = $db->table('users')
            ->where('role', 'dokter')
            ->where('status_aktif', 1)
            ->countAllResults();
        
        // Antrian Hari Ini
        $antrianHariIni = $db->table('antrian')
            ->where('DATE(created_at)', $today)
            ->countAllResults();
        
        // Pemeriksaan Bulan Ini
        $pemeriksaanBulanIni = $db->table('pemeriksaan_soap')
            ->where('DATE_FORMAT(created_at, "%Y-%m")', $thisMonth)
            ->countAllResults();
        
        // Obat Tersedia
        $obatTersedia = $db->table('obat')
            ->where('stok >', 0)
            ->countAllResults();
        
        // Pasien Aktif (yang pernah berobat dalam 30 hari terakhir)
        $pasienAktif = $db->table('pemeriksaan_soap')
            ->select('COUNT(DISTINCT no_rm) as total')
            ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
            ->get()
            ->getRow()->total ?? 0;
        
        // Total User Staff
        $totalStaff = $db->table('users')
            ->where('status_aktif', 1) 
            ->countAllResults();
        
        return [
            'total_pasien' => $totalPasien,
            'pasien_hari_ini' => $pasienHariIni,
            'total_dokter' => $totalDokter,
            'antrian_hari_ini' => $antrianHariIni,
            'pemeriksaan_bulan_ini' => $pemeriksaanBulanIni,
            'obat_tersedia' => $obatTersedia,
            'pasien_aktif' => $pasienAktif,
            'total_staff' => $totalStaff
        ];
    }
    
    private function getPageTitle($role)
    {
        $titles = [
            'admin' => 'Dashboard Admin',
            'dokter' => 'Dashboard Dokter',
            'perawat' => 'Dashboard Perawat',
            'farmasi' => 'Dashboard Farmasi',
            'kasir' => 'Dashboard Kasir',
            'admisi' => 'Dashboard Admisi',
            'manajemen' => 'Dashboard Manajemen'
        ];
        
        return $titles[$role] ?? 'Dashboard';
    }
    
}
