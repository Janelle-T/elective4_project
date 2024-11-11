<?php

namespace App\Models;

use CodeIgniter\Model;

class AcademicModel extends Model
{
    protected $table = 'academic';
    protected $primaryKey = 'id';
    protected $allowedFields = ['school_year', 'semester',  'status'];
    protected $useTimestamps = true;
}
