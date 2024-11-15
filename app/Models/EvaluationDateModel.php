<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluationDateModel extends Model
{
    protected $table = 'evaluation_dates';  // The name of the table
    protected $primaryKey = 'id';  // Primary key for the table
    protected $allowedFields = ['open_datetime', 'close_datetime'];  // Fields you want to be insertable or updatable
    protected $useTimestamps = true;  // Automatically manage created_at and updated_at timestamps

    // Automatically format date fields before saving them
    protected $beforeInsert = ['formatDateTime'];
    protected $beforeUpdate = ['formatDateTime'];

    // Format date fields to 'Y-m-d H:i:s'
    protected function formatDateTime(array $data)
    {
        if (isset($data['data']['open_datetime'])) {
            $data['data']['open_datetime'] = $this->formatDate($data['data']['open_datetime']);
        }
        if (isset($data['data']['close_datetime'])) {
            $data['data']['close_datetime'] = $this->formatDate($data['data']['close_datetime']);
        }
        return $data;
    }

    private function formatDate($date)
    {
        // Format the date to 'Y-m-d H:i:s' before saving to the database
        return date('Y-m-d H:i:s', strtotime($date));
    }

    // Method to fetch all evaluation dates from the database
    public function getEvaluationDates()
    {
        // Use CodeIgniter query builder to fetch all records from the table
        return $this->db->table($this->table)->get()->getResultArray();
    }
}
