<?php

namespace App\Models;

use CodeIgniter\Model;

class PemeriksaanSoapModel extends Model
{
    protected $table = 'pemeriksaan_soap';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'no_rm', 'id_dokter', 'keluhan_utama', 'riwayat_penyakit', 'riwayat_alergi',
        'tekanan_darah', 'denyut_nadi', 'suhu_tubuh', 'respirasi', 'pemeriksaan_fisik',
        'diagnosis', 'prognosis', 'edukasi', 'obat_manual', 'obat_db',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = false; // set true if you want auto timestamps

    /**
     * Relasi ke tabel pasien berdasarkan no_rm
     */
    public function getPasien($no_rm)
    {
        $db = \Config\Database::connect();
        return $db->table('pasien')->where('no_rekam_medis', $no_rm)->get()->getRowArray();
    }

    /**
     * Relasi ke tabel poliklinik berdasarkan kode/nama poli (jika ada kolom poli di tabel SOAP)
     * Jika kolom poli di pemeriksaan_soap adalah kode/nama, sesuaikan query di bawah ini.
     */
    public function getPoli($kodePoli)
    {
        $db = \Config\Database::connect();
        // Jika kolom 'poli' di pemeriksaan_soap adalah kode poli
        return $db->table('poliklinik')->where('kode', $kodePoli)->get()->getRowArray();
        // Jika kolom 'poli' adalah id poli, ganti 'kode' menjadi 'id'
    }

    /**
     * Relasi ke PemeriksaanAwalModel berdasarkan no_rm
     */
    public function getPemeriksaanAwal($no_rm)
    {
        $pemeriksaanAwalModel = new PemeriksaanAwalModel();
        return $pemeriksaanAwalModel->where('no_rm', $no_rm)->first();
    }
}
