<?php 
namespace App\Models;

use CodeIgniter\Model;

class EvaluationAnswerModel extends Model
{
    protected $table = 'evaluation_answer';
    protected $primaryKey = ['evaluation_id', 'evaluation_question_id'];
    protected $allowedFields = ['evaluation_id', 'evaluation_question_id', 'rating_id'];

    // Relationships
    public function getEvaluationAnswers($evaluation_id)
    {
        return $this->where('evaluation_id', $evaluation_id)->findAll();
    }

    public function createEvaluationAnswer($data)
    {
        return $this->insertBatch($data); // Insert multiple answers at once
    }
}
