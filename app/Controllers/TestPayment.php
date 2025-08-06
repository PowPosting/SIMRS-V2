<?php

namespace App\Controllers;

use App\Models\TagihanModel;

class TestPayment extends BaseController {
    
    public function index()
    {
        echo "<h2>Test Payment Endpoint</h2>";
        
        // Test proses pembayaran tanpa auth
        return $this->processPayment();
    }
    
    public function processPayment()
    {
        try {
            // Hardcode test data
            $idPasien = '2025070007';
            $tanggal = '2025-08-05';
            $jumlahBayar = 120000;
            $metodeBayar = 'cash';
            $totalTagihan = 109500;
            
            echo "<h3>Processing payment for:</h3>";
            echo "Patient: $idPasien<br>";
            echo "Date: $tanggal<br>";
            echo "Amount: Rp " . number_format($jumlahBayar) . "<br>";
            echo "Method: $metodeBayar<br>";
            echo "Total Bill: Rp " . number_format($totalTagihan) . "<br><br>";
            
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
                throw new \Exception('Tagihan tidak ditemukan');
            }
            
            echo "Found bill ID: " . $tagihan['id_tagihan'] . "<br>";
            echo "Current status: " . $tagihan['status'] . "<br><br>";
            
            // Update status tagihan menjadi paid
            $updateData = [
                'status' => 'paid',
                'tanggal_bayar' => date('Y-m-d H:i:s'),
                'metode_pembayaran' => $metodeBayar,
                'kasir_id' => 1, // Hardcode kasir ID
                'keterangan' => 'Test pembayaran berhasil'
            ];
            
            $updateResult = $tagihanModel->update($tagihan['id_tagihan'], $updateData);
            
            if ($updateResult) {
                echo "<div style='color: green;'>";
                echo "<h3>✅ Payment Successful!</h3>";
                echo "Status updated to: paid<br>";
                echo "Payment date: " . date('Y-m-d H:i:s') . "<br>";
                echo "Method: $metodeBayar<br>";
                echo "</div>";
                
                // Update status antrian pasien menjadi 'Selesai'
                $antrianUpdate = $db->table('antrian')
                    ->where('no_rm', $idPasien)
                    ->where('DATE(created_at)', $tanggal)
                    ->update(['status' => 'Selesai']);
                
                echo "Antrian updated: " . ($antrianUpdate ? "Yes" : "No") . "<br>";
                
                return [
                    'success' => true,
                    'message' => 'Pembayaran tagihan pasien berhasil diproses'
                ];
            } else {
                throw new \Exception('Gagal memperbarui status pembayaran');
            }
            
        } catch (\Exception $e) {
            echo "<div style='color: red;'>";
            echo "<h3>❌ Payment Failed!</h3>";
            echo "Error: " . $e->getMessage();
            echo "</div>";
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
