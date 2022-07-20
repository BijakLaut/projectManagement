<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\SegmentModel;
use App\Models\UnitModel;

class Unit extends BaseController
{
    protected $segmentModel, $unitModel, $jobModel, $db, $builder;

    public function __construct()
    {
        $this->jobModel = new JobModel();
        $this->segmentModel = new SegmentModel();
        $this->unitModel = new UnitModel();
        $this->db = \Config\Database::connect();
    }

    public function index($unitid, $segmentId)
    {
        $data = [
            "judul" => "Detail Ruangan",
            'segment' => $this->segmentModel->segmentDetail($segmentId),
            'unitList' => $this->unitModel->getUnitList($segmentId),
            'unit' => $this->unitModel->getUnitDetail($unitid),
            'jobList' => $this->jobModel->getJobList($unitid)
        ];

        return view('unit/unitDetail', $data);
    }

    public function addJob($id, $segmentId)
    {
        $this->jobModel->save([
            'name' => $this->request->getVar('name'),
            'duedate' => $this->request->getVar('duedate'),
            'unit_id' => $this->request->getVar('unit_id')
        ]);

        $this->builder = $this->db->table('unit');
        $unit = $this->builder->select('status')->where('unit_id', $this->request->getVar('unit_id'))->get()->getRowArray();
        $unitProgress = $this->jobModel->getJobProgress($this->request->getVar('unit_id'));

        // Penyesuaian progress dan status segmen
        // Jika progress = 100 dan status = Berjalan
        if ($unitProgress == 100 && $unit['status'] == 'Berjalan') {
            $this->unitModel->save([
                'unit_id' => $this->request->getVar('unit_id'),
                'progress' => $unitProgress,
                'status' => 'Selesai',
                'completion_date' => date('Y-m-d'),
            ]);
        } else if ($unitProgress < 100 && $unit['status'] == 'Selesai') {
            $this->unitModel->save([
                'unit_id' => $this->request->getVar('unit_id'),
                'progress' => $unitProgress,
                'status' => 'Berjalan',
                'completion_date' => null,
            ]);
        } else {
            $this->unitModel->save([
                'unit_id' => $this->request->getVar('unit_id'),
                'progress' => $unitProgress,
                'completion_date' => null,
            ]);
        }

        session()->setFlashdata('pesan', 'Pekerjaan berhasil ditambah');

        return redirect()->to("/unitDetail/$id/$segmentId");
    }
}
