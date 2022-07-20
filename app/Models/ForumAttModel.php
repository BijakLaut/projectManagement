<?php

namespace App\Models;

use CodeIgniter\Model;

class ForumAttModel extends Model
{
    protected $table = "forum_attachment";
    protected $primaryKey = 'att_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'parent_id', 'parent'];
}
