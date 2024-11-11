<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluationQuestionModel extends Model
{
    protected $table = 'evaluation_question';
    protected $primaryKey = 'id';
    protected $allowedFields = ['criteria_id', 'question_text'];
}
