<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\SegmentModel;
use App\Models\UnitModel;

class Job extends BaseController
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

    public function index($id, $unitid)
    {
        $data = [
            'judul' => "Detail Pekerjaan",
            'job' => $this->jobModel->getJobDetail($id),
            'jobList' => $this->jobModel->getJobList($unitid),
            'unit' => $this->unitModel->getUnitDetail($unitid)
        ];

        return view('job/jobDetail', $data);
    }

    public function editJob()
    {
        $this->jobModel->save([
            'job_id' => $this->request->getVar('job_id'),
            'name' => $this->request->getVar('name'),
            'progress' => $this->request->getVar('progress'),
            'duedate' => $this->request->getVar('duedate'),
            'unit_id' => $this->request->getVar('unit_id')
        ]);

        $unitProgress = $this->jobModel->getJobProgress($this->request->getVar('unit_id'));

        $this->unitModel->save([
            'unit_id' => $this->request->getVar('unit_id'),
            'progress' => $unitProgress
        ]);

        $segmentProgress = $this->unitModel->getUnitProgress($this->request->getVar('segment_id'));

        $this->segmentModel->save([
            'segment_id' => $this->request->getVar('segment_id'),
            'progress' => $segmentProgress
        ]);
        // dd($unitProgress);
        // print_r(array_reduce($array, "own_function", "2"));

        session()->setFlashdata('pesan', 'Pekerjaan berhasil diubah');

        return redirect()->back();
    }
}
