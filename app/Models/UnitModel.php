<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table = "unit";
    protected $primaryKey = 'unit_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'code', 'duedate', 'segment_id'];

    public function getUnitList($segmentId = false)
    {
        if ($segmentId) {
            $unitList = $this->where('segment_id', $segmentId)->findAll();

            for ($i = 0; $i < count($unitList); $i++) {
                $duedateDisplay = date('Y-m-d', strtotime($unitList[$i]['duedate']));

                $unitList[$i]['duedate'] = $duedateDisplay;
                return $unitList;
            }
        }

        $unitList = $this->findAll();

        for ($i = 0; $i < count($unitList); $i++) {
            $duedateDisplay = date('Y-m-d', strtotime($unitList[$i]['duedate']));

            $unitList[$i]['duedate'] = $duedateDisplay;
            return $unitList;
        }
    }

    public function getUnitDetail($id)
    {
        $unitDetail = $this->find($id);
        $timeNow = strtotime('now');
        $duedateDisplay = date('d M Y', strtotime($unitDetail['duedate']));

        $duedate = strtotime($unitDetail['duedate']);
        $datediff = ($duedate - $timeNow) / 60 / 60 / 24;
        $datediff = ceil($datediff);

        $unitDetail['duedate'] = $duedateDisplay;
        $unitDetail += [
            'datediff' => $datediff,
        ];

        return $unitDetail;
    }
}
