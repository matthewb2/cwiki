<?php

namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    // updated_at을 allowedFields에 추가해야 합니다!
    protected $allowedFields = ['title', 'updated_at'];
}