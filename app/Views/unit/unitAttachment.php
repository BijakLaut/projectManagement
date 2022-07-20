<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mb-lg-5">
    <h2><?= $judul; ?></h2>
    <!-- Breadcrumb -->
    <?= $this->include('layout/breadcrumbFA'); ?>

    <!-- Alert -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php elseif (session()->getFlashdata('deleted')) : ?>
        <div class="alert alert-warning" role="alert">
            <?= session()->getFlashdata('deleted'); ?>
        </div>
    <?php endif; ?>
    <div class="row justify-content-center mt-lg-4">
        <div class="col-lg-8">
            <?php if (in_groups('SuperAdmin')) : ?>
                <form action="<?= base_url('uploadFile') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="parent" value="<?= $parent['parent']; ?>">
                    <input type="hidden" name="parentid" value="<?= $parent['parent_id']; ?>">
                    <input type="hidden" name="authorid" value="<?= user()->id; ?>">
                    <div class="row justify-content-center mb-2">
                        <div class="col-lg-4">
                            <label class="form-label">Nama File</label>
                            <input type="text" class="form-control <?= ($validation->hasError('originalname')) ? 'is-invalid' : '' ?>" name="originalname" autocomplete="off">
                            <div class="invalid-feedback">
                                <?= $validation->getError('originalname') ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Jenis File</label>
                            <select class="form-select <?= ($validation->hasError('type')) ? 'is-invalid' : '' ?>" name="type" onchange="enableUpload()">
                                <option selected>Pilih jenis file..</option>
                                <option value="dokumen">Dokumen (PDF/DOC/DOCX)</option>
                                <option value="gambar">Gambar (JPG/JPEG/PNG)</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= $validation->getError('type') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <label class="form-label">Unggah File</label>
                            <div class="input-group">
                                <input type="file" class="form-control <?= ($validation->hasError('file')) ? 'is-invalid' : '' ?>" disabled name="file">
                                <button class="btn btn-outline-secondary" disabled type="submit">Unggah</button>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('file') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <hr class="mt-5">
    <div class="row mt-lg-5 justify-content-center">
        <div class="col-lg-8">
            <h4 class="text-center">File Lampiran</h4>
            <div>
                <ul class="list-group my-3">
                    <?php if (count($attachments) > 0) : ?>
                        <?php foreach ($attachments as $file) : ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="col"><?= $file->original_name ?></span>
                                <div class="col d-flex justify-content-end">
                                    <form action="<?= base_url('deleteFile/' . $file->file_id) ?>" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-sm btn-outline-danger me-2" type="submit" onclick="return confirm('Hapus file?')">Hapus</button>
                                    </form>
                                    <a class="btn btn-sm btn-outline-warning me-0" href="<?= base_url('toDownload/' . $file->file_id) ?>">Unduh</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="alert alert-warning text-center" role="alert">
                            Tidak ada file untuk ditampilkan
                        </div>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
    function enableUpload() {
        if (event.target.value == "gambar") {
            $('div.input-group input').removeAttr('disabled');
            $('div.input-group button').removeAttr('disabled');
            $('div.input-group button').removeClass('btn-outline-secondary');
            $('div.input-group button').addClass('btn-outline-primary');
            $('div.input-group input').attr('accept', 'image/png, image/jpg, image/jpeg');
        } else if (event.target.value == "dokumen") {
            $('div.input-group input').removeAttr('disabled');
            $('div.input-group button').removeAttr('disabled');
            $('div.input-group button').removeClass('btn-outline-secondary');
            $('div.input-group button').addClass('btn-outline-primary');
            $('div.input-group input').attr('accept', 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        } else {
            $('div.input-group input').attr('disabled', true);
            $('div.input-group button').attr('disabled', true);
            $('div.input-group button').removeClass('btn-outline-primary');
            $('div.input-group button').addClass('btn-outline-secondary');
        }
    }
</script>

<?= $this->endSection(); ?>