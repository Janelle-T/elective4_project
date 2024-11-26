<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluationDateModel extends Model
{
    protected $table = 'evaluation_dates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['open_datetime', 'close_datetime'];
    protected $useTimestamps = true;
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
        return date('Y-m-d H:i:s', strtotime($date));
    }

    public function dashboard()
    {
        $evaluationDateModel = new EvaluationDateModel();
        $data['evaluationDates'] = $evaluationDateModel->getEvaluationDates();
        return view('student_dash', $data);
    }

    // Method to fetch all evaluation dates from the database
    public function getEvaluationDates()
    {
        $query = $this->db->table($this->table)->get();
        $results = $query->getResultArray();

        // Add debugging output here:
        if (empty($results)) {
            log_message('error', "No evaluation dates found in the database.");
        } else {
            log_message('debug', "Evaluation dates fetched successfully: " . json_encode($results));
        }

        return $results;
    }

    public function isEvaluationOpen()
    {
        $now = date('Y-m-d H:i:s');
        $evaluationDates = $this->orderBy('open_datetime', 'ASC')->findAll(); // Get all dates, ordered

        foreach ($evaluationDates as $evaluationDate) {
            $openTime = $evaluationDate['open_datetime'];
            $closeTime = $evaluationDate['close_datetime'];
            if ($now >= $openTime && $now <= $closeTime) {
                return true; // Found an active evaluation period
            }
        }

        return false; // No active evaluation period found
    }
}
