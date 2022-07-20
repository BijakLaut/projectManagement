<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table = "job";
    protected $primaryKey = 'job_id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'progress', 'status', 'duedate', 'unit_id', 'completion_date'];
    protected $db, $builder;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('job');
    }

    public function getJobList($unitId)
    {
        $sql = "SELECT * FROM job WHERE unit_id = $unitId ORDER BY `duedate` ASC";

        $query = $this->db->query($sql);

        return $query->getResult('array');
    }

    public function getJobDetail($id)
    {
        // Ambil berbagai tanggal dan ubah
        $jobDetail = $this->builder->select('*')->where('job_id', $id)->get()->getRowArray();
        $duedateDisplay = date('Y-m-d', strtotime($jobDetail['duedate']));
        $jobDetail['duedate'] = $duedateDisplay;

        // Hitung selisih untuk Sisa Waktu dan Waktu Penyelesaian
        if ($jobDetail['status'] == 'Selesai') {
            $duedate = strtotime($jobDetail['duedate']);
            $compdate = strtotime($jobDetail['completion_date']);
            $datediff = ceil(($compdate - $duedate) / 60 / 60 / 24);
        } else {
            $timeNow = strtotime('now');
            $duedate = strtotime($jobDetail['duedate']);
            $datediff = ceil(($duedate - $timeNow) / 60 / 60 / 24);
        }

        $jobDetail += [
            'datediff' => $datediff,
        ];

        return $jobDetail;
    }

    public function getJobProgress($unitId)
    {
        $this->builder->select('progress')->where('unit_id', $unitId);
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
