<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2><?= $judul; ?></h2>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>" class="text-decoration-none">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item disabled">
                ...
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('/jobDetail/' . $breadcrumb['job_id'] . '/' . $breadcrumb['unit_id']); ?>" class="text-decoration-none">
                    <?= $breadcrumb['job_name']; ?>
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('/detailForum/' . $breadcrumb['forum_id']); ?>" class="text-decoration-none">
                    <?= $breadcrumb['topic']; ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Edit Forum
            </li>
        </ol>
    </nav>
    <?php if (session()->getFlashdata('forumEdited')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('forumEdited'); ?>
        </div>
    <?php elseif (session()->getFlashdata('deleted')) : ?>
        <div class="alert alert-warning" role="alert">
            <?= session()->getFlashdata('deleted'); ?>
        </div>
    <?php endif; ?>
    <div class="row justify-content-center">
        <div class="col-lg-7 mt-lg-5">
            <form id="updateForum" action="<?= base_url('updateForum/' . $form['forum_id']) ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="has_attachment" value="<?= $form['has_attachment'] ?>">
                <input type="hidden" name="redirect" id="redirect">
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Topik Diskusi</label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control <?= ($validation->hasError('topic')) ? 'is-invalid' : '' ?>" name="topic" autocomplete="off" value="<?= (old('topic') !== null) ? old('topic') : $form['topic'] ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('topic') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Deskripsi</label>
                    <div class="col-lg-7">
                        <textarea class="form-control <?= ($validation->hasError('description')) ? 'is-invalid' : '' ?>" rows="4" name="description"><?= (old('description') !== null) ? old('description') : $form['description'] ?></textarea>
                        <div class="invalid-feedback">
                            <?= $validation->getError('description') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Jenis File</label>
                    <div class="col-lg-7">
                        <select id="select" class="form-select <?= ($validation->hasError('type')) ? 'is-invalid' : '' ?>" name="type" onchange="enableUpload()">
                            <option selected>Pilih jenis file..</option>
                            <option value="none">Tanpa Lampiran</option>
                            <option value="dokumen">Dokumen (PDF/DOC/DOCX)</option>
                            <option value="gambar">Gambar (JPG/JPEG/PNG)</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('type') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">File Lampiran</label>
                    <div class="col-lg-7">
                        <input class="form-control to-enable <?= ($validation->hasError('attachment')) ? 'is-invalid' : '' ?>" type="file" disabled name="attachment">
                        <div class="invalid-feedback">
                            <?= $validation->getError('attachment') ?>
                        </div>
                    </div>
                </div>
                <div class="row col-lg-7 mx-auto">
                    <button type="submit" class="btn btn-sm btn-primary mt-lg-5 mb-lg-3 to-enable" disabled onclick="redir(1)">Simpan Perubahan & Kembali</button>
                </div>
                <div class="row col-lg-7 mx-auto justify-content-around">
                    <button type="submit" class="btn btn-sm btn-primary to-enable col-lg-5" disabled onclick="redir(0)">Simpan Perubahan</button>
                    <button type="button" class="btn btn-sm btn-primary col-lg-5" onclick="previous(<?= htmlentities(json_encode($form)); ?>)">Reset</button>
                </div>
            </form>
        </div>
        <div class="col-lg-5 mt-lg-5">
            <?php if (count($attachments) > 0) : ?>
                <div class="row mb-3 justify-content-center">
                    <h5 class="text-center mb-lg-3">File Terlampir</h5>
                    <div class="col-lg-12">
                        <ul class="list-group">
                            <?php foreach ($attachments as $attachment) : ?>
                                <li class="list-group-item d-flex justify-content-between align-content-center">
                                    <div class="overflow-auto">
                                        <?= $attachment['name'] ?>
                                    </div>
                                    <div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Opsi
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="<?= base_url('forumAttDownload/' . $attachment['name']) ?>">Unduh</a>
                                                </li>
                                                <li>
                                                    <form action="<?= base_url('deleteForumAtt/' . $attachment['att_id']) ?>" method="POST">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <a class="dropdown-item" onclick="submit()">Hapus</a>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    function enableUpload() {
        if (event.target.value == "dokumen") {
            $('input.to-enable').removeAttr('disabled');
            $('button.to-enable').removeAttr('disabled');
            $('input.to-enable').attr('accept', 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        } else if (event.target.value == "gambar") {
            $('input.to-enable').removeAttr('disabled');
            $('button.to-enable').removeAttr('disabled');
            $('input.to-enable').attr('accept', 'image/png, image/jpg, image/jpeg');
        } else if (event.target.value == "none") {
            $('button.to-enable').removeAttr('disabled');
        } else {
            $('input.to-enable').attr('disabled', true);
            $('button.to-enable').attr('disabled', true);
        }

    }

    function previous(object) {
        let inputs = $("form#updateForum").find("input");
        let select = $("#select")[0];
        let options = $("#select option");

        $('textarea[name="description"]').val(object['description']);
        $('textarea[name="description"]').removeClass('is-invalid');

        for (const option of options) {
            $(option).attr("selected", false);

            if (option.label == 'Pilih jenis file..') {
                $(select).val(option.value).change();
            }
        }

        for (const input of inputs) {
            if (object.hasOwnProperty(input.name)) {
                $(input).val(object[input.name]);
            }

            if ($(input).hasClass("is-invalid")) {
                $(input).removeClass("is-invalid");
            }
        }
    }

    function redir(cond) {
        $('#redirect').val(cond);
    }

    function submit() {
        if (confirm('Hapus file?')) {
            $(event.target).parents('form').submit();
        }
    }
</script>
<?= $this->endsection(); ?>