<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpWord\TemplateProcessor;
use CodeIgniter\HTTP\ResponseInterface;

class ExportWord extends Controller
    
{
    public function pasien($no_rm)
    {
        // 1. Ambil data pasien dari database (ganti dengan model Anda)
        $pasienModel = model('App\Models\PasienModel');
        $data = $pasienModel->getByNoRM($no_rm); // pastikan method ini ada
        if (!$data) {
            return $this->response->setStatusCode(404)->setBody('Data pasien tidak ditemukan');
        }

        // 2. Siapkan data untuk template
        $replace = [
            'nama' => $data['nama'] ?? '-',
            'no_rm' => $data['no_rm'] ?? '-',
            'nomor_identitas' => $data['nomor_identitas'] ?? '-',
            'status_aktif' => $data['status_aktif'] ?? '-',
            'nama_lengkap' => $data['nama_lengkap'] ?? '-',
            'gelar' => $data['gelar'] ?? '-',
            'nomor_hp' => $data['nomor_hp'] ?? '-',
            'jenis_kelamin' => $data['jenis_kelamin'] ?? '-',
            'ttl' => $data['ttl'] ?? '-',
            'status_perkawinan' => $data['status_perkawinan'] ?? '-',
            'alamat' => $data['alamat'] ?? '-',
            'kelurahan' => $data['kelurahan'] ?? '-',
            'kecamatan' => $data['kecamatan'] ?? '-',
            'kabupaten_kota' => $data['kabupaten_kota'] ?? '-',
            'provinsi' => $data['provinsi'] ?? '-',
            'kode_pos' => $data['kode_pos'] ?? '-',
            'nama_kontak_darurat' => $data['nama_kontak'] ?? '-',
            'hubungan_kontak_darurat' => $data['hubungan_kontak_darurat'] ?? '-',
            'nomor_hp_kontak_darurat' => $data['nomor_hp_kontak_darurat'] ?? '-',
            'alamat_kontak_darurat' => $data['alamat_kontak_darurat'] ?? '-',
            'golongan_darah' => $data['golongan_darah'] ?? '-',
            'agama' => $data['agama'] ?? '-',
            'pendidikan' => $data['pendidikan'] ?? '-',
            'pekerjaan' => $data['pekerjaan'] ?? '-',
            'kewarganegaraan' => $data['kewarganegaraan'] ?? '-',
            'suku' => $data['suku'] ?? '-',
            'tanggal_cetak' => date('d-m-Y'),


        ];

        // 3. Load template Word
        $templatePath = APPPATH . 'Templates/word/pasien_template.docx';
        if (!file_exists($templatePath)) {
            return $this->response->setStatusCode(500)->setBody('Template Word tidak ditemukan');
        }
        $templateProcessor = new TemplateProcessor($templatePath);
        foreach ($replace as $key => $val) {
            $templateProcessor->setValue($key, $val);
        }

        // 4. Output file Word ke browser
        $filename = 'Identitas_Pasien_' . $replace['no_rm'] . '.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $templateProcessor->saveAs('php://output');
        exit;
    }

    // cetak nomor antrian

     
    public function antrian($no_antrian)
    {
        $antrianModel = model('App\\Models\\AntrianModel');
        $antrian = $antrianModel->where('no_antrian', $no_antrian)->first();
        if (!$antrian) {
            return $this->response->setStatusCode(404)->setBody('Data antrian tidak ditemukan');
        }
        $replace = [
            'no_antrian' => $antrian['no_antrian'],
            'tanggal' => date('d-m-Y'),
        ];
        $templatePath = APPPATH . 'Templates/word/antrian_template.docx';
        if (!file_exists($templatePath)) {
            return $this->response->setStatusCode(500)->setBody('Template Word antrian tidak ditemukan');
        }
        $templateProcessor = new TemplateProcessor($templatePath);
        foreach ($replace as $key => $val) {
            $templateProcessor->setValue($key, $val);
        }
        $filename = 'Nomor_Antrian_' . $replace['no_antrian'] . '.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $templateProcessor->saveAs('php://output');
        exit;
    }

    // Export antrian poli lengkap ke Word (dengan data poli, pasien, waktu, dsb)
    public function antrianPoli($no_antrian)
    {
        $antrianPoliModel = model('App\\Models\\AntrianPoliModel');
        $antrian = $antrianPoliModel->where('no_antrian', $no_antrian)->first();
        if (!$antrian) {
            return $this->response->setStatusCode(404)->setBody('Data antrian poli tidak ditemukan');
        }
        // Ambil data poli
        $poliModel = model('App\\Models\\PoliklinikModel');
        $poli = $poliModel->find($antrian['id_poli'] ?? 0);
        $nama_poli = $poli['nama'] ?? '-';
        // Ambil data pasien
        $pasienModel = model('App\\Models\\PasienModel');
        $pasien = $pasienModel->where('no_rekam_medis', $antrian['no_rm'] ?? '')->first();
        $nama_pasien = $pasien['nama_lengkap'] ?? '-';
        // Siapkan data untuk template Word
        $replace = [
            'no_antrian' => $antrian['no_antrian'],
            'tanggal' => isset($antrian['created_at']) ? date('d-m-Y', strtotime($antrian['created_at'])) : date('d-m-Y'),
            'jam' => isset($antrian['created_at']) ? date('H:i', strtotime($antrian['created_at'])) : date('H:i'),
            'nama_poli' => $nama_poli,
            'nama_pasien' => $nama_pasien,
            'no_rm' => $antrian['no_rm'] ?? '-',
            'status' => $antrian['status'] ?? '-',
        ];
        $templatePath = APPPATH . 'Templates/word/antrian_poli_template.docx';
        if (!file_exists($templatePath)) {
            return $this->response->setStatusCode(500)->setBody('Template Word antrian poli tidak ditemukan');
        }
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        foreach ($replace as $key => $val) {
            $templateProcessor->setValue($key, $val);
        }
        $filename = 'Antrian_Poli_' . $replace['no_antrian'] . '.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $templateProcessor->saveAs('php://output');
        exit;
    }
    // Export hasil pemeriksaan pasien ke Word
    public function pemeriksaan($id)
    {
        $pemeriksaanModel = model('App\\Models\\PemeriksaanAwalModel');
        $data = $pemeriksaanModel->find($id);
        if (!$data) {
            return $this->response->setStatusCode(404)->setBody('Data pemeriksaan tidak ditemukan');
        }
        // Ambil data antrian untuk dapatkan no_rm
        $antrianModel = model('App\\Models\\AntrianModel');
        $antrian = $antrianModel->find($data['id_antrian'] ?? 0);
        $no_rm = $antrian['no_rm'] ?? '-';
        // Ambil data pasien untuk dapatkan nama pasien
        $pasienModel = model('App\\Models\\PasienModel');
        $pasien = $pasienModel->where('no_rekam_medis', $antrian['no_rm'] ?? '')->first();
        $nama_pasien = $pasien['nama_lengkap'] ?? '-';
        // Siapkan data untuk template Word
        $replace = [
            'no_rm' => $no_rm,
            'nama_pasien' => $nama_pasien,
            'tanggal_pemeriksaan' => isset($data['created_at']) ? date('d F Y H:i', strtotime($data['created_at'])) : '-',
            'tekanan_darah' => $data['tekanan_darah'] ?? '-',
            'detak_jantung' => $data['detak_jantung'] ?? '-',
            'suhu_tubuh' => $data['suhu_tubuh'] ?? '-',
            'pernafasan' => $data['pernafasan'] ?? '-',
            'tinggi_badan' => $data['tinggi_badan'] ?? '-',
            'berat_badan' => $data['berat_badan'] ?? '-',
            'keluhan' => $data['keluhan'] ?? '-',
            'tanggal_cetak' => date('d-m-Y'),
            'jam_cetak' => date('H:i'),
        ];
        $templatePath = APPPATH . 'Templates/word/pemeriksaan_template.docx';
        if (!file_exists($templatePath)) {
            return $this->response->setStatusCode(500)->setBody('Template Word pemeriksaan tidak ditemukan');
        }
        $templateProcessor = new TemplateProcessor($templatePath);
        foreach ($replace as $key => $val) {
            $templateProcessor->setValue($key, $val);
        }
        $filename = 'Hasil_Pemeriksaan_' . $replace['no_rm'] . '.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $templateProcessor->saveAs('php://output');
        exit;
    }

    // Export hasil pemeriksaan SOAP ke Word dengan template pemeriksaansoap_template.docx
    public function pemeriksaansoap($id)
    {
        $soapModel = model('App\\Models\\PemeriksaanSoapModel');
        $data = $soapModel->find($id);
        if (!$data) {
            return $this->response->setStatusCode(404)->setBody('Data pemeriksaan SOAP tidak ditemukan');
        }
        $no_rm = $data['no_rm'] ?? '-';
        // Ambil data pasien untuk dapatkan nama pasien
        $pasienModel = model('App\\Models\\PasienModel');
        $pasien = $pasienModel->where('no_rekam_medis', $no_rm)->first();
        $nama_pasien = $pasien['nama_lengkap'] ?? '-';
        // Ambil data dokter pemeriksa
        $dokter_nama = '-';
        if (!empty($data['id_dokter'])) {
            $db = \Config\Database::connect();
            $dokter = $db->table('users')->where('id', $data['id_dokter'])->get()->getRowArray();
            if ($dokter && !empty($dokter['nama_lengkap'])) {
                $dokter_nama = $dokter['nama_lengkap'];
            } elseif ($dokter && !empty($dokter['fullname'])) {
                $dokter_nama = $dokter['fullname'];
            } elseif ($dokter && !empty($dokter['nama'])) {
                $dokter_nama = $dokter['nama'];
            }
        }
        // Format obat_manual dan obat_db menjadi string nama obat
        $db = \Config\Database::connect();
        // Obat manual: decode json, gabung jadi string
        $obat_manual_str = '-';
        if (!empty($data['obat_manual'])) {
            $obat_manual = json_decode($data['obat_manual'], true);
            if (is_array($obat_manual) && count($obat_manual) > 0) {
                $obat_manual = array_filter(array_map('trim', $obat_manual));
                if (count($obat_manual) > 0) {
                    $obat_manual_str = implode(", ", $obat_manual);
                }
            }
        }

        // Obat db: decode json, ambil nama dari tabel obat
        $obat_db_str = '-';
        if (!empty($data['obat_db'])) {
            $obat_db = json_decode($data['obat_db'], true);
            if (is_array($obat_db) && count($obat_db) > 0) {
                $obatRows = $db->table('obat')->whereIn('id_obat', $obat_db)->get()->getResultArray();
                $obatMap = [];
                foreach ($obatRows as $ob) {
                    $obatMap[$ob['id_obat']] = $ob['nama_obat'];
                }
                $obatNames = [];
                foreach ($obat_db as $oid) {
                    if (isset($obatMap[$oid])) {
                        $obatNames[] = $obatMap[$oid];
                    }
                }
                if (count($obatNames) > 0) {
                    $obat_db_str = implode(", ", $obatNames);
                }
            }
        }

        // Format tanggal pemeriksaan ke format Indonesia (30 Juli 2025 14:30 WIB)
        function indo_datetime($datetime) {
            $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            $ts = strtotime($datetime);
            $tgl = date('d', $ts);
            $bln = $bulan[(int)date('m', $ts)];
            $thn = date('Y', $ts);
            return "$tgl $bln $thn";
        }

        $replace = [
            'no_rm' => $no_rm,
            'nama_pasien' => $nama_pasien,
            'tanggal_pemeriksaan' => isset($data['created_at']) ? indo_datetime($data['created_at']) : '-',
            'keluhan_utama' => $data['keluhan_utama'] ?? '-',
            'riwayat_penyakit' => $data['riwayat_penyakit'] ?? '-',
            'tekanan_darah' => $data['tekanan_darah'] ?? '-',
            'denyut_nadi' => $data['denyut_nadi'] ?? '-',
            'suhu_tubuh' => $data['suhu_tubuh'] ?? '-',
            'respirasi' => $data['respirasi'] ?? '-',
            'pemeriksaan_fisik' => $data['pemeriksaan_fisik'] ?? '-',
            'diagnosis' => $data['diagnosis'] ?? '-',
            'prognosis' => $data['prognosis'] ?? '-',
            'obat_manual' => $obat_manual_str,
            'obat_db' => $obat_db_str,
            'edukasi' => $data['edukasi'] ?? '-',
            'id_dokter' => $dokter_nama,
            'tanggal_cetak' => date('d-m-Y'),
            'jam_cetak' => date('H:i'),
        ];
        $templatePath = APPPATH . 'Templates/word/pemeriksaansoap_template.docx';
        if (!file_exists($templatePath)) {
            return $this->response->setStatusCode(500)->setBody('Template Word SOAP tidak ditemukan');
        }
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        foreach ($replace as $key => $val) {
            $templateProcessor->setValue($key, $val);
        }
        $filename = 'Pemeriksaan_SOAP_' . $replace['no_rm'] . '.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $templateProcessor->saveAs('php://output');
        exit;
    }
}
