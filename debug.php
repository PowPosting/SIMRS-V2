<?php
require 'spark';
$db = \Config\Database::connect();
$query = $db->query("SELECT a.*, p.nama_lengkap as nama_pasien, pol.nama as poli_tujuan 
                     FROM antrian a 
                     JOIN pasien p ON p.no_rekam_medis = a.no_rm 
                     JOIN poliklinik pol ON pol.id = a.id_poli 
                     WHERE a.no_rm = 'RM000006'");
$result = $query->getResult();
print_r($result);
?>
