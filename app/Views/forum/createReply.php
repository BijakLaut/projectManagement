<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2><?= $judul; ?></h2>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item disabled">
                ...
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('/jobDetail/' . $breadcrumb['job_id'] . '/' . $breadcrumb['unit_id']); ?>" class="text-decoration-none"><?= $breadcrumb['job_name']; ?></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('/detailForum/' . $breadcrumb['forum_id']); ?>" class="text-decoration-none"><?= $breadcrumb['topic']; ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Buat Balasan Forum
            </li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-lg-7 mt-lg-5">
            <form action="<?= base_url('saveReply') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="forum_parent" value="<?= $breadcrumb['forum_id'] ?>">
                <input type="hidden" name="author_id" value="<?= user()->id ?>">
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Balasan Forum</label>
                    <div class="col-lg-7">
                        <input type="text" readonly class="form-control" autocomplete="off" value="<?= $breadcrumb['topic'] ?>">
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Deskripsi</label>
                    <div class="col-lg-7">
                        <textarea class="form-control <?= ($validation->hasError('description')) ? 'is-invalid' : '' ?>" rows="4" name="description"></textarea>
                        <div class="invalid-feedback">
                            <?= ($validation->getError('description')); ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">Jenis File</label>
                    <div class="col-lg-7">
                        <select class="form-select" name="type" onchange="enableUpload()">
                            <option selected>Pilih jenis file..</option>
                            <option value="none">Tanpa Lampiran</option>
                            <option value="dokumen">Dokumen (PDF/DOC/DOCX)</option>
                            <option value="gambar">Gambar (JPG/JPEG/PNG)</option>
                        </select>
                        <div class="invalid-feedback">

                        </div>
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <label class="col-lg-3 col-form-label">File Lampiran</label>
                    <div class="col-lg-7">
                        <input class="form-control to-enable <?= ($validation->hasError('attachment')) ? 'is-invalid' : '' ?>" type="file" disabled name="attachment">
                        <div class="invalid-feedback">
                            <?= ($validation->getError('attachment')); ?>
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