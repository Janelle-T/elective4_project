<?php

namespace App\Models;

use CodeIgniter\Model;

class CollegeModel extends Model
{
    protected $table      = 'college';  // Table name
    protected $primaryKey = 'college_id';  // Primary key is college_id
    protected $allowedFields = ['college_name'];

    // Method to delete a college by its college_id
    public function deleteCollegeById($college_id)
    {
        // Ensure the college_id is valid before proceeding
        if (!$college_id) {
            log_message('error', 'Invalid college_id: ' . $college_id);  // Log if the ID is invalid
            return false;
        }

        // Perform the delete operation
        $builder = $this->db->table($this->table);
        $builder->where($this->primaryKey, $college_id);  // Use the correct primary key
        return $builder->delete();  // Returns true if deleted, false if not
    }

    // Method to update a college by its college_id
    public function updateCollege($college_id, $data)
    {
        // Ensure the college_id and data are valid before proceeding
        if (!$college_id || !$data || !isset($data['college_name'])) {
            log_message('error', 'Invalid update parameters. ID: ' . $college_id . ', Data: ' . json_encode($data));
            return false;
        }

        // Perform the update operation
        $builder = $this->db->table($this->table);
        $builder->where($this->primaryKey, $college_id);  // Use the correct primary key
        return $builder->update($data);  // Returns true if updated, false if not
    }
}
