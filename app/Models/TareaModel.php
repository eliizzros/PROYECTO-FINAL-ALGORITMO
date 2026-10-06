<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model
{
    protected $table = 'tasks';

    protected $allowedFields = [
        'description'
    ];
}