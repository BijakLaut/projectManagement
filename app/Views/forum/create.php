<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2><?= $judul; ?></h2>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/unitDetail/' . $detail['unit_id'] . '/' . $detail['segment_id']); ?>" class="text-decoration-none">Detail Unit - <?= $detail['unit_code'] ?></a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/jobDetail/' . $detail['job_id'] . '/' . $detail['unit_id']); ?>" class="text-decoration-none"><?= $detail['job_name'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $judul ?></li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-lg-7 mt-lg-5">
            <form action="<?= base_url('saveForum') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="parent" value="<?= $detail['job_id'] ?>">
                <input type="hidden" name="unitid" value="<?= $detail['unit_id'] ?>">
                <input type="hidden" name="author" value="<?= user()->id ?>">
                <input type="hidden" name="attparent" value="forum">
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Topik Diskusi</label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control <?= ($validation->hasError('topic')) ? 'is-invalid' : '' ?>" name="topic" autocomplete="off" value="<?= old('topic') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('topic') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Deskripsi</label>
                    <div class="col-lg-7">
                        <textarea class="form-control <?= ($validation->hasError('description')) ? 'is-invalid' : '' ?>" rows="4" name="description"><?= old('description') ?></textarea>
                        <div class="invalid-feedback">
                            <?= $validation->getError('description') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Jenis File</label>
                    <div class="col-lg-7">
                        <select class="form-select <?= ($validation->hasError('type')) ? 'is-invalid' : '' ?>" name="type" onchange="enableUpload()">
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
                <div class="row col-lg-8 mx-auto">
                    <button type="submit" class="btn btn-sm btn-primary my-lg-5 to-enable" disabled>Submit</button>
                </div>
            </form>
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
</script>
<?= $this->endsection(); ?>