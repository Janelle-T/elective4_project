<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin'; // The name of your database table
    protected $primaryKey = 'id'; // The primary key of the table
    protected $allowedFields = ['full_name', 'email', 'passwordHash', 'verification_token', 'email_verified']; // Updated fields for insertion

    // Validation rules for data validation
    protected $validationRules = [
        'full_name' => 'required|min_length[2]',
        'email' => 'required|valid_email|is_unique[admin.email]', // Update to match the correct table name
        'passwordHash' => 'required|min_length[8]', // Updated to match the hashed password field
    ];

    // Custom validation messages
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already registered.',
        ],
        'full_name' => [
            'required' => 'Full name is required.',
            'min_length' => 'Full name must be at least 2 characters long.',
        ],
        'passwordHash' => [
            'required' => 'Password is required.',
            'min_length' => 'Password must be at least 8 characters long.',
        ],
    ];

    // You can also add other methods for specific functionalities, if needed
}
