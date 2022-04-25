<?php

namespace App\Models;

use CodeIgniter\Model;

class SegmentModel extends Model
{
    protected $table = "segment";
    protected $primaryKey = 'segment_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'duedate'];

    public function includeRemainingTime()
    {
        $segments = $this->findAll();

        $timeNow = strtotime('now');

        for ($i = 0; $i < count($segments); $i++) {
            $duedate = strtotime($segments[$i]['duedate']);
            $datediff = ($duedate - $timeNow) / 60 / 60 / 24;
            $datediff = ceil($datediff);
            $segments[$i] += ['datediff' => $datediff];
        }

        return $segments;
    }

    public function segmentDetail($id)
    {
        $segment = $this->find($id);

        return $segment;
    }
}
