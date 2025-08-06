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
        $tagihanModel = new TagihanModel();
        $db = \Config\Database::connect();
        
        // Cari tagihan berdasarkan no_rm dan tanggal
        $tagihan = $tagihanModel->builder()
            ->select('tagihan.*, pasien.nama_lengkap as nama_pasien')
            ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(tagihan.no_rm AS CHAR)', 'left')
            ->where('tagihan.no_rm', $idPasien)
            ->where('DATE(tagihan.tanggal_tagihan)', $tanggal)
            ->where('tagihan.status', 'pending')
            ->get()
            ->getRowArray();
        
        if (!$tagihan) {
            return $this->response->setJSON(['error' => 'Tagihan tidak ditemukan']);
        }
        
        // Parse detail tagihan dari JSON
        $detail_tagihan = json_decode($tagihan['detail_tagihan'], true) ?? [];
        
        // Pisahkan obat dan biaya standar
        $resep_list = [];
        $total_obat = 0;
        
        foreach ($detail_tagihan as $item) {
            if (isset($item['nama_obat'])) {
                // Ini adalah item obat
                $resep_list[] = [
                    'nama_obat' => $item['nama_obat'],
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                    'harga_jual' => $item['harga_satuan'],
                    'subtotal' => $item['subtotal']
                ];
                $total_obat += $item['subtotal'];
            }
        }
        
        // Data pasien dan dokter - ambil dari resep terkait
        $resepModel = new \App\Models\ResepModel();
        $resepInfo = $resepModel->builder()
            ->select('resep.*, pasien.nama_lengkap as nama_pasien, users.nama_lengkap as nama_dokter')
            ->join('pasien', 'pasien.id = resep.id_pasien', 'left')
            ->join('users', 'users.id = resep.id_dokter', 'left')
            ->where('resep.id', $tagihan['no_resep'])
            ->get()
            ->getRowArray();
        
        $data = [
            'resep_list' => $resep_list,
            'pasien' => [
                'no_rm' => $tagihan['no_rm'],
                'nama_pasien' => $tagihan['nama_pasien'],
                'tanggal_resep' => $tagihan['tanggal_tagihan'],
                'nama_dokter' => $resepInfo['nama_dokter'] ?? '-',
                'jenis_kelamin' => $resepInfo['jenis_kelamin'] ?? '-',
                'umur' => $resepInfo['umur'] ?? '-'
            ],
            'total_obat' => $total_obat,
            'total_tagihan' => $tagihan['total_biaya'],
            'biaya_layanan' => [
                'administrasi' => 50000,
                'nurs_station' => 100000,
                'dokter' => 250000
            ]
        ];
        
        // Jika request AJAX (untuk modal), gunakan view modal
        if ($this->request->isAJAX()) {
            return view('kasir/detail_tagihan_modal', $data);
        }
        
        // Jika bukan AJAX (halaman penuh), gunakan view dengan layout
        return view('kasir/detail_tagihan', $data);
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
            $updateData = [
                'status' => 'paid',
                'tanggal_bayar' => date('Y-m-d H:i:s'),
                'metode_pembayaran' => $metodeBayar,
                'kasir_id' => $this->session->get('user_id') ?? 1,
                'keterangan' => 'Pembayaran berhasil - ' . ucfirst($metodeBayar)
            ];
            
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
                        'kembalian' => floatval($jumlahBayar) - floatval($tagihan['total_biaya']),
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
        // Dummy data, ganti dengan query ke database jika sudah ada tabel pembayaran
        $riwayat_pembayaran = [
            [
                'waktu_bayar' => '2025-08-05 14:30:00',
                'no_rm' => 'RM2025080001',
                'nama_pasien' => 'Budi Santoso',
                'total_tagihan' => 456500,
                'jumlah_bayar' => 500000,
                'kembalian' => 43500,
                'metode_bayar' => 'cash',
                'nama_kasir' => 'Admin Kasir',
            ],
            [
                'waktu_bayar' => '2025-08-05 13:10:00',
                'no_rm' => 'RM2025080002',
                'nama_pasien' => 'Siti Aminah',
                'total_tagihan' => 300000,
                'jumlah_bayar' => 300000,
                'kembalian' => 0,
                'metode_bayar' => 'transfer',
                'nama_kasir' => 'Admin Kasir',
            ],
        ];
        $data = [
            'riwayat_pembayaran' => $riwayat_pembayaran
        ];
        return view('kasir/riwayat_pembayaran', $data);
    }
}
