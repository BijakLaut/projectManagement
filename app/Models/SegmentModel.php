<?php

namespace App\Models;

use CodeIgniter\Model;

class SegmentModel extends Model
{
    protected $table = "segment";
    protected $primaryKey = 'segment_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'progress', 'status', 'duedate', 'completion_date'];

    public function includeRemainingTime()
    {
        $segments = $this->findAll();

        foreach ($segments as $int => $segment) {
            if ($segment['status'] == 'Selesai') {
                $duedate = strtotime($segment['duedate']);
                $compdate = strtotime($segment['completion_date']);
                $datediff = ceil(($compdate - $duedate) / 60 / 60 / 24);
            } else {
                $timeNow = strtotime('now');
                $duedate = strtotime($segment['duedate']);
                $datediff = ceil(($duedate - $timeNow) / 60 / 60 / 24);
            }

            $segments[$int] += [
                'datediff' => $datediff,
            ];
        }
        // for ($i = 0; $i < count($segments); $i++) {
        //     $duedate = strtotime($segments[$i]['duedate']);
        //     $datediff = ($duedate - $timeNow) / 60 / 60 / 24;
        //     $datediff = ceil($datediff);
        //     $segments[$i] += ['datediff' => $datediff];
        // }

        return $segments;
    }

    public function segmentDetail($id)
    {
        $segment = $this->find($id);

        return $segment;
    }
}
