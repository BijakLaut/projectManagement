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
}
