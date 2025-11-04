<?php

namespace App\Controllers;

use App\Models\TagihanModel;

class Kasir extends BaseController {
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        // Cek login dan role kasir/admin
        $userRole = $this->session->get('role');
        if (!$this->session->get('isLoggedIn') || ($userRole !== 'kasir' && $userRole !== 'admin')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
    }

    /**
     * Halaman utama kasir
     */
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Ambil semua pasien dengan status "Menunggu Kasir" hari ini
        $today = date('Y-m-d');
        $antrianMenunggu = $db->table('antrian a')
            ->select('a.no_rm, a.created_at, p.nama_lengkap')
            ->join('pasien p', 'p.no_rekam_medis = a.no_rm', 'left')
            ->where('a.status', 'Menunggu Kasir')
            ->where('DATE(a.created_at)', $today)
            ->get()
            ->getResultArray();
        
        $tagihan_list = [];
        
        foreach ($antrianMenunggu as $antrian) {
            $no_rm = $antrian['no_rm'];
            $tanggal = date('Y-m-d', strtotime($antrian['created_at']));
            
            // Cek apakah ada tagihan obat dari farmasi
            $tagihanObat = $db->table('tagihan')
                ->where('no_rm', $no_rm)
                ->where('DATE(tanggal_tagihan)', $tanggal)
                ->where('status', 'pending')
                ->get()
                ->getRowArray();
            
            if ($tagihanObat) {
                // Pasien dengan resep obat - ada tagihan dari farmasi
                $tagihan_list[] = [
                    'no_rm' => $no_rm,
                    'id_pasien' => $no_rm,
                    'nama_pasien' => $antrian['nama_lengkap'],
                    'tanggal' => $tanggal,
                    'tanggal_tagihan' => $antrian['created_at'],
                    'total_biaya' => $tagihanObat['total_biaya'],
                    'total_tagihan' => $tagihanObat['total_biaya'],
                    'status' => 'pending',
                    'jenis' => 'dengan_resep'
                ];
            } else {
                // Pasien tanpa resep obat - hanya biaya konsultasi & admin
                // Default: Biaya konsultasi + admin
                $biaya_konsultasi = 50000; // Sesuaikan dengan tarif RS
                $biaya_admin = 10000;
                $total = $biaya_konsultasi + $biaya_admin;
                
                $tagihan_list[] = [
                    'no_rm' => $no_rm,
                    'id_pasien' => $no_rm,
                    'nama_pasien' => $antrian['nama_lengkap'],
                    'tanggal' => $tanggal,
                    'tanggal_tagihan' => $antrian['created_at'],
                    'total_biaya' => $total,
                    'total_tagihan' => $total,
                    'status' => 'pending',
                    'jenis' => 'tanpa_resep'
                ];
            }
        }

        $data = [
            'title' => 'Kasir - SIMRS',
            'pageTitle' => 'Kasir & Pembayaran Pasien',
            'tagihan_list' => $tagihan_list
        ];
        return view('kasir/kasir', $data);
    }

    
    public function detailTagihan($idPasien, $tanggal)
    {
        log_message('info', "detailTagihan called with idPasien: $idPasien, tanggal: $tanggal");
        
        try {
            $tagihanModel = new TagihanModel();
            $db = \Config\Database::connect();
            
            log_message('info', "Models initialized successfully");
            
            // Cari tagihan berdasarkan no_rm dan tanggal (bisa pending atau lunas)
            $tagihan = $tagihanModel->builder()
                ->select('tagihan.*, pasien.nama_lengkap as nama_pasien, pasien.tanggal_lahir')
                ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(tagihan.no_rm AS CHAR)', 'left')
                ->where('tagihan.no_rm', $idPasien)
                ->where('DATE(tagihan.tanggal_tagihan)', $tanggal)
                ->get()
                ->getRowArray();
            
            log_message('info', "Tagihan query executed. Result: " . json_encode($tagihan));
            
            // Flag untuk menandai pasien tanpa resep
            $tanpaResep = false;
            
            if (!$tagihan) {
                // Cek apakah pasien ada di antrian dengan status "Menunggu Kasir" (tanpa resep)
                $antrian = $db->table('antrian a')
                    ->select('a.*, p.nama_lengkap, p.tanggal_lahir, p.jenis_kelamin')
                    ->join('pasien p', 'p.no_rekam_medis = a.no_rm', 'left')
                    ->where('a.no_rm', $idPasien)
                    ->where('DATE(a.created_at)', $tanggal)
                    ->where('a.status', 'Menunggu Kasir')
                    ->get()
                    ->getRowArray();
                
                if (!$antrian) {
                    log_message('error', "Tagihan atau antrian not found for idPasien: $idPasien, tanggal: $tanggal");
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
                
                // Buat tagihan sementara untuk pasien tanpa resep
                $tanpaResep = true;
                $biaya_konsultasi = 50000;
                $biaya_admin = 10000;
                $total = $biaya_konsultasi + $biaya_admin;
                
                $tagihan = [
                    'id_tagihan' => null,
                    'no_rm' => $idPasien,
                    'nama_pasien' => $antrian['nama_lengkap'],
                    'tanggal_lahir' => $antrian['tanggal_lahir'],
                    'tanggal_tagihan' => $antrian['created_at'],
                    'total_biaya' => $total,
                    'status' => 'pending',
                    'detail_tagihan' => null
                ];
                
                log_message('info', "Pasien tanpa resep, created temporary tagihan: " . json_encode($tagihan));
            }
            
            log_message('info', "Tagihan found, parsing detail_tagihan");
            
            // Parse detail tagihan dari JSON
            $detail_tagihan = json_decode($tagihan['detail_tagihan'], true) ?? [];
            log_message('info', "Detail tagihan parsed: " . json_encode($detail_tagihan));
            
            // Pisahkan obat dan biaya standar
            $resep_list = [];
            $total_obat = 0;
            
            foreach ($detail_tagihan as $item) {
                if (isset($item['nama_obat'])) {
                    // Ini adalah item obat
                    // Handle different key names for price
                    $harga = $item['harga_satuan'] ?? $item['harga_jual'] ?? 0;
                    
                    $resep_list[] = [
                        'nama_obat' => $item['nama_obat'],
                        'jumlah' => $item['jumlah'],
                        'satuan' => $item['satuan'],
                        'harga_jual' => $harga,
                        'subtotal' => $item['subtotal']
                    ];
                    $total_obat += $item['subtotal'];
                    
                    log_message('info', "Processed obat item: " . $item['nama_obat'] . " with price: " . $harga);
                }
            }
            
            log_message('info', "Resep list processed. Total obat: $total_obat");
            
            // Data pasien dan dokter - ambil dari resep terkait atau langsung dari pasien
            $resepModel = new \App\Models\ResepModel();
            $pasienModel = new \App\Models\PasienModel();
            
            log_message('info', "Additional models initialized");
            
            // Ambil data pasien berdasarkan no_rm
            $pasienInfo = $pasienModel->builder()
                ->select('pasien.*, users.nama_lengkap as nama_dokter')
                ->join('resep', 'resep.id_pasien = pasien.id', 'left')
                ->join('users', 'users.id = resep.id_dokter', 'left')
                ->where('pasien.no_rekam_medis', $tagihan['no_rm'])
                ->orderBy('resep.tanggal_resep', 'DESC')
                ->get()
                ->getRowArray();
            
            log_message('info', "Pasien info query executed: " . json_encode($pasienInfo));
            
            // Hitung umur jika ada tanggal lahir
            $umur = '-';
            if (!empty($pasienInfo['tanggal_lahir'])) {
                $lahir = new \DateTime($pasienInfo['tanggal_lahir']);
                $sekarang = new \DateTime();
                $umur = $sekarang->diff($lahir)->y . ' tahun';
            }
            
            log_message('info', "Age calculated: $umur");
            
            $data = [
                'resep_list' => $resep_list,
                'pasien' => [
                    'no_rm' => $tagihan['no_rm'],
                    'nama_pasien' => $tagihan['nama_pasien'],
                    'tanggal_resep' => $tagihan['tanggal_tagihan'],
                    'nama_dokter' => $pasienInfo['nama_dokter'] ?? '-',
                    'jenis_kelamin' => $pasienInfo['jenis_kelamin'] ?? '-',
                    'umur' => $umur
                ],
                'total_obat' => $total_obat,
                'total_tagihan' => $tagihan['total_biaya'],
                'tanpa_resep' => $tanpaResep, // Flag untuk view
                'biaya_layanan' => [
                    'administrasi' => $tanpaResep ? 10000 : 35000,
                    'konsultasi' => $tanpaResep ? 50000 : 100000
                ]
            ];
            
            log_message('info', "Data array prepared successfully");
            
            // Jika request AJAX (untuk modal), tentukan jenis modal
            if ($this->request->isAJAX()) {
                // Check if this is a request for simple detail view (from riwayat pembayaran)
                $viewType = $this->request->getGet('view') ?? 'detail';
                
                if ($viewType === 'simple') {
                    log_message('info', "Processing simple view type");
                    // Simple detail view for riwayat pembayaran
                    $userModel = new \App\Models\UserModel();
                    $kasirInfo = $userModel->find($tagihan['kasir_id'] ?? 1);
                    
                    // Format tanggal lahir
                    $tanggalLahirFormatted = '-';
                    if (!empty($tagihan['tanggal_lahir'])) {
                        $tanggalLahirFormatted = date('d/m/Y', strtotime($tagihan['tanggal_lahir']));
                    }
                    
                    // Format tanggal pembayaran
                    $tanggalBayarFormatted = '-';
                    if (!empty($tagihan['tanggal_bayar'])) {
                        $tanggalBayarFormatted = date('d/m/Y H:i', strtotime($tagihan['tanggal_bayar']));
                    } else if (!empty($tagihan['tanggal_tagihan'])) {
                        $tanggalBayarFormatted = date('d/m/Y H:i', strtotime($tagihan['tanggal_tagihan']));
                    }
                    
                    // Ambil data pembayaran dari database
                    $jumlahBayar = $tagihan['jumlah_bayar'] ?? 0;
                    $totalBiaya = $tagihan['total_biaya'] ?? 0;
                    $kembalian = $tagihan['kembalian'] ?? 0;
                    
                    // Jika jumlah_bayar masih 0 (data lama sebelum field ini ada), hitung ulang
                    if ($jumlahBayar == 0 && $totalBiaya > 0 && $tagihan['status'] == 'paid') {
                        // Data lama yang sudah bayar tapi belum ada field jumlah_bayar
                        // Asumsikan pembayaran pas
                        $jumlahBayar = $totalBiaya;
                        $kembalian = 0;
                    } else if ($jumlahBayar > 0) {
                        // Data baru, gunakan data asli dari database
                        // Kembalian sudah dihitung saat proses pembayaran
                    }
                    
                    $simpleData = [
                        'success' => true,
                        'data' => [
                            'id' => $tagihan['id_tagihan'] ?? $tagihan['id'],
                            'no_tagihan' => $tagihan['no_tagihan'] ?? 'TG-' . str_pad($tagihan['id_tagihan'] ?? $tagihan['id'], 8, '0', STR_PAD_LEFT),
                            'no_rm' => $tagihan['no_rm'] ?? '-',
                            'nama_pasien' => $tagihan['nama_pasien'] ?? '-',
                            'tanggal_lahir' => $tanggalLahirFormatted,
                            'jenis_layanan' => $tagihan['jenis_layanan'] ?? 'Rawat Jalan',
                            'total_biaya' => $totalBiaya,
                            'jumlah_bayar' => $jumlahBayar,
                            'kembalian' => $kembalian,
                            'metode_pembayaran' => $tagihan['metode_pembayaran'] ?? 'Cash',
                            'tanggal_pembayaran' => $tanggalBayarFormatted,
                            'kasir' => $kasirInfo['nama_lengkap'] ?? 'System',
                            'detail_biaya' => '<tr><td>Biaya Administrasi</td><td class="text-end">Rp 35,000</td></tr>
                                             <tr><td>Biaya Obat</td><td class="text-end">Rp ' . number_format($total_obat, 0, ',', '.') . '</td></tr>
                                             <tr><td>Biaya Konsultasi Dokter</td><td class="text-end">Rp 100,000</td></tr>'
                        ]
                    ];
                    
                    log_message('info', "Simple data prepared, returning JSON");
                    return $this->response->setJSON($simpleData);
                }
                
                // Default detailed view for kasir page
                log_message('info', "Processing detailed view, returning modal view");
                return view('kasir/detail_tagihan_modal', $data);
            }
            
            // Jika bukan AJAX (halaman penuh), gunakan view dengan layout
            log_message('info', "Non-AJAX request, returning full page view");
            return view('kasir/detail_tagihan', $data);
            
        } catch (\Exception $e) {
            log_message('error', "Exception in detailTagihan: " . $e->getMessage());
            log_message('error', "Stack trace: " . $e->getTraceAsString());
            return $this->response->setJSON(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    

    /**
     * Proses pembayaran (AJAX)
     */
    public function prosesPembayaran()
    {
        $request = \Config\Services::request();
        try {
            // Ambil data dari request POST
            $idPasien = $request->getPost('idPasien');
            $tanggal = $request->getPost('tanggal');
            $jumlahBayar = $request->getPost('jumlahBayar');
            $metodeBayar = $request->getPost('metodeBayar');
            $totalTagihan = $request->getPost('totalTagihan');
            
            // Debug logging
            log_message('info', "Proses Pembayaran Data: idPasien=$idPasien, tanggal=$tanggal, jumlahBayar=$jumlahBayar, metodeBayar=$metodeBayar, totalTagihan=$totalTagihan");
            
            // Validasi input
            if (!$idPasien || !$tanggal || !$jumlahBayar || !$metodeBayar) {
                throw new \Exception('Data pembayaran tidak lengkap');
            }
            
            $tagihanModel = new TagihanModel();
            $db = \Config\Database::connect();
            
            // Cek apakah ada tagihan dari farmasi
            $tagihan = $tagihanModel->builder()
                ->where('no_rm', $idPasien)
                ->where('DATE(tanggal_tagihan)', $tanggal)
                ->where('status', 'pending')
                ->get()
                ->getRowArray();
            
            $totalBiaya = 0;
            $isNewTagihan = false;
            
            if (!$tagihan) {
                // Pasien tanpa resep - buat tagihan baru untuk biaya konsultasi & admin
                $isNewTagihan = true;
                $biaya_konsultasi = 50000;
                $biaya_admin = 10000;
                $totalBiaya = $biaya_konsultasi + $biaya_admin;
                
                // Buat record tagihan baru
                $newTagihanData = [
                    'no_rm' => $idPasien,
                    'total_biaya' => $totalBiaya,
                    'status' => 'pending',
                    'tanggal_tagihan' => date('Y-m-d H:i:s'),
                    'keterangan' => 'Biaya Konsultasi Dokter + Administrasi',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $tagihanModel->insert($newTagihanData);
                $tagihan = [
                    'id_tagihan' => $tagihanModel->insertID(),
                    'no_rm' => $idPasien,
                    'total_biaya' => $totalBiaya,
                    'status' => 'pending'
                ];
                
                log_message('info', "Created new tagihan for pasien tanpa resep: " . json_encode($tagihan));
            } else {
                $totalBiaya = $tagihan['total_biaya'];
            }
            
            // Validasi jumlah bayar
            if (floatval($jumlahBayar) < floatval($totalBiaya)) {
                throw new \Exception('Jumlah bayar kurang dari total tagihan');
            }
            
            // Update status tagihan menjadi paid
            $kembalian = floatval($jumlahBayar) - floatval($totalBiaya);
            
            $updateData = [
                'status' => 'paid',
                'tanggal_bayar' => date('Y-m-d H:i:s'),
                'metode_pembayaran' => $metodeBayar,
                'jumlah_bayar' => floatval($jumlahBayar),
                'kembalian' => $kembalian,
                'kasir_id' => $this->session->get('user_id') ?? 1,
                'keterangan' => $isNewTagihan ? 'Pembayaran Konsultasi & Admin - ' . ucfirst($metodeBayar) : 'Pembayaran berhasil - ' . ucfirst($metodeBayar)
            ];
            
            log_message('info', "Update Data: " . json_encode($updateData));
            log_message('info', "Tagihan ID to update: " . $tagihan['id_tagihan']);
            log_message('info', "Total Biaya: " . $totalBiaya);
            log_message('info', "Jumlah Bayar (float): " . floatval($jumlahBayar));
            log_message('info', "Kembalian (calculated): " . $kembalian);
            
            $updateResult = $tagihanModel->update($tagihan['id_tagihan'], $updateData);
            
            log_message('info', "Update Result: " . ($updateResult ? 'SUCCESS' : 'FAILED'));
            
            if ($updateResult) {
                // Update status antrian pasien menjadi 'Selesai' (menggunakan id_antrian_perawat)
                $antrianPoli = $db->table('antrian_poli')
                    ->where('no_rm', $idPasien)
                    ->where('DATE(created_at)', $tanggal)
                    ->orderBy('id', 'DESC')
                    ->get(1)
                    ->getFirstRow('array');
                
                if ($antrianPoli && isset($antrianPoli['id_antrian_perawat'])) {
                    $db->table('antrian')
                        ->where('id', $antrianPoli['id_antrian_perawat'])
                        ->update(['status' => 'Selesai']);
                    
                    log_message('info', "Updated antrian id=" . $antrianPoli['id_antrian_perawat'] . " status to Selesai for no_rm: " . $idPasien);
                } else {
                    log_message('warning', "No antrian_poli or id_antrian_perawat found for no_rm: " . $idPasien);
                }
                
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diproses',
                    'data' => [
                        'id_tagihan' => $tagihan['id_tagihan'],
                        'total_tagihan' => $totalBiaya,
                        'jumlah_bayar' => $jumlahBayar,
                        'kembalian' => $kembalian,
                        'metode_bayar' => $metodeBayar
                    ]
                ]);
            } else {
                throw new \Exception('Gagal memperbarui status pembayaran');
            }
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tampilkan riwayat pembayaran pasien
     */
    public function riwayatPembayaran()
    {
        $tagihanModel = new TagihanModel();
        $db = \Config\Database::connect();
        
        // Ambil data riwayat pembayaran dari database
        $riwayat_pembayaran = $tagihanModel->builder()
            ->select('tagihan.*, pasien.nama_lengkap as nama_pasien, users.nama_lengkap as nama_kasir')
            ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(tagihan.no_rm AS CHAR)', 'left')
            ->join('users', 'users.id = tagihan.kasir_id', 'left')
            ->where('tagihan.status', 'paid')
            ->orderBy('tagihan.tanggal_bayar', 'DESC')
            ->limit(100) // Limit untuk performa
            ->get()
            ->getResultArray();
        
        // Format data untuk view
        foreach ($riwayat_pembayaran as &$riwayat) {
            $riwayat['waktu_bayar'] = $riwayat['tanggal_bayar'];
            $riwayat['total_tagihan'] = $riwayat['total_biaya'];
            // Gunakan data asli dari database, jangan override dengan fallback
            $riwayat['nama_kasir'] = $riwayat['nama_kasir'] ?? 'System';
        }
        
        
        $data = [
            'title' => 'Riwayat Pembayaran - SIMRS',
            'pageTitle' => 'Riwayat Pembayaran Kasir',
            'riwayat_pembayaran' => $riwayat_pembayaran
        ];
        return view('kasir/riwayat_pembayaran', $data);
    }

    /**
     * Export riwayat pembayaran ke Excel
     */
    public function exportExcel()
    {
        $tagihanModel = new TagihanModel();
        
        // Ambil data riwayat pembayaran
        $riwayat_pembayaran = $tagihanModel->builder()
            ->select('tagihan.*, pasien.nama_lengkap as nama_pasien, users.nama_lengkap as nama_kasir')
            ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(tagihan.no_rm AS CHAR)', 'left')
            ->join('users', 'users.id = tagihan.kasir_id', 'left')
            ->where('tagihan.status', 'paid')
            ->orderBy('tagihan.tanggal_bayar', 'DESC')
            ->get()
            ->getResultArray();
        
        // Set header untuk download Excel
        $filename = 'riwayat_pembayaran_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header CSV
        fputcsv($output, [
            'Waktu Bayar',
            'No. RM',
            'Nama Pasien',
            'Total Tagihan',
            'Metode Pembayaran',
            'Kasir',
            'Keterangan'
        ]);
        
        // Data CSV
        foreach ($riwayat_pembayaran as $row) {
            fputcsv($output, [
                $row['tanggal_bayar'],
                $row['no_rm'],
                $row['nama_pasien'] ?? '-',
                $row['total_biaya'],
                $row['metode_pembayaran'] ?? 'Cash',
                $row['nama_kasir'] ?? 'System',
                $row['keterangan'] ?? '-'
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Export riwayat pembayaran ke PDF
     */
    public function exportPdf()
    {
        // Untuk implementasi PDF, bisa menggunakan library seperti TCPDF atau mPDF
        // Sementara redirect dengan pesan
        return redirect()->to(base_url('kasir/riwayat-pembayaran'))
                        ->with('message', 'Export PDF akan segera tersedia');
    }

    /**
     * Print Struk/Invoice Tagihan
     */
    public function printTagihan($idPasien, $tanggal)
    {
        $tagihanModel = new TagihanModel();
        
        try {
            // Cari tagihan berdasarkan no_rm dan tanggal
            $tagihan = $tagihanModel->builder()
                ->select('tagihan.*, pasien.nama_lengkap as nama_pasien, pasien.tanggal_lahir')
                ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(tagihan.no_rm AS CHAR)', 'left')
                ->where('tagihan.no_rm', $idPasien)
                ->where('DATE(tagihan.tanggal_tagihan)', $tanggal)
                ->get()
                ->getRowArray();
            
            if (!$tagihan) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Tagihan tidak ditemukan');
            }
            
            // Parse detail tagihan dari JSON
            $detail_tagihan = json_decode($tagihan['detail_tagihan'], true) ?? [];
            
            // Pisahkan obat dan biaya standar
            $resep_list = [];
            $total_obat = 0;
            
            foreach ($detail_tagihan as $item) {
                if (isset($item['nama_obat'])) {
                    $harga = $item['harga_satuan'] ?? $item['harga_jual'] ?? 0;
                    
                    $resep_list[] = [
                        'nama_obat' => $item['nama_obat'],
                        'jumlah' => $item['jumlah'],
                        'satuan' => $item['satuan'],
                        'harga_jual' => $harga,
                        'subtotal' => $item['subtotal']
                    ];
                    $total_obat += $item['subtotal'];
                }
            }
            
            // Ambil data pasien dan dokter
            $resepModel = new \App\Models\ResepModel();
            $pasienModel = new \App\Models\PasienModel();
            
            $pasienInfo = $pasienModel->builder()
                ->select('pasien.*, users.nama_lengkap as nama_dokter')
                ->join('resep', 'resep.id_pasien = pasien.id', 'left')
                ->join('users', 'users.id = resep.id_dokter', 'left')
                ->where('pasien.no_rekam_medis', $tagihan['no_rm'])
                ->orderBy('resep.tanggal_resep', 'DESC')
                ->get()
                ->getRowArray();
            
            // Hitung umur
            $umur = '-';
            if (!empty($pasienInfo['tanggal_lahir'])) {
                $lahir = new \DateTime($pasienInfo['tanggal_lahir']);
                $sekarang = new \DateTime();
                $umur = $sekarang->diff($lahir)->y . ' tahun';
            }
            
            // Prepare data untuk print
            $data = [
                'resep_list' => $resep_list,
                'pasien' => [
                    'no_rm' => $tagihan['no_rm'],
                    'nama_pasien' => $tagihan['nama_pasien'],
                    'tanggal_resep' => $tagihan['tanggal_tagihan'],
                    'nama_dokter' => $pasienInfo['nama_dokter'] ?? '-',
                    'jenis_kelamin' => $pasienInfo['jenis_kelamin'] ?? '-',
                    'umur' => $umur
                ],
                'total_obat' => $total_obat,
                'total_tagihan' => $tagihan['total_biaya'],
                'jumlah_bayar' => $tagihan['jumlah_bayar'] ?? 0,
                'kembalian' => $tagihan['kembalian'] ?? 0,
                'metode_pembayaran' => $tagihan['metode_pembayaran'] ?? '',
                'biaya_layanan' => [
                    'administrasi' => 35000,
                    'dokter' => 100000
                ]
            ];
            
            return view('kasir/print_tagihan', $data);
            
        } catch (\Exception $e) {
            log_message('error', "Exception in printTagihan: " . $e->getMessage());
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tagihan tidak ditemukan');
        }
    }
}
