<?php

namespace App\Models;

use CodeIgniter\Model;

class PostalCodeModel extends Model
{
    protected $table = 'db_postal_code_data';
    protected $primaryKey = 'id';
    protected $allowedFields = ['postal_code', 'sub_district', 'urban', 'city', 'province_code'];
    protected $useTimestamps = false;

    public function findByPostalCode($postalCode)
    {
        $builder = $this->db->table($this->table);
        $builder->select('db_postal_code_data.*, db_province_data.province_name')
                ->join('db_province_data', 'db_province_data.province_code = db_postal_code_data.province_code')
                ->where('db_postal_code_data.postal_code', $postalCode);

        // Log the generated SQL query for debugging
        log_message('debug', 'SQL Query: ' . $builder->getCompiledSelect());

        $results = $builder->get()->getResultArray();
        log_message('debug', 'All Results: ' . json_encode($results));
        $result = null;
        foreach ($results as $row) {
            if ($row['postal_code'] === $postalCode) {
                $result = $row;
                break;
            }
        }
        log_message('debug', 'Filtered Result: ' . json_encode($result));
        return $result;
    }
}
