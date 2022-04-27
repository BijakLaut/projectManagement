<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table = "job";
    protected $primaryKey = 'job_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'progress', 'duedate', 'unit_id'];

    public function getJobList($unitId)
    {
        $sql = "SELECT * FROM job WHERE unit_id = $unitId ORDER BY `duedate` ASC";

        $query = $this->db->query($sql);

        return $query->getResult('array');
    }

    public function getJobDetail($id)
    {
        $jobDetail = $this->find($id);

        $timeNow = strtotime('now');
        $duedateDisplay = date('Y-m-d', strtotime($jobDetail['duedate']));

        $duedate = strtotime($jobDetail['duedate']);
        $datediff = ($duedate - $timeNow) / 60 / 60 / 24;
        $datediff = ceil($datediff);

        $jobDetail['duedate'] = $duedateDisplay;
        $jobDetail += [
            'datediff' => $datediff,
        ];

        return $jobDetail;
    }

    public function getJobProgress($unitId)
    {
        $sql = "SELECT progress FROM job WHERE unit_id = $unitId";

        $query = $this->db->query($sql);

        $result = $query->getResult('array');

        $unpacks = [];
        for ($i = 0; $i < count($result); $i++) {
            $unpacks += [
                $i => $result[$i]['progress']
            ];
        }

        $result = array_reduce($unpacks, function ($acc, $curr) {
            $acc = $acc + $curr;
            return $acc;
        }, 0) / count($unpacks);

        return $result;
    }
}
