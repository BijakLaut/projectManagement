<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table = "unit";
    protected $primaryKey = 'unit_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'code', 'progress', 'status', 'duedate', 'segment_id', 'completion_date'];
    protected $db, $builder;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('unit');
    }

    public function getUnitList($segmentId = false)
    {
        if ($segmentId) {
            $unitList = $this->builder->select('*')->where('segment_id', $segmentId)->get()->getResultArray();

            for ($i = 0; $i < count($unitList); $i++) {
                $duedateDisplay = date('Y-m-d', strtotime($unitList[$i]['duedate']));

                $unitList[$i]['duedate'] = $duedateDisplay;
                return $unitList;
            }
        }

        $this->builder->resetQuery();
        $unitList = $this->builder->select('*')->get()->getResultArray();

        for ($i = 0; $i < count($unitList); $i++) {
            $duedateDisplay = date('Y-m-d', strtotime($unitList[$i]['duedate']));

            $unitList[$i]['duedate'] = $duedateDisplay;
            return $unitList;
        }
    }

    public function getUnitDetail($id)
    {
        $unitDetail = $this->builder->select('*')->where('unit_id', $id)->get()->getRowArray();
        $duedateDisplay = date('d M Y', strtotime($unitDetail['duedate']));
        $unitDetail['duedate'] = $duedateDisplay;

        // Hitung selisih untuk Sisa Waktu dan Waktu Penyelesaian
        if ($unitDetail['status'] == 'Selesai') {
            $duedate = strtotime($unitDetail['duedate']);
            $compdate = strtotime($unitDetail['completion_date']);
            $datediff = ceil(($compdate - $duedate) / 60 / 60 / 24);
        } else {
            $timeNow = strtotime('now');
            $duedate = strtotime($unitDetail['duedate']);
            $datediff = ceil(($duedate - $timeNow) / 60 / 60 / 24);
        }

        $unitDetail += [
            'datediff' => $datediff,
        ];

        return $unitDetail;
    }

    public function getUnitProgress($segmentId)
    {
        $this->builder->select('progress')->where('segment_id', $segmentId);
        $results = $this->builder->get()->getResultArray();

        $unpacks = [];
        foreach ($results as $int => $result) {
            $unpacks += [$int => $result['progress']];
        }

        $result = array_reduce($unpacks, function ($acc, $curr) {
            $acc = $acc + $curr;
            return $acc;
        }, 0) / count($unpacks);

        return $result;
    }
}
