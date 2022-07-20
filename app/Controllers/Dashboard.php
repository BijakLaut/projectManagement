<?php

namespace App\Controllers;

use App\Models\FileModel;
use App\Models\JobModel;
use App\Models\SegmentModel;
use App\Models\UnitModel;

class Dashboard extends BaseController
{
    protected $jobModel, $segmentModel, $unitModel, $fileModel, $db, $builder, $forum;

    public function __construct()
    {
        $this->segmentModel = new SegmentModel();
        $this->unitModel = new UnitModel();
        $this->jobModel = new JobModel();
        $this->fileModel = new FileModel();
        $this->db = \Config\Database::connect();
        $this->forum = new Forum;
    }

    public function index()
    {
        $data = [
            'judul' => "Proyek Apartemen Uhuy",
            'segments' => $this->segmentModel->includeRemainingTime(),
            'units' => $this->unitModel->getUnitList(),
            'validation' => \Config\Services::validation()
        ];

        return view('dashboard/index', $data);
    }

    public function addSegment()
    {
        $this->segmentModel->save([
            'name' => $this->request->getVar('name'),
            'status' => $this->request->getVar('status'),
            'duedate' => $this->request->getVar('duedate')
        ]);

        session()->setFlashdata('pesan', 'Segmen berhasil ditambah');

        return redirect()->to('/');
    }

    public function addUnit()
    {
        $this->unitModel->save([
            'name' => $this->request->getVar('name'),
            'code' => $this->request->getVar('code'),
            'status' => $this->request->getVar('status'),
            'duedate' => $this->request->getVar('duedate'),
            'segment_id' => $this->request->getVar('segment_id')
        ]);

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

        session()->setFlashdata('pesan', 'Unit berhasil ditambah');

        return redirect()->to('/');
    }

    public function checkInput()
    {
        $validation = \Config\Services::validation();
        $fields = [];

        if ($this->request->getPost('form_id') == 'addUnit') {
            $fields = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Unit tidak boleh kosong'
                    ]
                ],
                'code' => [
                    'rules'     => 'required|is_unique[unit.code]',
                    'errors'    => [
                        'required'  => 'Kode Unit tidak boleh kosong',
                        'is_unique' => 'Kode Unit harus unik'
                    ]
                ],
                'duedate' => [
                    'rules'     => 'required',
                    'errors'    => [
                        'required' => 'Target Penyelesaian tidak boleh kosong'
                    ]
                ]
            ];
        } else if ($this->request->getPost('form_id') == 'addSegment') {
            $fields = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Segmen tidak boleh kosong'
                    ]
                ],
                'duedate' => [
                    'rules'     => 'required',
                    'errors'    => [
                        'required' => 'Target Penyelesaian tidak boleh kosong'
                    ]
                ]
            ];
        } else if ($this->request->getPost('form_id') == 'addJob') {
            $fields = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Pekerjaan tidak boleh kosong'
                    ]
                ],
                'duedate' => [
                    'rules'     => 'required',
                    'errors'    => [
                        'required' => 'Target Penyelesaian tidak boleh kosong'
                    ]
                ]
            ];
        } else if ($this->request->getPost('form_id') == 'editUnit') {
            $unitLama = $this->unitModel->getUnitDetail($this->request->getVar('unit_id'));

            if ($unitLama['code'] == $this->request->getVar('code')) {
                $codeRule = 'required';
            } else {
                $codeRule = 'required|is_unique[unit.code]';
            }

            $fields = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Unit tidak boleh kosong'
                    ]
                ],
                'code' => [
                    'rules'     => $codeRule,
                    'errors'    => [
                        'required'  => 'Kode Unit tidak boleh kosong',
                        'is_unique' => 'Kode Unit harus unik'
                    ]
                ],
                'status' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap pilih salah satu Status',
                    ]
                ],
                'duedate' => [
                    'rules'     => 'required',
                    'errors'    => [
                        'required' => 'Target Penyelesaian tidak boleh kosong'
                    ]
                ]
            ];
        } else if ($this->request->getPost()['form_id'] == 'editJob') {
            $fields = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Pekerjaan tidak boleh kosong'
                    ]
                ],
                'progress' => [
                    'rules' => 'required|greater_than_equal_to[0]|less_than_equal_to[100]',
                    'errors' => [
                        'required' => 'Progress Pekerjaan tidak boleh kosong',
                        'greater_than_equal_to' => 'Progress Pekerjaan tidak boleh kurang dari {param}',
                        'less_than_equal_to' => 'Progress Pekerjaan tidak boleh lebih dari {param}'
                    ]
                ],
                'status' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap pilih salah satu Status',
                    ]
                ],
                'duedate' => [
                    'rules'     => 'required',
                    'errors'    => [
                        'required' => 'Target Penyelesaian tidak boleh kosong'
                    ]
                ]
            ];
        } else if ($this->request->getPost()['form_id'] == 'editSegment') {
            $fields = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Segmen tidak boleh kosong'
                    ]
                ],
                'status' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap pilih salah satu Status',
                    ]
                ],
                'duedate' => [
                    'rules'     => 'required',
                    'errors'    => [
                        'required' => 'Target Penyelesaian tidak boleh kosong'
                    ]
                ]
            ];
        }

        $this->validate($fields);

        return json_encode(['errors' => $validation->getErrors()]);
    }

    public function editUnit()
    {
        $this->unitModel->save([
            'unit_id' => $this->request->getVar('unit_id'),
            'name' => $this->request->getVar('name'),
            'code' => $this->request->getVar('code'),
            'status' => $this->request->getVar('status'),
            'duedate' => $this->request->getVar('duedate'),
            'segment_id' => $this->request->getVar('segment_id')
        ]);

        session()->setFlashdata('pesan', 'Unit berhasil diubah');

        return redirect()->to('/');
    }

    public function editSegment()
    {
        $this->segmentModel->save([
            'segment_id' => $this->request->getVar('segment_id'),
            'name' => $this->request->getVar('name'),
            'status' => $this->request->getVar('status'),
            'duedate' => $this->request->getVar('duedate'),
        ]);

        session()->setFlashdata('pesan', 'Segmen berhasil diubah');

        return redirect()->to('/');
    }

    public function delete($model, $id)
    {
        if ($model == 'segment') {
            // Ambil record Unit
            $this->builder = $this->db->table('unit');
            $units = $this->builder->select('unit_id')->where('segment_id', $id)->get()->getResultArray();

            // Ambil record job
            $this->builder->resetQuery();
            $this->builder = $this->db->table('job');
            $jobs = $this->builder->select('job_id, unit_id')->get()->getResultArray();

            // Looping
            foreach ($units as $unit) {
                // Looping per Unit
                foreach ($jobs as $job) {
                    // Jika parent pada Job sesuai
                    if ($job['unit_id'] == $unit['unit_id']) {
                        // Ambil record attachment yang dimiliki Job
                        $this->builder->resetQuery();
                        $this->builder = $this->db->table('file');
                        $where = ['parent' => 'job', 'parent_id' => $job['job_id']];
                        $attachments = $this->builder->select('*')->where($where)->get()->getResultArray();

                        // Ambil record Forum 
                        $this->builder->resetQuery();
                        $this->builder = $this->db->table('forum');
                        $forums = $this->builder->select('*')->where('parent_id', $job['job_id'])->get()->getResultArray();

                        // Hapus attachment
                        foreach ($attachments as $attachment) {
                            $this->fileModel->delete($attachment['file_id']);
                            unlink('assets/attachments/' . $attachment['filename']);
                        }

                        // Hapus Forum, Reply, dan Attachment terkait
                        foreach ($forums as $forum) {
                            $this->forum->deleteForum($forum['forum_id']);
                        }

                        // Hapus Job
                        $this->jobModel->delete($job['job_id']);
                    }
                }

                // Ambil record attachment milik Unit
                $this->builder->resetQuery();
                $this->builder = $this->db->table('file');
                $where = ['parent' => 'unit', 'parent_id' => $unit['unit_id']];
                $attachments = $this->builder->select('*')->where($where)->get()->getResultArray();

                // Hapus attachment
                foreach ($attachments as $attachment) {
                    $this->fileModel->delete($attachment['file_id']);
                    unlink('assets/attachments/' . $attachment['filename']);
                }

                // Hapus Unit
                $this->unitModel->delete($unit['unit_id']);
            }

            // Hapus Segmen
            $this->segmentModel->delete($id);
        } else if ($model == 'unit') {
            // Ambil record job
            $this->builder = $this->db->table('job');
            $jobs = $this->builder->select('job_id, unit_id')->where('unit_id', $id)->get()->getResultArray();

            foreach ($jobs as $job) {
                // Ambil record attachment yang dimiliki Job
                $this->builder->resetQuery();
                $this->builder = $this->db->table('file');
                $where = ['parent' => 'job', 'parent_id' => $job['job_id']];
                $attachments = $this->builder->select('*')->where($where)->get()->getResultArray();

                // Hapus attachment
                foreach ($attachments as $attachment) {
                    $this->fileModel->delete($attachment['file_id']);
                    unlink('assets/attachments/' . $attachment['filename']);
                }

                // Ambil record Forum 
                $this->builder->resetQuery();
                $this->builder = $this->db->table('forum');
                $forums = $this->builder->select('*')->where('parent_id', $job['job_id'])->get()->getResultArray();

                // Hapus Forum, Reply, dan Attachment terkait
                foreach ($forums as $forum) {
                    $this->forum->deleteForum($forum['forum_id']);
                }

                // Hapus Job
                $this->jobModel->delete($job['job_id']);
            }

            // Ambil id segmen (parent) dari unit
            $this->builder->resetQuery();
            $this->builder = $this->db->table('unit');
            $segment_id = $this->builder->select('segment_id')->where('unit_id', $id)->get()->getRowArray();

            // Hapus Unit
            $this->unitModel->delete($id);

            // Penyesuaian progress dan status segmen
            $this->builder->resetQuery();
            $this->builder = $this->db->table('segment');
            $segment = $this->builder->select('status')->where('segment_id', $segment_id)->get()->getRowArray();
            $segmentProgress = $this->unitModel->getUnitProgress($segment_id);

            // Jika progress = 100 dan status = Berjalan
            if ($segmentProgress == 100 && $segment['status'] == 'Berjalan') {
                $this->segmentModel->save([
                    'segment_id' => $segment_id,
                    'progress' => $segmentProgress,
                    'status' => 'Selesai',
                    'completion_date' => date('Y-m-d'),
                ]);
            } else if ($segmentProgress < 100 && $segment['status'] == 'Selesai') {
                $this->segmentModel->save([
                    'segment_id' => $segment_id,
                    'progress' => $segmentProgress,
                    'status' => 'Berjalan',
                    'completion_date' => null,
                ]);
            } else {
                $this->segmentModel->save([
                    'segment_id' => $segment_id,
                    'progress' => $segmentProgress,
                    'completion_date' => null,
                ]);
            }
        } else if ($model == 'job') {
            // Ambil record attachment yang dimiliki Job
            $this->builder = $this->db->table('file');
            $where = ['parent' => 'job', 'parent_id' => $id];
            $attachments = $this->builder->select('*')->where($where)->get()->getResultArray();

            // Hapus attachment
            foreach ($attachments as $attachment) {
                $this->fileModel->delete($attachment['file_id']);
                unlink('assets/attachments/' . $attachment['filename']);
            }

            // Ambil record Forum 
            $this->builder->resetQuery();
            $this->builder = $this->db->table('forum');
            $forums = $this->builder->select('*')->where('parent_id', $id)->get()->getResultArray();

            // Hapus Forum, Reply, dan Attachment terkait
            foreach ($forums as $forum) {
                $this->forum->deleteForum($forum['forum_id']);
            }

            // Ambil id unit (parent) dari job
            $this->builder->resetQuery();
            $this->builder = $this->db->table('job');
            $unit_id = $this->builder->select('unit_id')->where('job_id', $id)->get()->getRowArray();

            // Hapus Job
            $this->jobModel->delete($id);

            // Penyesuaian progress dan status unit
            $this->builder->resetQuery();
            $this->builder = $this->db->table('unit');
            $unit = $this->builder->select('status')->where('unit_id', $unit_id)->get()->getRowArray();
            $unitProgress = $this->jobModel->getJobProgress($unit_id);

            // Jika progress = 100 dan status = Berjalan
            if ($unitProgress == 100 && $unit['status'] == 'Berjalan') {
                $this->unitModel->save([
                    'unit_id' => $unit_id,
                    'progress' => $unitProgress,
                    'status' => 'Selesai',
                    'completion_date' => date('Y-m-d'),
                ]);
            } else if ($unitProgress < 100 && $unit['status'] == 'Selesai') {
                $this->unitModel->save([
                    'unit_id' => $unit_id,
                    'progress' => $unitProgress,
                    'status' => 'Berjalan',
                    'completion_date' => null,
                ]);
            } else {
                $this->unitModel->save([
                    'unit_id' => $unit_id,
                    'progress' => $unitProgress,
                    'completion_date' => null,
                ]);
            }
        }

        session()->setFlashdata('hapus', ucfirst($model) . ' berhasil dihapus');
        return redirect()->back();
    }
}
