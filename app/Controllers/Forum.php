<?php

namespace App\Controllers;

use App\Models\ForumAttModel;
use App\Models\ForumModel;
use App\Models\ReplyModel;

class Forum extends BaseController
{
    protected $forumModel, $db, $builder, $forumBuilder, $forumAttModel, $replyModel;

    public function __construct()
    {
        $this->forumModel = new ForumModel();
        $this->forumAttModel = new ForumAttModel();
        $this->replyModel = new ReplyModel();
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('job');
        $this->forumBuilder = $this->db->table('forum');
    }

    public function create($parent_id)
    {
        // Breadcrumb
        $this->builder->select('job_id, job.name as job_name, unit.unit_id as unit_id, unit.code as unit_code, segment.segment_id as segment_id');
        $this->builder->join('unit', 'unit.unit_id = job.unit_id');
        $this->builder->join('segment', 'segment.segment_id = unit.segment_id');
        $this->builder->where('job.job_id', $parent_id);
        $query = $this->builder->get();

        $data = [
            "judul" => "Tambah Forum Diskusi",
            'detail' => $query->getRowArray(),
            'validation' => \Config\Services::validation(),
        ];

        return view('forum/create', $data);
    }

    public function saveForum()
    {
        // Validation Rules
        $fields = [
            'topic' => [
                'rules' => 'required|alpha_numeric_space|max_length[255]',
                'errors' => [
                    'required' => 'Judul topik tidak boleh kosong',
                    'alpha_numeric_space' => 'Judul topik hanya boleh mengandung huruf, angka, dan spasi',
                    'max_length' => 'Judul topik tidak boleh lebih dari 255 karakter'
                ]
            ],
            'description' => [
                'rules'     => 'required|string|max_length[500]',
                'errors'    => [
                    'required' => 'Deskripsi tidak boleh kosong',
                    'string' => 'Terdapat karakter yang tidak sesuai pada Deskripsi',
                    'max_length' => 'Deskripsi tidak boleh lebih dari 500 karakter'
                ]
            ]
        ];

        // Deklarasi status has_attachment, 0 berarti 'tidak', 1 berarti 'ya'
        $has_attachment = 0;

        if ($this->request->getPost('type') == 'gambar') {
            $has_attachment = 1;
            $fields['attachment'] = [
                'rules' => 'uploaded[attachment]|mime_in[attachment,image/png,image/jpg,image/jpeg]|max_size[attachment,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PNG/JPG/JPEG',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
        } else if ($this->request->getPost('type') == 'dokumen') {
            $has_attachment = 1;
            $fields['attachment'] = [
                'rules' => 'uploaded[attachment]|mime_in[attachment,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]|max_size[attachment,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PDF/DOC/DOCX',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
        }

        if (!$this->validate($fields)) {
            return redirect()->back()->withInput();
        } else {
            // Save record ke tabel Forum
            $this->forumModel->save([
                'topic' => $this->request->getPost('topic'),
                'description' => $this->request->getPost('description'),
                'parent_id' => $this->request->getPost('parent'),
                'author_id' => $this->request->getPost('author'),
                'has_attachment' => $has_attachment
            ]);

            // Cek apakah pengguna memiliki lampiran
            if ($this->request->getPost('type') != 'none') {
                // Ambil file dan forum_id sebagai referensi parent dari attachment
                $attachment = $this->request->getFile('attachment');
                $forumid = $this->forumBuilder->select('forum_id')->get()->getLastRow('array');
                $forumid = $forumid['forum_id'];

                // Save record ke tabel Forum_Attachment
                $this->forumAttModel->save([
                    'name' => $attachment->getName(),
                    'parent' => 'forum',
                    'parent_id' => $forumid
                ]);

                // Pindahkan file ke direktori permanen
                $attachment->move('assets/forumAttachment/');
            }

            // Redirect dan Kirimkan pesan
            session()->setFlashdata('forumSuccess', 'Forum berhasil dibuat');

            return redirect()->to('/jobDetail/' . $this->request->getPost('parent') . '/' . $this->request->getPost('unitid'));
        }
    }

    public function detailForum($forumid)
    {
        // Forum Detail
        $this->forumBuilder->select('forum_id, topic, forum.description as forum_description, forum.author_id as forum_authorid, forum.has_attachment as forum_att, forum.created_at as forum_created, forum.updated_at as forum_updated, users.id as user_id, users.username as user_username, users.fullname as user_name');
        $this->forumBuilder->join('users', 'users.id = forum.author_id');
        $forumDetail = $this->forumBuilder->where('forum_id', $forumid)->get();

        // Attachment
        $this->builder = $this->db->table('forum_attachment');
        $attachments = $this->builder->select('*')->get();

        // Breadcrumb
        $this->forumBuilder->resetQuery();
        $this->forumBuilder->select('job_id, job.name as job_name, unit.unit_id as unit_id, unit.code as unit_code, segment.segment_id as segment_id')->join('job', 'job.job_id = forum.parent_id');
        $this->forumBuilder->join('unit', 'unit.unit_id = job.unit_id');
        $this->forumBuilder->join('segment', 'segment.segment_id = unit.segment_id');
        $this->forumBuilder->where('forum.forum_id', $forumid);
        $query = $this->forumBuilder->get();

        // Forum Reply
        $this->builder->resetQuery();
        $this->builder = $this->db->table('reply');
        $this->builder->select('reply.*, users.id as user_id, users.fullname as user_name, users.username as user_username');
        $this->builder->join('users', 'users.id = reply.author_id');
        $replies = $this->builder->where('forum_parent', $forumid)->orderBy('reply.updated_at', 'ASC')->get()->getResultArray();

        $data = [
            "judul" => "Topik Diskusi",
            'forumDetail' => $forumDetail->getRowArray(),
            'attachments' => $attachments->getResultArray(),
            'breadcrumb' => $query->getRowArray(),
            'replies' => $replies,
        ];

        return view('forum/forumDetail', $data);
    }

    public function createReply($forumid)
    {
        // Breadcrumb
        $this->forumBuilder->select('forum_id, topic, job_id, job.name as job_name, unit.unit_id as unit_id, unit.code as unit_code, segment.segment_id as segment_id')->join('job', 'job.job_id = forum.parent_id');
        $this->forumBuilder->join('unit', 'unit.unit_id = job.unit_id');
        $this->forumBuilder->join('segment', 'segment.segment_id = unit.segment_id');
        $this->forumBuilder->where('forum.forum_id', $forumid);
        $query = $this->forumBuilder->get();

        $data = [
            "judul" => "Balasan Forum",
            'breadcrumb' => $query->getRowArray(),
            'validation' => \Config\Services::validation(),
        ];

        return view('forum/createReply', $data);
    }

    public function saveReply()
    {
        // Validation Rules
        $fields = [
            'description' => [
                'rules'     => 'required|string|max_length[500]',
                'errors'    => [
                    'required' => 'Deskripsi tidak boleh kosong',
                    'string' => 'Terdapat karakter yang tidak sesuai pada Deskripsi',
                    'max_length' => 'Deskripsi tidak boleh lebih dari 500 karakter'
                ]
            ]
        ];

        // Deklarasi status has_attachment
        $has_attachment = 0;

        if ($this->request->getPost('type') == 'gambar') {
            $has_attachment = 1;
            $fields['attachment'] = [
                'rules' => 'uploaded[attachment]|mime_in[attachment,image/png,image/jpg,image/jpeg]|max_size[attachment,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PNG/JPG/JPEG',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
        } else if ($this->request->getPost('type') == 'dokumen') {
            $has_attachment = 1;
            $fields['attachment'] = [
                'rules' => 'uploaded[attachment]|mime_in[attachment,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]|max_size[attachment,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PDF/DOC/DOCX',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
        }

        // Lakukan validasi
        if (!$this->validate($fields)) {
            // Jika terdapat error, kembali
            return redirect()->back()->withInput();
        } else {
            // Lakukan proses save record jika tidak terdapat error
            $this->replyModel->save([
                'description' => $this->request->getPost('description'),
                'forum_parent' => $this->request->getPost('forum_parent'),
                'author_id' => $this->request->getPost('author_id'),
                'has_attachment' => $has_attachment
            ]);

            // Cek apakah pengguna memiliki lampiran
            if ($this->request->getPost('type') != 'none') {
                // Ambil file dan reply_id sebagai referensi parent dari file attachment
                $attachment = $this->request->getFile('attachment');
                $this->builder = $this->db->table('reply');
                $replyid = $this->builder->select('reply_id')->get()->getLastRow('array');
                $replyid = $replyid['reply_id'];

                // Save record ke tabel Forum Attachment
                $this->forumAttModel->save([
                    'name' => $attachment->getName(),
                    'parent' => 'reply',
                    'parent_id' => $replyid
                ]);

                // Pindahkan file ke direktori permanen
                $attachment->move('assets/forumAttachment/');
            }

            // Redirect dan kirimkan pesan
            session()->setFlashdata('replySuccess', 'Balasan Forum berhasil dikirim');

            return redirect()->to('/detailForum/' . $this->request->getPost('forum_parent'));
        }
    }

    public function forumAttDownload($fileName)
    {
        // Download file attachment
        return $this->response->download("assets/forumAttachment/$fileName", null);
    }

    public function deleteForum($forumid)
    {
        // 1. Hapus Attachment (Forum & Reply)
        // Ambil record Reply
        $this->builder = $this->db->table('reply');
        $this->builder->select('reply_id, forum_parent')->where('forum_parent', $forumid);
        $replies = $this->builder->get();
        $replies = $replies->getResultArray();

        // Ambil record Attachment dari Forum
        $this->builder = $this->db->table('forum_attachment');
        $where = ['parent' => 'forum', 'parent_id' => $forumid];
        $this->builder->select('att_id, name, parent, parent_id')->where($where);
        $attachments = $this->builder->get();
        $forumAttachments = $attachments->getResultArray();

        // Delete Record dan Unlink File Attachment dari Forum
        foreach ($forumAttachments as $att) {
            // Unlink/delete file dari server
            unlink('assets/forumAttachment/' . $att['name']);

            // Delete record dari database
            $this->forumAttModel->delete($att['att_id']);
        }

        // Ambil record Attachment dari Reply
        $this->builder->resetQuery();
        $this->builder = $this->db->table('forum_attachment');
        $this->builder->select('att_id, name, parent, parent_id')->where('parent', 'reply');
        $attachments = $this->builder->get();
        $replyAttachments = $attachments->getResultArray();

        foreach ($replyAttachments as $att) {
            foreach ($replies as $i => $reply) {
                if ($att['parent_id'] == $reply['reply_id']) {
                    // Unlink/delete file dari server
                    unlink('assets/forumAttachment/' . $att['name']);

                    // Delete record dari database
                    $this->forumAttModel->delete($att['att_id']);
                }
            }
        }

        // 2. Hapus Reply
        foreach ($replies as $reply) {
            $this->replyModel->delete($reply['reply_id']);
        }

        // 3. Ambil job_id dan unit_id sebelum forum terhapus
        $this->forumBuilder->select('forum_id, job_id, unit.unit_id as unitid');
        $this->forumBuilder->join('job', 'job.job_id = forum.parent_id');
        $this->forumBuilder->join('unit', 'unit.unit_id = job.unit_id');

        $redirect = $this->forumBuilder->where('forum_id', $forumid)->get();
        $redirect = $redirect->getRowArray();

        // 4. Hapus Forum
        $this->forumModel->delete($forumid);

        // 5. Redirect dan Tampilkan Pesan
        session()->setFlashdata('forumDeleted', 'Forum berhasil dihapus');

        return redirect()->to("jobDetail/" . $redirect['job_id'] . '/' . $redirect['unitid']);
    }

    public function deleteReply($replyid)
    {
        // 1. Hapus Attachment Reply
        // Ambil record Reply
        $this->builder = $this->db->table('reply');
        $this->builder->select('*')->where('reply_id', $replyid);
        $reply = $this->builder->get()->getRowArray();

        // Ambil record attachment dari Reply
        $this->builder->resetQuery();
        $this->builder = $this->db->table('forum_attachment');
        $where = ['parent' => 'reply', 'parent_id' => $reply['reply_id']];
        $this->builder->select('att_id, name, parent, parent_id')->where($where);
        $replyAttachments = $this->builder->get()->getResultArray();

        foreach ($replyAttachments as $att) {
            // Unlink/delete file dari server
            unlink('assets/forumAttachment/' . $att['name']);

            // Delete record dari database
            $this->forumAttModel->delete($att['att_id']);
        }

        // 2. Hapus Reply
        $this->replyModel->delete($reply['reply_id']);

        // 3. Redirect dan Tampilkan Pesan
        session()->setFlashdata('replyDeleted', 'Reply berhasil dihapus');
        return redirect()->back();
    }

    public function editForum($forumid)
    {
        // Breadcrumb
        $this->forumBuilder->select('forum_id, topic, job_id, job.name as job_name, unit.unit_id as unit_id, unit.code as unit_code, segment.segment_id as segment_id')->join('job', 'job.job_id = forum.parent_id');
        $this->forumBuilder->join('unit', 'unit.unit_id = job.unit_id');
        $this->forumBuilder->join('segment', 'segment.segment_id = unit.segment_id');
        $this->forumBuilder->where('forum.forum_id', $forumid);
        $query = $this->forumBuilder->get();

        // Form Input Value & File Attachment
        $this->forumBuilder->resetQuery();
        $formValue = $this->forumBuilder->select('*')->where('forum_id', $forumid)->get();

        // File Attachment
        $this->builder = $this->db->table('forum_attachment');
        $where = ['parent' => 'forum', 'parent_id' => $forumid];
        $attachments = $this->builder->select('*')->where($where)->get();

        $data = [
            'judul' => "Edit Forum",
            "validation" => \Config\Services::validation(),
            'breadcrumb' => $query->getRowArray(),
            'form' => $formValue->getRowArray(),
            'attachments' => $attachments->getResultArray(),
        ];

        return view('forum/editForum', $data);
    }

    public function editReply($replyid)
    {
        // Form Input Value & File Attachment
        $this->builder = $this->db->table('reply');
        $formValue = $this->builder->select('*')->where('reply_id', $replyid)->get()->getRowArray();

        // Breadcrumb
        $this->forumBuilder->select('forum_id, topic, job_id, job.name as job_name, unit.unit_id as unit_id, unit.code as unit_code, segment.segment_id as segment_id')->join('job', 'job.job_id = forum.parent_id');
        $this->forumBuilder->join('unit', 'unit.unit_id = job.unit_id');
        $this->forumBuilder->join('segment', 'segment.segment_id = unit.segment_id');
        $this->forumBuilder->where('forum.forum_id', $formValue['forum_parent']);
        $query = $this->forumBuilder->get();

        // File Attachment
        $this->builder->resetQuery();
        $this->builder = $this->db->table('forum_attachment');
        $where = ['parent' => 'reply', 'parent_id' => $replyid];
        $attachments = $this->builder->select('*')->where($where)->get();

        $data = [
            'judul' => "Edit Balasan",
            "validation" => \Config\Services::validation(),
            'breadcrumb' => $query->getRowArray(),
            'form' => $formValue,
            'attachments' => $attachments->getResultArray(),
        ];

        return view('forum/editReply', $data);
    }

    public function deleteForumAtt($id)
    {
        // Ambil record file sesuai dengan id
        $file = $this->forumAttModel->find($id);

        // Ambil parent_id
        $data = ['parent' => $file['parent'], 'parent_id' => $file['parent_id']];

        // Hapus record dan unlink file
        $this->forumAttModel->delete($id);
        unlink('assets/forumAttachment/' . $file['name']);

        // Cek apakah forum/reply tersebut masih memiliki attachment
        $this->builder = $this->db->table('forum_attachment');
        $where = ['parent' => $data['parent'], 'parent_id' => $data['parent_id']];
        $attachments = $this->builder->select('*')->where($where)->get();
        $attachments = $attachments->getResultArray();

        // Jika forum/reply tidak memiliki attachment lagi maka set has_attachment = 0 (false)
        if (count($attachments) == 0) {
            if ($data['parent'] == 'forum') {
                $this->forumModel->save([
                    'forum_id' => $file['parent_id'],
                    'has_attachment' => 0
                ]);
            } elseif ($data['parent'] == 'reply') {
                $this->replyModel->save([
                    'reply_id' => $file['parent_id'],
                    'has_attachment' => 0
                ]);
            }
        }

        // Redirect dan kirimkan pesan
        session()->setFlashdata('deleted', 'File berhasil dihapus');

        return redirect()->back();
    }

    public function updateForum($forumid)
    {
        // Validation Rules
        $fields = [
            'topic' => [
                'rules' => 'required|alpha_numeric_space|max_length[255]',
                'errors' => [
                    'required' => 'Judul topik tidak boleh kosong',
                    'alpha_numeric_space' => 'Judul topik hanya boleh mengandung huruf, angka, dan spasi',
                    'max_length' => 'Judul topik tidak boleh lebih dari 255 karakter'
                ]
            ],
            'description' => [
                'rules'     => 'required|string|max_length[500]',
                'errors'    => [
                    'required' => 'Deskripsi tidak boleh kosong',
                    'string' => 'Terdapat karakter yang tidak sesuai pada Deskripsi',
                    'max_length' => 'Deskripsi tidak boleh lebih dari 500 karakter'
                ]
            ]
        ];

        $has_attachment = $this->request->getPost('has_attachment');
        if ($this->request->getPost('type') == 'gambar') {
            $fields['attachment'] = [
                'rules' => 'uploaded[attachment]|mime_in[attachment,image/png,image/jpg,image/jpeg]|max_size[attachment,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PNG/JPG/JPEG',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
            $has_attachment = 1;
        } else if ($this->request->getPost('type') == 'dokumen') {
            $fields['attachment'] = [
                'rules' => 'uploaded[attachment]|mime_in[attachment,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]|max_size[attachment,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PDF/DOC/DOCX',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
            $has_attachment = 1;
        }

        // Lakukan validasi
        if (!$this->validate($fields)) {
            // Kembali jika ada error
            return redirect()->back()->withInput();
        } else {
            // Update record
            $this->forumModel->save([
                'forum_id' => $forumid,
                'topic' => $this->request->getPost('topic'),
                'description' => $this->request->getPost('description'),
                'has_attachment' => $has_attachment
            ]);

            // Upload file
            if ($this->request->getPost('type') != 'none') {
                $attachment = $this->request->getFile('attachment');

                // Update record
                $this->forumAttModel->save([
                    'name' => $attachment->getName(),
                    'parent' => 'forum',
                    'parent_id' => $forumid
                ]);

                // Pindahkan file ke direktori permanen
                $attachment->move('assets/forumAttachment/');
            }

            // Redirect dan kirimkan pesan
            session()->setFlashdata('forumEdited', 'Forum Berhasil Diubah');

            if ($this->request->getPost('redirect') == 1) {
                return redirect()->to('/detailForum/' . $forumid);
            } else {
                return redirect()->back();
            }
        }
    }

    public function updateReply()
    {
        // Validation Rules
        $fields = [
            'description' => [
                'rules'     => 'required|string|max_length[500]',
                'errors'    => [
                    'required' => 'Deskripsi tidak boleh kosong',
                    'string' => 'Terdapat karakter yang tidak sesuai pada Deskripsi',
                    'max_length' => 'Deskripsi tidak boleh lebih dari 500 karakter'
                ]
            ]
        ];

        // Status reply memiliki attachment atau tidak
        $has_attachment = $this->request->getPost('has_attachment');

        if ($this->request->getPost('type') == 'gambar') {
            $fields['attachment'] = [
                'rules' => 'uploaded[attachment]|mime_in[attachment,image/png,image/jpg,image/jpeg]|max_size[attachment,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PNG/JPG/JPEG',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
            $has_attachment = 1;
        } else if ($this->request->getPost('type') == 'dokumen') {
            $fields['attachment'] = [
                'rules' => 'uploaded[attachment]|mime_in[attachment,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]|max_size[attachment,5120]',
                'errors' => [
                    'uploaded' => 'Harap pilih file terlebih dahulu',
                    'mime_in' => 'Ekstensi file harus PDF/DOC/DOCX',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 5 MB'
                ]
            ];
            $has_attachment = 1;
        }

        // Lakukan validasi
        if (!$this->validate($fields)) {
            // Kembali jika ada error
            return redirect()->back()->withInput();
        } else {
            // Update record
            $this->replyModel->save([
                'reply_id' => $this->request->getPost('replyid'),
                'description' => $this->request->getPost('description'),
                'has_attachment' => $has_attachment
            ]);

            // Upload file
            if ($this->request->getPost('type') != 'none') {
                $attachment = $this->request->getFile('attachment');

                // Update record
                $this->forumAttModel->save([
                    'name' => $attachment->getName(),
                    'parent' => 'reply',
                    'parent_id' => $this->request->getPost('replyid')
                ]);

                // Pindahkan file ke direktori permanen
                $attachment->move('assets/forumAttachment/');
            }

            // Redirect dan kirimkan pesan
            session()->setFlashdata('replyEdited', 'Balasan Forum berhasil diubah');

            if ($this->request->getPost('redirect') == 1) {
                return redirect()->to('/detailForum/' . $this->request->getPost('forumid'));
            } else {
                return redirect()->back();
            }
        }
    }
}
