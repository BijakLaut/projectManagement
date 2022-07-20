<?php

namespace App\Models;

use CodeIgniter\Model;

class ReplyModel extends Model
{
    protected $table = "reply";
    protected $primaryKey = 'reply_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['forum_parent', 'description', 'author_id', 'has_attachment'];
}
