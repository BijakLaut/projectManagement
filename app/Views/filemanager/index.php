<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mb-lg-5">
    <h2><?= $judul; ?></h2>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">File Lampiran - Proyek Apartemen Uhuy</li>
        </ol>
    </nav>
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
                    <input type="hidden" name="parent" value="project">
                    <div class="row justify-content-center mb-2">
                        <div class="col-lg-4">
                            <label class="form-label">Nama File</label>
                            <input type="text" class="form-control <?= ($validation->hasError('namaFile')) ? 'is-invalid' : '' ?>" name="namaFile" autocomplete="off">
                            <div class="invalid-feedback">
                                <?= $validation->getError('namaFile') ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Jenis File</label>
                            <select class="form-select <?= ($validation->hasError('type')) ? 'is-invalid' : '' ?>" name="type" onchange="enableUpload()">
                                <option selected>Pilih jenis file..</option>
                                <option value="spk">SPK (PDF/DOC/DOCX)</option>
                                <option value="gambar">Asbuild Gambar (JPG/JPEG/PNG)</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= $validation->getError('type') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <label class="form-label">Upload File</label>
                            <div class="input-group">
                                <input type="file" class="form-control <?= ($validation->hasError('file')) ? 'is-invalid' : '' ?>" disabled name="file">
                                <button class="btn btn-outline-secondary" disabled type="submit">Upload</button>
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
    <div class="row mt-lg-5 justify-content-between">
        <div class="col-lg-5">
            <h4 class="text-center">Surat Perintah Kerja</h4>
            <div>
                <ul class="list-group my-3">
                    <?php if (count($filespk) > 0) : ?>
                        <?php foreach ($filespk as $file) : ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="col"><?= $file->name ?></span>
                                <div class="col d-flex justify-content-end">
                                    <form action="<?= base_url('deleteFile/' . $file->file_id) ?>" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-sm btn-outline-danger me-2" type="submit" onclick="return confirm('Hapus file?')">Hapus</button>
                                    </form>
                                    <a class="btn btn-sm btn-outline-warning me-0" href="<?= base_url('toDownload/' . $file->name . '/' . $file->extension . '/' . $file->type) ?>">Unduh</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li class="list-group-item d-flex justify-content-center">
                            <span class="fst-italic">Tidak ada file untuk ditampilkan</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-5">
            <h4 class="text-center">Asbuild Gambar</h4>
            <div>
                <ul class="list-group my-3">
                    <?php foreach ($filegambar as $file) : ?>
                        <?php if ($file->type == 'gambar') : ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="col"><?= $file->name ?></span>
                                <div class="col d-flex justify-content-end">
                                    <form action="<?= base_url('deleteFile/' . $file->file_id) ?>" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-sm btn-outline-danger me-2" type="submit" onclick="return confirm('Hapus file?')">Hapus</button>
                                    </form>
                                    <a class="btn btn-sm btn-outline-warning me-0" href="<?= base_url('toDownload/' . $file->name . '/' . $file->extension . '/' . $file->type) ?>">Unduh</a>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
    function enableUpload() {
        if (event.target.value == "spk" || event.target.value == "gambar") {
            $('div.input-group input').removeAttr('disabled');
            $('div.input-group button').removeAttr('disabled');
            $('div.input-group button').removeClass('btn-outline-secondary');
            $('div.input-group button').addClass('btn-outline-primary');
        } else {
            $('div.input-group input').attr('disabled', true);
            $('div.input-group button').attr('disabled', true);
            $('div.input-group button').removeClass('btn-outline-primary');
            $('div.input-group button').addClass('btn-outline-secondary');
        }
    }
</script>

<?= $this->endSection(); ?>