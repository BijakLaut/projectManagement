<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\SegmentModel;
use App\Models\UnitModel;

class Job extends BaseController
{
    protected $segmentModel, $unitModel, $jobModel, $db, $builder;

    public function __construct()
    {
        $this->jobModel = new JobModel();
        $this->segmentModel = new SegmentModel();
        $this->unitModel = new UnitModel();
        $this->db = \Config\Database::connect();
    }

    public function index($jobid, $unitid)
    {
        $this->builder = $this->db->table('forum');
        $this->builder->select('forum.*, users.id as user_id, username, fullname, user_image');
        $this->builder->join('users', 'users.id = forum.author_id')->where('parent_id', $jobid);
        $forums = $this->builder->get()->getResultArray();

        $this->builder->resetQuery();
        $this->builder->select('forum_id, reply_id, forum_parent');
        $this->builder->join('reply', 'reply.forum_parent = forum.forum_id');
        $replies = $this->builder->where('forum.parent_id', $jobid)->get()->getResultArray();

        $data = [
            'judul' => "Detail Pekerjaan",
            'job' => $this->jobModel->getJobDetail($jobid),
            'jobList' => $this->jobModel->getJobList($unitid),
            'unit' => $this->unitModel->getUnitDetail($unitid),
            'forums' => $forums,
            'replies' => $replies,
        ];

        return view('job/jobDetail', $data);
    }

    public function editJob()
    {
        // Ambil Job sesuai id
        $this->builder = $this->db->table('job');
        $job = $this->builder->select('status')->where('job_id', $this->request->getPost('job_id'))->get()->getRowArray();

        // Masukkan semua input ke dalam row job
        // Jika progress = 100 dan Status = Berjalan
        if ($this->request->getPost('progress') == 100 && $job['status'] == 'Berjalan') {
            $this->jobModel->save([
                'job_id' => $this->request->getVar('job_id'),
                'name' => $this->request->getVar('name'),
                'progress' => $this->request->getVar('progress'),
                'status' => 'Selesai',
                'duedate' => $this->request->getVar('duedate'),
                'completion_date' => date('Y-m-d'),
            ]);
        }

        // Jika progress < 100 dan Status == Selesai (Ubah kembali status menjadi berjalan)
        else if ($this->request->getPost('progress') < 100 && $job['status'] == 'Selesai') {
            $this->jobModel->save([
                'job_id' => $this->request->getVar('job_id'),
                'name' => $this->request->getVar('name'),
                'progress' => $this->request->getVar('progress'),
                'status' => 'Berjalan',
                'duedate' => $this->request->getVar('duedate'),
                'completion_date' => null,
            ]);
        } else {
            $this->jobModel->save([
                'job_id' => $this->request->getPost('job_id'),
                'name' => $this->request->getPost('name'),
                'progress' => $this->request->getPost('progress'),
                'status' => $this->request->getPost('status'),
                'duedate' => $this->request->getPost('duedate'),
                'unit_id' => $this->request->getPost('unit_id'),
                'completion_date' => null,
            ]);
        }

        // $this->jobModel->save([
        //     'job_id' => $this->request->getVar('job_id'),
        //     'name' => $this->request->getVar('name'),
        //     'progress' => $this->request->getVar('progress'),
        //     'duedate' => $this->request->getVar('duedate'),
        // ]);

        // Unit
        // Ambil Status dan Progress Unit
        $this->builder->resetQuery();
        $this->builder = $this->db->table('unit');
        $unit = $this->builder->select('status')->where('unit_id', $this->request->getPost('unit_id'))->get()->getRowArray();
        $unitProgress = $this->jobModel->getJobProgress($this->request->getVar('unit_id'));

        // Penyesuaian akumulasi progress dan status unit
        // Jika progress unit = 100 dan status unit = Berjalan
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

        // Segment
        // Ambil status dan progress segmen
        $this->builder->resetQuery();
        $this->builder = $this->db->table('segment');
        $segment = $this->builder->select('status')->where('segment_id', $this->request->getPost('segment_id'))->get()->getRowArray();
        $segmentProgress = $this->unitModel->getUnitProgress($this->request->getVar('segment_id'));

        // Penyesuaian progress dan status segmen
        // Jika progress = 100 dan status = Berjalan
        if ($segmentProgress == 100 && $segment['status'] == 'Berjalan') {
            $this->segmentModel->save([
                'segment_id' => $this->request->getVar('segment_id'),
                'progress' => $segmentProgress,
                'status' => 'Selesai',
                'completion_date' => date('Y-m-d'),
            ]);
        } else if ($segmentProgress < 100 && $segment['status'] == 'Selesai') {
            $this->segmentModel->save([
                'segment_id' => $this->request->getVar('segment_id'),
                'progress' => $segmentProgress,
                'status' => 'Berjalan',
                'completion_date' => null,
            ]);
        } else {
            $this->segmentModel->save([
                'segment_id' => $this->request->getVar('segment_id'),
                'progress' => $segmentProgress,
                'completion_date' => null,
            ]);
        }

        session()->setFlashdata('pesan', 'Pekerjaan berhasil diubah');

        return redirect()->back();
    }
}
