<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table      = 'student_list';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id', 'full_name', 'phoneNumber', 'gender', 'email', 'passwordHash',  'reset_token', 'token_created_at', 'email_verified', 'verification_token'];
}
