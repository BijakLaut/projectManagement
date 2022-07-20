<?php

namespace App\Controllers;

use App\Models\FileModel;

class FileManager extends BaseController
{
    protected $fileModel, $validation, $db, $builder;

    public function __construct()
    {
        $this->fileModel = new FileModel();
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('file');
    }

    public function uploadAttachment($parent, $parentid = 0)
    {
        // Breadcrumb
        if ($parent == 'unit') {
            $this->builder = $this->db->table($parent);
            $breadcrumb = $this->builder->select('unit_id, code, segment_id')->where('unit_id', $parentid)->get()->getRowArray();
        } elseif ($parent == 'job') {
            $this->builder = $this->db->table($parent);
            $this->builder->select('unit.unit_id as unit_id, unit.segment_id as segment_id, code, job_id, job.name as job_name');
            $this->builder->join('unit', 'unit.unit_id = job.unit_id');
            $breadcrumb = $this->builder->where('job_id', $parentid)->get()->getRowArray();
        } else {
            $breadcrumb = 'project';
        }

        // Ambil record file sesuai parent
        $this->builder->resetQuery();
        $this->builder = $this->db->table('file');
        $where = ['parent' => $parent, 'parent_id' => $parentid];
        $attachments = $this->builder->select('*')->where($where)->get()->getResultObject();

        $data = [
            'judul' => 'File Lampiran',
            'breadcrumb' => $breadcrumb,
            'parent' => ['parent' => $parent, 'parent_id' => $parentid],
            'validation' => \Config\Services::validation(),
            'attachments' => $attachments,
        ];

        return view('filemanager/uploadAttachment', $data);
    }

    public function uploadFile()
    {
        // Validation Rules
        $fields = [
            'originalname' => [
                'rules' => 'required|alpha_numeric_space|min_length[5]|max_length[100]|is_unique[file.original_name]',
                'errors' => [
                    'required' => 'Nama file tidak boleh kosong',
                    'alpha_numeric_space' => 'Nama file hanya boleh mengandung huruf, angka, dan spasi',
                    'min_length' => 'Nama file tidak boleh kurang dari 5 karakter',
                    'max_length' => 'Nama file tidak boleh lebih dari 100 karakter',
                    'is_unique' => 'Nama file sudah terpakai, silakan gunakan nama lain',
                ]
            ],
            'type' => [
                'rules'     => 'alpha',
                'errors'    => [
                    'alpha'  => 'Harap pilih salah satu jenis file'
                ]
            ],

        ];

        if ($this->request->getPost('type') == 'gambar') {
            $fields['file'] = [
                'rules' => 'uploaded[file]|mime_in[file,image/png,image/jpg,image/jpeg]|max_size[file,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PNG/JPG/JPEG',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
        } else if ($this->request->getPost('type') == 'dokumen') {
            $fields['file'] = [
                'rules' => 'uploaded[file]|mime_in[file,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]|max_size[file,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PDF/DOC/DOCX',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
        }

        // Lakukan validasi
        if (!$this->validate($fields)) {
            // Kembali jika terdapat error
            return redirect()->back()->withInput();
        } else {
            // Proses save record dan upload file
            // Ambil berbagai atribut file
            $userFile = $this->request->getFile('file');
            $filename = $userFile->getRandomName();
            $fileExt = $userFile->getExtension();
            $originalName = $this->request->getPost('originalname') . '.' . $fileExt;

            // Save record
            $this->fileModel->save([
                'filename' => $filename,
                'original_name' => $originalName,
                'parent' => $this->request->getPost('parent'),
                'parent_id' => $this->request->getPost('parentid'),
                'author_id' => $this->request->getPost('authorid'),
                'type' => $this->request->getPost('type'),
                'extension' => $fileExt
            ]);

            // Pindahkan file ke direktori permanen
            $userFile->move('assets/attachments/', $filename);

            // Redirect dan kirimkan pesan
            session()->setFlashdata('success', 'File berhasil diunggah');

            return redirect()->back();
        }
    }

    public function toDownload($fileid)
    {
        $file = $this->fileModel->find($fileid);

        return $this->response->download("assets/attachments/" . $file['filename'], null)->setFileName($file['original_name']);
    }

    public function deleteFile($id)
    {
        $file = $this->fileModel->find($id);

        $this->fileModel->delete($id);
        unlink('assets/attachments/' . $file['filename']);

        session()->setFlashdata('deleted', 'File berhasil dihapus');

        return redirect()->back();
    }
}
