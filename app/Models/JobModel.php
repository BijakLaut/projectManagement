<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table = "job";
    protected $primaryKey = 'job_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'duedate', 'unit_id'];

    public function getJobList($unitId)
    {
        $sql = "SELECT * FROM job WHERE unit_id = $unitId ORDER BY `duedate` ASC";

        $query = $this->db->query($sql);

        return $query->getResult('array');

        // $jobList = $this->where('unit_id', $unitId)->findAll();

        // return $jobList;
    }

    public function getJobDetail($id)
    {
        $jobDetail = $this->find($id);

        $timeNow = strtotime('now');
        $duedateDisplay = date('d M Y', strtotime($jobDetail['duedate']));

        $duedate = strtotime($jobDetail['duedate']);
        $datediff = ($duedate - $timeNow) / 60 / 60 / 24;
        $datediff = ceil($datediff);

        $jobDetail['duedate'] = $duedateDisplay;
        $jobDetail += [
            'datediff' => $datediff,
        ];

        return $jobDetail;
    }
}
