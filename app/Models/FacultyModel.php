<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultyModel extends Model
{
    protected $table      = 'faculty_list';
    protected $primaryKey = 'id';
    protected $allowedFields = ['faculty_id', 'full_name', 'phoneNumber', 'gender', 'email', 'passwordHash', 'college', 'department', 'is_active', 'reset_token', 'token_created_at', 'email_verified', 'verification_token'];
}
