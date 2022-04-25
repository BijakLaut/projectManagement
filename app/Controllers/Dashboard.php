<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\SegmentModel;
use App\Models\UnitModel;

class Dashboard extends BaseController
{
    protected $jobModel;
    protected $segmentModel;
    protected $unitModel;

    public function __construct()
    {
        $this->segmentModel = new SegmentModel();
        $this->unitModel = new UnitModel();
        $this->jobModel = new JobModel();
    }

    public function index()
    {
        $data = [
            "judul" => "Proyek Apartemen Uhuy",
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
            'duedate' => $this->request->getVar('duedate'),
            'segment_id' => $this->request->getVar('segment_id')
        ]);

        session()->setFlashdata('pesan', 'Unit berhasil ditambah');

        return redirect()->to('/');
    }

    public function checkInput()
    {
        $validation = \Config\Services::validation();
        $fields = [];

        if ($_POST['form_id'] == 'addUnit') {
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
        } else if ($_POST['form_id'] == 'addSegment') {
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
        } else if ($_POST['form_id'] == 'addJob') {
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
        } else if ($_POST['form_id'] == 'editUnit') {
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
                'duedate' => [
                    'rules'     => 'required',
                    'errors'    => [
                        'required' => 'Target Penyelesaian tidak boleh kosong'
                    ]
                ]
            ];
        }

        $this->validate($fields);

        if (true) {
            return json_encode(['errors' => $validation->getErrors()]);
        }
    }

    public function editUnit()
    {
        $this->unitModel->save([
            'unit_id' => $this->request->getVar('unit_id'),
            'name' => $this->request->getVar('name'),
            'code' => $this->request->getVar('code'),
            'duedate' => $this->request->getVar('duedate'),
            'segment_id' => $this->request->getVar('segment_id')
        ]);

        session()->setFlashdata('pesan', 'Unit berhasil diubah');

        return redirect()->to('/');
    }

    public function delete($model, $id)
    {
        if ($model == 'segment') {
            $this->segmentModel->delete($id);
        } else if ($model == 'unit') {
            $this->unitModel->delete($id);
        } else if ($model == 'job') {
            $this->jobModel->delete($id);
        }

        session()->setFlashdata('hapus', ucfirst($model) . ' berhasil dihapus');
        return redirect()->to('/');
    }
}
