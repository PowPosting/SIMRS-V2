<?php

namespace App\Controllers;

class Shared extends BaseController
{
    protected $session;
    protected $db;
    protected $pasienModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->pasienModel = new \App\Models\PasienModel();
        
        // Hanya cek login
        if (!$this->session->get('isLoggedIn')) {
            redirect()->to(base_url('login'))->send();
            exit();
        }
    }

    public function datapasien()
    {
        $pasien = $this->pasienModel->findAll();
        
        $data = [
            'title' => 'Data Pasien - SIMRS',
            'pageTitle' => 'Data Pasien',
            'pasien' => $pasien
        ];

        return view('shared/datapasien', $data);
    }

    public function getDetailPasien($id)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pasien tidak ditemukan'
            ]);
        }

        try {
            // Get patient data
            $pasien = $this->pasienModel->find($id);
            if (!$pasien) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data pasien tidak ditemukan'
                ]);
            }

            // Load helpers
            helper(['tanggal']);

            // Format dates and calculate age
            $pasien['tanggal_lahir_formatted'] = tanggal_indonesia($pasien['tanggal_lahir']);
            $pasien['tanggal_daftar_formatted'] = tanggal_indonesia($pasien['tanggal_daftar'] ?? $pasien['created_at']);
            $pasien['umur'] = hitung_umur($pasien['tanggal_lahir']);
            $pasien['status_aktif_text'] = isset($pasien['status_aktif']) ? ($pasien['status_aktif'] ? 'Aktif' : 'Tidak Aktif') : 'Aktif';
            $pasien['jenis_kelamin_text'] = $pasien['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan';

            // Relasi: alamat
            $alamatModel = new \App\Models\AlamatPasienModel();
            $alamat = $alamatModel->where('pasien_id', $id)->first();
            if ($alamat) {
                $pasien['alamat_lengkap'] = $alamat['alamat_lengkap'] ?? '-';
                $pasien['kelurahan'] = $alamat['kelurahan'] ?? '-';
                $pasien['kecamatan'] = $alamat['kecamatan'] ?? '-';
                $pasien['kabupaten_kota'] = $alamat['kabupaten_kota'] ?? '-';
                $pasien['provinsi'] = $alamat['provinsi'] ?? '-';
                $pasien['kode_pos'] = $alamat['kode_pos'] ?? '-';
            } else {
                $pasien['alamat_lengkap'] = $pasien['kelurahan'] = $pasien['kecamatan'] = $pasien['kabupaten_kota'] = $pasien['provinsi'] = $pasien['kode_pos'] = '-';
            }

            // Relasi: kontak darurat (ambil satu kontak utama jika ada)
            $kontakModel = new \App\Models\KontakDaruratModel();
            $kontak = $kontakModel->where('pasien_id', $id)->first();
            if ($kontak) {
                $pasien['nama_kontak_darurat'] = $kontak['nama_kontak'] ?? '-';
                $pasien['hubungan_kontak_darurat'] = $kontak['hubungan'] ?? '-';
                $pasien['nomor_hp_kontak_darurat'] = $kontak['nomor_hp'] ?? '-';
                $pasien['alamat_kontak_darurat'] = $kontak['alamat'] ?? '-';
            } else {
                $pasien['nama_kontak_darurat'] = $pasien['hubungan_kontak_darurat'] = $pasien['nomor_hp_kontak_darurat'] = $pasien['alamat_kontak_darurat'] = '-';
            }

            // Relasi: info medis
            $medisModel = new \App\Models\InfoMedisPasienModel();
            $medis = $medisModel->where('pasien_id', $id)->first();
            if ($medis) {
                $pasien['golongan_darah'] = $medis['golongan_darah'] ?? '-';
            } else {
                $pasien['golongan_darah'] = '-';
            }

            // Relasi: info tambahan
            $tambahanModel = new \App\Models\InfoTambahanPasienModel();
            $tambahan = $tambahanModel->where('pasien_id', $id)->first();
            if ($tambahan) {
                $pasien['agama'] = $tambahan['agama'] ?? '-';
                $pasien['pendidikan_terakhir'] = $tambahan['pendidikan_terakhir'] ?? '-';
                $pasien['pekerjaan'] = $tambahan['pekerjaan'] ?? '-';
                $pasien['kewarganegaraan'] = $tambahan['kewarganegaraan'] ?? '-';
                $pasien['suku'] = $tambahan['suku'] ?? '-';
            } else {
                $pasien['agama'] = $pasien['pendidikan_terakhir'] = $pasien['pekerjaan'] = $pasien['kewarganegaraan'] = $pasien['suku'] = '-';
            }

            // Set response header
            $this->response->setHeader('Content-Type', 'application/json');
            return $this->response->setJSON([
                'success' => true,
                'data' => $pasien
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data'
            ]);
        }
    }

    public function editPasien($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('datapasien'));
        }

        $pasien = $this->pasienModel->find($id);
        if (!$pasien) {
            session()->setFlashdata('error', 'Data pasien tidak ditemukan.');
            return redirect()->to(base_url('datapasien'));
        }

        $data = [
            'title' => 'Edit Data Pasien - SIMRS',
            'pageTitle' => 'Edit Data Pasien',
            'pasien' => $pasien
        ];

        return view('shared/edit_pasien', $data);
    }

    public function updatePasien($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pasien tidak ditemukan'
            ]);
        }

        // Validate input data
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|valid_date',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'alamat' => 'required',
            'nomor_hp' => 'required|numeric|min_length[10]',
            'status_kawin' => 'required',
            'pekerjaan' => 'required',
            'kewarganegaraan' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $updateData = [
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'tempat_lahir' => $this->request->getPost('tempat_lahir'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'alamat' => $this->request->getPost('alamat'),
                'kecamatan' => $this->request->getPost('kecamatan'),
                'kabupaten' => $this->request->getPost('kabupaten'),
                'provinsi' => $this->request->getPost('provinsi'),
                'nomor_hp' => $this->request->getPost('nomor_hp'),
                'golongan_darah' => $this->request->getPost('golongan_darah'),
                'status_kawin' => $this->request->getPost('status_kawin'),
                'pekerjaan' => $this->request->getPost('pekerjaan'),
                'kewarganegaraan' => $this->request->getPost('kewarganegaraan'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->pasienModel->update($id, $updateData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data pasien berhasil diperbarui'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal memperbarui data pasien'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function deletePasien($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pasien tidak ditemukan'
            ]);
        }

        try {
            if ($this->pasienModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data pasien berhasil dihapus'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data pasien'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
