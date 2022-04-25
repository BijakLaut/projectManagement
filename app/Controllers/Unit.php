<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\SegmentModel;
use App\Models\UnitModel;

class Unit extends BaseController
{
    protected $segmentModel;
    protected $unitModel;
    protected $jobModel;

    public function __construct()
    {
        $this->jobModel = new JobModel();
        $this->segmentModel = new SegmentModel();
        $this->unitModel = new UnitModel();
    }

    public function index($id, $segmentId)
    {
        $data = [
            "judul" => "Detail Ruangan",
            'segment' => $this->segmentModel->segmentDetail($segmentId),
            'unitList' => $this->unitModel->getUnitList($segmentId),
            'unit' => $this->unitModel->getUnitDetail($id),
            'jobList' => $this->jobModel->getJobList($id)
        ];

        return view('unit/unitDetail', $data);
    }

    public function addJob($id, $segmentId)
    {
        // dd($this->request->getVar());
        $this->jobModel->save([
            'name' => $this->request->getVar('name'),
            'duedate' => $this->request->getVar('duedate'),
            'unit_id' => $this->request->getVar('unitid')
        ]);

        session()->setFlashdata('pesan', 'Pekerjaan berhasil ditambah');

        return redirect()->to("/unitDetail/$id/$segmentId");
    }


    // public function addUnit()
    // {
    //     $this->unitModel->save([
    //         'name' => $this->request->getVar('name'),
    //         'duedate' => $this->request->getVar('duedate'),
    //         'segment_id' => $this->request->getVar('segmentid')
    //     ]);

    //     session()->setFlashdata('pesan', 'Unit berhasil ditambah');

    //     return redirect()->to('/');
    // }
}
