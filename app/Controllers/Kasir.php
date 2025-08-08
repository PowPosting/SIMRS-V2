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
        // Ambil data tagihan dari table tagihan
        $tagihanModel = new TagihanModel();
        $db = \Config\Database::connect();
        
        // Ambil tagihan yang belum dibayar dengan join ke pasien untuk nama
        $tagihan_list = $tagihanModel->builder()
            ->select('tagihan.*, pasien.nama_lengkap as nama_pasien')
            ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(tagihan.no_rm AS CHAR)', 'left')
            ->where('tagihan.status', 'pending')
            ->orderBy('tagihan.tanggal_tagihan', 'DESC')
            ->get()
            ->getResultArray();
        
        // Format data untuk view
        foreach ($tagihan_list as &$tagihan) {
            $tagihan['tanggal'] = date('Y-m-d', strtotime($tagihan['tanggal_tagihan']));
            $tagihan['id_pasien'] = $tagihan['no_rm']; // Untuk compatibility dengan view
            $tagihan['total_tagihan'] = $tagihan['total_biaya'];
        }

        $data = [
            'title' => 'Kasir - SIMRS',
            'pageTitle' => 'Kasir & Pembayaran Pasien',
            'tagihan_list' => $tagihan_list
        ];
        return view('kasir/kasir', $data);
    }

    /**
     * Ambil detail tagihan untuk modal (AJAX)
     */
    public function detailTagihan($idPasien, $tanggal)
    {
        log_message('info', "detailTagihan called with idPasien: $idPasien, tanggal: $tanggal");
        
        try {
            $tagihanModel = new TagihanModel();
            $db = \Config\Database::connect();
            
            log_message('info', "Models initialized successfully");
            
            // Cari tagihan berdasarkan no_rm dan tanggal
            $tagihan = $tagihanModel->builder()
                ->select('tagihan.*, pasien.nama_lengkap as nama_pasien')
                ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(tagihan.no_rm AS CHAR)', 'left')
                ->where('tagihan.no_rm', $idPasien)
                ->where('DATE(tagihan.tanggal_tagihan)', $tanggal)
                ->where('tagihan.status', 'pending')
                ->get()
                ->getRowArray();
            
            log_message('info', "Tagihan query executed. Result: " . json_encode($tagihan));
            
            if (!$tagihan) {
                log_message('error', "Tagihan not found for idPasien: $idPasien, tanggal: $tanggal");
                return $this->response->setJSON(['error' => 'Tagihan tidak ditemukan']);
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
                'biaya_layanan' => [
                    'administrasi' => 35000,
                    'dokter' => 100000
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
                    $kasirInfo = $userModel->find($tagihan['id_kasir'] ?? 1);
                    
                    $simpleData = [
                        'success' => true,
                        'data' => [
                            'id' => $tagihan['id'],
                            'no_tagihan' => $tagihan['no_tagihan'] ?? 'TG-' . str_pad($tagihan['id'], 8, '0', STR_PAD_LEFT),
                            'no_rm' => $tagihan['no_rm'] ?? '-',
                            'nama_pasien' => $tagihan['nama_pasien'] ?? '-',
                            'tanggal_lahir' => $tagihan['tanggal_lahir'] ?? '-',
                            'jenis_layanan' => $tagihan['jenis_layanan'] ?? 'Rawat Jalan',
                            'total_biaya' => $tagihan['total_biaya'] ?? 0,
                            'jumlah_bayar' => $tagihan['jumlah_bayar'] ?? 0,
                            'kembalian' => ($tagihan['jumlah_bayar'] ?? 0) - ($tagihan['total_biaya'] ?? 0),
                            'metode_pembayaran' => $tagihan['metode_pembayaran'] ?? 'Cash',
                            'tanggal_pembayaran' => date('d/m/Y H:i', strtotime($tagihan['tanggal_bayar'] ?? 'now')),
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
            
            // Cari tagihan berdasarkan no_rm dan tanggal
            $tagihan = $tagihanModel->builder()
                ->where('no_rm', $idPasien)
                ->where('DATE(tanggal_tagihan)', $tanggal)
                ->where('status', 'pending')
                ->get()
                ->getRowArray();
            
            if (!$tagihan) {
                throw new \Exception('Tagihan tidak ditemukan atau sudah dibayar');
            }
            
            // Validasi jumlah bayar
            if (floatval($jumlahBayar) < floatval($tagihan['total_biaya'])) {
                throw new \Exception('Jumlah bayar kurang dari total tagihan');
            }
            
            // Update status tagihan menjadi paid
            $kembalian = floatval($jumlahBayar) - floatval($tagihan['total_biaya']);
            
            $updateData = [
                'status' => 'paid',
                'tanggal_bayar' => date('Y-m-d H:i:s'),
                'metode_pembayaran' => $metodeBayar,
                'jumlah_bayar' => floatval($jumlahBayar),
                'kembalian' => $kembalian,
                'kasir_id' => $this->session->get('user_id') ?? 1,
                'keterangan' => 'Pembayaran berhasil - ' . ucfirst($metodeBayar)
            ];
            
            log_message('info', "Update Data: " . json_encode($updateData));
            
            $updateResult = $tagihanModel->update($tagihan['id_tagihan'], $updateData);
            
            if ($updateResult) {
                // Update status antrian pasien menjadi 'Selesai'
                $db->table('antrian')
                    ->where('no_rm', $idPasien)
                    ->where('DATE(created_at)', $tanggal)
                    ->update(['status' => 'Selesai']);
                
                $db->table('antrian_poli')
                    ->where('no_rm', $idPasien)
                    ->where('DATE(created_at)', $tanggal)
                    ->update(['status' => 'Selesai']);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diproses',
                    'data' => [
                        'id_tagihan' => $tagihan['id_tagihan'],
                        'total_tagihan' => $tagihan['total_biaya'],
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
            // no_rm sudah sesuai, tidak perlu diubah
            $riwayat['total_tagihan'] = $riwayat['total_biaya'];
            $riwayat['jumlah_bayar'] = $riwayat['jumlah_bayar'] ?? $riwayat['total_biaya']; // Gunakan field dari DB atau fallback
            $riwayat['kembalian'] = $riwayat['kembalian'] ?? 0; // Gunakan field dari DB atau default 0
            $riwayat['metode_bayar'] = $riwayat['metode_pembayaran'] ?? 'tunai'; // Perbaiki mapping
            $riwayat['nama_kasir'] = $riwayat['nama_kasir'] ?? 'System';
        }
        
        // Jika tidak ada data dari database, gunakan dummy data
        if (empty($riwayat_pembayaran)) {
            $riwayat_pembayaran = [
                [
                    'waktu_bayar' => '2025-08-06 14:30:00',
                    'no_rm' => 'RM2025070033',
                    'nama_pasien' => 'Patrik RM',
                    'total_tagihan' => 510000,
                    'jumlah_bayar' => 510000,
                    'kembalian' => 0,
                    'metode_bayar' => 'cash',
                    'nama_kasir' => 'Admin Kasir',
                ],
                [
                    'waktu_bayar' => '2025-08-06 13:10:00',
                    'no_rm' => 'RM2025080001',
                    'nama_pasien' => 'Budi Santoso',
                    'total_tagihan' => 456500,
                    'jumlah_bayar' => 500000,
                    'kembalian' => 43500,
                    'metode_bayar' => 'cash',
                    'nama_kasir' => 'Admin Kasir',
                ],
                [
                    'waktu_bayar' => '2025-08-06 12:45:00',
                    'no_rm' => 'RM2025080002',
                    'nama_pasien' => 'Siti Aminah',
                    'total_tagihan' => 300000,
                    'jumlah_bayar' => 300000,
                    'kembalian' => 0,
                    'metode_bayar' => 'transfer',
                    'nama_kasir' => 'Admin Kasir',
                ],
                [
                    'waktu_bayar' => '2025-08-06 11:20:00',
                    'no_rm' => 'RM2025080003',
                    'nama_pasien' => 'Ahmad Wijaya',
                    'total_tagihan' => 275000,
                    'jumlah_bayar' => 300000,
                    'kembalian' => 25000,
                    'metode_bayar' => 'cash',
                    'nama_kasir' => 'Kasir 2',
                ],
                [
                    'waktu_bayar' => '2025-08-06 10:15:00',
                    'no_rm' => 'RM2025080004',
                    'nama_pasien' => 'Maria Gonzalez',
                    'total_tagihan' => 825000,
                    'jumlah_bayar' => 825000,
                    'kembalian' => 0,
                    'metode_bayar' => 'debit',
                    'nama_kasir' => 'Admin Kasir',
                ],
                [
                    'waktu_bayar' => '2025-08-06 09:30:00',
                    'no_rm' => 'RM2025080005',
                    'nama_pasien' => 'Robert Johnson',
                    'total_tagihan' => 150000,
                    'jumlah_bayar' => 150000,
                    'kembalian' => 0,
                    'metode_bayar' => 'qris',
                    'nama_kasir' => 'Kasir 3',
                ],
            ];
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
}
