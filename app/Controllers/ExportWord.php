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


            'tekanan_darah' => $data['tekanan_darah'] ?? '-',
            'detak_jantung' => $data['detak_jantung'] ?? '-',
            'suhu_tubuh' => $data['suhu_tubuh'] ?? '-',
            'pernafasan' => $data['pernafasan'] ?? '-',
            'berat_badan' => $data['berat_badan'] ?? '-',
            'tinggi_badan' => $data['tinggi_badan'] ?? '-',
            'keluhan' => $data['keluhan'] ?? '-',
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
        // Ambil data antrian dan pasien
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
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
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
}
