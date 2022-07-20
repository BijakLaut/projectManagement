<?php

namespace App\Models;

use CodeIgniter\Model;

class ForumModel extends Model
{
    protected $table = "forum";
    protected $primaryKey = 'forum_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['topic', 'description', 'parent_id', 'author_id', 'has_attachment'];
}
