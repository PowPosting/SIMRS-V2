<?php

namespace App\Controllers;

use App\Models\PasienModel;
use App\Models\AlamatPasienModel;
use App\Models\InfoMedisPasienModel;
use App\Models\InfoTambahanPasienModel;
use App\Models\KontakDaruratModel;

class Pasien extends BaseController
{
    protected $pasienModel;
    protected $alamatModel;
    protected $infoMedisModel;
    protected $infoTambahanModel;
    protected $kontakDaruratModel;

    public function __construct()
    {
        $this->pasienModel = new PasienModel();
        $this->alamatModel = new AlamatPasienModel();
        $this->infoMedisModel = new InfoMedisPasienModel();
        $this->infoTambahanModel = new InfoTambahanPasienModel();
        $this->kontakDaruratModel = new KontakDaruratModel();
    }

    public function index()
    {
        $data['title'] = 'Data Pasien';
        $data['pasien'] = $this->pasienModel->findAll();
        
        return view('pasien/index', $data);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Pasien';
        $data['pasien'] = $this->pasienModel->getPasienLengkap($id);
        $data['kontak_darurat'] = $this->kontakDaruratModel->where('pasien_id', $id)->findAll();
        
        return view('pasien/detail', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Pasien Baru';
        
        if ($this->request->getMethod() === 'post') {
            $this->db->transStart();
            
            try {
                // Insert data pasien
                $pasienId = $this->pasienModel->insert([
                    'no_rekam_medis' => $this->request->getPost('no_rekam_medis'),
                    'title' => $this->request->getPost('title'),
                    'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                    'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                    'tempat_lahir' => $this->request->getPost('tempat_lahir'),
                    'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                    'status_perkawinan' => $this->request->getPost('status_perkawinan'),
                    'nomor_identitas' => $this->request->getPost('nomor_identitas'),
                    'email' => $this->request->getPost('email'),
                    'nomor_hp' => $this->request->getPost('nomor_hp'),
                    'tanggal_daftar' => date('Y-m-d H:i:s')
                ]);

                // Insert alamat
                $this->alamatModel->insert([
                    'pasien_id' => $pasienId,
                    'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
                    'rt' => $this->request->getPost('rt'),
                    'rw' => $this->request->getPost('rw'),
                    'kelurahan' => $this->request->getPost('kelurahan'),
                    'kecamatan' => $this->request->getPost('kecamatan'),
                    'kabupaten_kota' => $this->request->getPost('kabupaten_kota'),
                    'provinsi' => $this->request->getPost('provinsi'),
                    'kode_pos' => $this->request->getPost('kode_pos')
                ]);

                // Insert info medis
                $this->infoMedisModel->insert([
                    'pasien_id' => $pasienId,
                    'golongan_darah' => $this->request->getPost('golongan_darah'),
                    'rhesus' => $this->request->getPost('rhesus'),
                    'alergi' => $this->request->getPost('alergi'),
                    'riwayat_penyakit' => $this->request->getPost('riwayat_penyakit')
                ]);

                // Insert info tambahan
                $this->infoTambahanModel->insert([
                    'pasien_id' => $pasienId,
                    'agama' => $this->request->getPost('agama'),
                    'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
                    'pekerjaan' => $this->request->getPost('pekerjaan'),
                    'kewarganegaraan' => $this->request->getPost('kewarganegaraan'),
                    'suku' => $this->request->getPost('suku'),
                    'bahasa' => $this->request->getPost('bahasa')
                ]);

                // Insert kontak darurat
                $this->kontakDaruratModel->insert([
                    'pasien_id' => $pasienId,
                    'nama_kontak' => $this->request->getPost('nama_kontak'),
                    'hubungan' => $this->request->getPost('hubungan'),
                    'nomor_hp' => $this->request->getPost('kontak_nomor_hp'),
                    'alamat' => $this->request->getPost('kontak_alamat')
                ]);

                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \Exception('Gagal menyimpan data pasien');
                }

                return redirect()->to('/pasien')->with('success', 'Data pasien berhasil ditambahkan');
            } catch (\Exception $e) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }

        return view('pasien/create', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Data Pasien';
        $data['pasien'] = $this->pasienModel->getPasienLengkap($id);
        
        if ($this->request->getMethod() === 'post') {
            $this->db->transStart();
            
            try {
                // Update data pasien
                $this->pasienModel->update($id, [
                    'title' => $this->request->getPost('title'),
                    'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                    'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                    'tempat_lahir' => $this->request->getPost('tempat_lahir'),
                    'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                    'status_perkawinan' => $this->request->getPost('status_perkawinan'),
                    'email' => $this->request->getPost('email'),
                    'nomor_hp' => $this->request->getPost('nomor_hp')
                ]);

                // Update alamat
                $this->alamatModel->where('pasien_id', $id)->set([
                    'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
                    'rt' => $this->request->getPost('rt'),
                    'rw' => $this->request->getPost('rw'),
                    'kelurahan' => $this->request->getPost('kelurahan'),
                    'kecamatan' => $this->request->getPost('kecamatan'),
                    'kabupaten_kota' => $this->request->getPost('kabupaten_kota'),
                    'provinsi' => $this->request->getPost('provinsi'),
                    'kode_pos' => $this->request->getPost('kode_pos')
                ])->update();

                // Update info medis
                $this->infoMedisModel->where('pasien_id', $id)->set([
                    'golongan_darah' => $this->request->getPost('golongan_darah'),
                    'rhesus' => $this->request->getPost('rhesus'),
                    'alergi' => $this->request->getPost('alergi'),
                    'riwayat_penyakit' => $this->request->getPost('riwayat_penyakit')
                ])->update();

                // Update info tambahan
                $this->infoTambahanModel->where('pasien_id', $id)->set([
                    'agama' => $this->request->getPost('agama'),
                    'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
                    'pekerjaan' => $this->request->getPost('pekerjaan'),
                    'kewarganegaraan' => $this->request->getPost('kewarganegaraan'),
                    'suku' => $this->request->getPost('suku'),
                    'bahasa' => $this->request->getPost('bahasa')
                ])->update();

                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \Exception('Gagal mengupdate data pasien');
                }

                return redirect()->to('/pasien')->with('success', 'Data pasien berhasil diupdate');
            } catch (\Exception $e) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }

        return view('pasien/edit', $data);
    }

    public function delete($id)
    {
        try {
            // Soft delete
            $this->pasienModel->delete($id);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
