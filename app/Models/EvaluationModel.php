<?php 
namespace App\Models;

use CodeIgniter\Model;

class EvaluationModel extends Model
{
    protected $table = 'evaluation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id', 'faculty_id', 'academic_id', 'comment'];

    public function getEvaluationByStudentAndFaculty($student_id, $faculty_id, $academic_id)
    {
        return $this->where([
            'student_id' => $student_id,
            'faculty_id' => $faculty_id,
            'academic_id' => $academic_id
        ])->first();
    }

    public function createEvaluation($data)
    {
        return $this->insert($data);
    }
}
