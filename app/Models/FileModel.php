<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table = 'file';
    protected $primaryKey = 'file_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['filename', 'original_name', 'parent', 'parent_id', 'author_id', 'type', 'extension'];
}
