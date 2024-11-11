<?php
namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'department_id';
    protected $allowedFields = ['college_id', 'department_name'];

    // Method to fetch all departments for a specific college
    public function getDepartmentsByCollege($collegeId)
    {
        // Fetch departments for a specific college
        return $this->select('department.department_id, department.department_name')
                    ->where('college_id', $collegeId)  // Filter departments by the provided college ID
                    ->findAll();  // Return all departments for the selected college
    }

    // Optional: If you want to fetch departments with college info
    public function getAllDepartments()
    {
        return $this->select('department.department_id, department.department_name, college.college_name')
                    ->join('college', 'college.college_id = department.college_id', 'left')  // Adjust the join condition based on your schema
                    ->findAll();  // Fetch all departments with associated college data
    }
}
