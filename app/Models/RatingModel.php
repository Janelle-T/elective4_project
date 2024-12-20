<?php

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'id';
    protected $allowedFields = ['rate', 'descriptive_rating', 'qualitative_description'];
}
