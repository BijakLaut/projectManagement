<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-2">
    <h2 class="mb-4"><?= $judul; ?></h2>
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('pesan'); ?>
        </div>
    <?php elseif (session()->getFlashdata('hapus')) : ?>
        <div class="alert alert-warning" role="alert">
            <?= session()->getFlashdata('hapus'); ?>
        </div>
    <?php endif; ?>
    <div class="list-group col-6">
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold">Surat Perintah Kerja</div>
            </div>
            <div class="d-grid col-1">
                <!-- Button trigger modal -->
                <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="modal" data-bs-target="#modalspk">Lihat</button>
            </div>
        </li>

        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold">Asbuild Gambar</div>
            </div>
            <div class="d-grid col-1">
                <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="modal" data-bs-target="#modalasbuild">Lihat</button>
            </div>
        </li>
    </div>

    <div class="progress-section my-5">
        <div class="header-progress d-flex flex-row mb-3">
            <h3 class="my-2 me-4">Daftar Segmen</h3>
            <button class="btn btn-sm btn-primary my-2" type="button" data-bs-toggle="modal" data-bs-target="#modalTambahSegmen">Tambah Segmen</button>
        </div>

        <?php $i = 1; ?>
        <?php foreach ($segments as $segment) : ?>
            <div class="row">
                <div class="card col-9 px-0 mb-3">
                    <div class="card-header row align-items-center mx-0" data-bs-toggle="collapse" href="#collapse<?= $i; ?>" role="button" aria-expanded="<?= ($i == 1) ? 'true' : 'false' ?>" aria-controls="#collapse<?= $i; ?>">
                        <span class="text-header col-2">
                            <strong><?= $segment['name'] ?></strong>
                        </span>
                        <div class="progress-div col-3">
                            <span><strong>Progress</strong></span>
                            <div class="progress gx-0" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $segment['progress']; ?>%">
                                <div class="progress-bar" role="progressbar" style="width: <?= $segment['progress'] ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                        </div>
                        <div class="time-expand col-7 d-flex flex-row justify-content-between">
                            <span class="ms-2"><strong>Target Penyelesaian: </strong><span class="text-danger"><?= date('d M Y', strtotime($segment['duedate'])); ?></span></span>
                            <span>Sisa Waktu: <?= $segment['datediff'] ?> Hari</span>
                            <img src="assets/img/expand_more.png" alt="" width="25px">
                        </div>

                    </div>
                    <div class="collapse <?= ($i == 1) ? 'show' : '' ?> card-body" id="collapse<?= $i; ?>">
                        <button type="button" class="btn btn-sm btn-primary mt-2 ms-2" data-bs-toggle="modal" data-bs-target="#modalTambahUnit" data-segmentid="<?= $segment['segment_id']; ?>" data-segmentname="<?= $segment['name']; ?>">Tambah Unit</button>
                        <div class="unit-cards d-flex flex-row flex-wrap my-3">
                            <?php foreach ($units as $unit) : ?>
                                <?php if ($unit['segment_id'] == $segment['segment_id']) : ?>
                                    <div class="card text-dark bg-info m-2" style="width: 200px; height: 150px;">
                                        <div class="card-body pt-4">
                                            <h6 class="card-title"><?= $unit['name'] ?></h6>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?= $unit['progress'] ?>%;" aria-valuenow="<?= $unit['progress'] ?>" aria-valuemin="0" aria-valuemax="100"><?= $unit['progress'] ?>%</div>
                                            </div>
                                            <div class="card-action d-flex flex-row align-items-end">
                                                <a href="<?= base_url('/unitDetail/' . $unit['unit_id'] . '/' . $unit['segment_id']) ?>">
                                                    <button type="button" class="btn btn-sm btn-light mt-4 me-2">Detail Unit</button>
                                                </a>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light px-2 dropdown-toggle" type="button" id="opsiUnit" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Opsi
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="opsiUnit">
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEditUnit" data-unitcode="<?= $unit['code'] ?>" data-unitid="<?= $unit['unit_id'] ?>" onclick="populate(this, <?= htmlentities(json_encode($unit)); ?>); ">Edit</button>
                                                        <form action="<?= base_url('/delete/unit/' . $unit['unit_id'])  ?>" method="POST">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Hapus Unit?')">Hapus</button>
                                                        </form>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $i++; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal SPK -->
<div class="modal fade" id="modalspk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalspkLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalspkLabel">Daftar SPK - Proyek A</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <label for="unggahspk" class="form-label">Unggah Berkas</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="unggahspk" aria-describedby="inputgroupspk" aria-label="Upload">
                        <button class="btn btn-outline-primary" type="submit" id="inputgroupspk">Unggah</button>
                    </div>
                </form>
                <ul class="list-group my-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Surat Perintah Kerja Maret 2022</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Surat Perintah Kerja Januari 2022</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Surat Perintah Kerja Desember 2021</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asbuild -->
<div class="modal fade" id="modalasbuild" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalasbuildLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalasbuildLabel">Daftar Asbuild Gambar - Proyek A</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <label for="unggahasbuild" class="form-label">Unggah Berkas</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="unggahasbuild" aria-describedby="inputgroupasbuild" aria-label="Upload">
                        <button class="btn btn-outline-primary" type="submit" id="inputgroupasbuild">Unggah</button>
                    </div>
                </form>
                <ul class="list-group my-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Gambar 1</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Gambar 2</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Gambar 3</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Segmen -->
<div class="modal fade" id="modalTambahSegmen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTambahSegmenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahSegmenLabel">Tambah Segmen - Proyek A</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('/addSegment') ?>" method="POST" id="addSegment">
                    <?php csrf_field(); ?>
                    <input type="hidden" class="form-control" name="form_id" value="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Segmen</label>
                        <input type="text" class="form-control" name="name" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control" name="duedate" autocomplete="off" value="dd-mm-yyyy" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Tambah Segmen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Unit -->
<div class="modal fade" id="modalTambahUnit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTambahUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahUnitLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('/addUnit') ?>" method="POST" id="addUnit" data-segmentid="<?= $segment['segment_id'] ?>" novalidate>
                    <?php csrf_field(); ?>
                    <input type="hidden" class="form-control" name="segment_id" value="">
                    <input type="hidden" class="form-control" name="form_id" value="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Unit</label>
                        <input type="text" class="form-control" name="name" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Unit</label>
                        <input type="text" class="form-control" name="code" value="" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control" name="duedate" required autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Tambah Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Unit -->
<div class="modal fade" id="modalEditUnit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditUnitLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('/editUnit') ?>" method="POST" id="editUnit" novalidate>
                    <?php csrf_field(); ?>
                    <input type="hidden" class="form-control" name="unit_id" value="">
                    <input type="hidden" class="form-control" name="segment_id" value="">
                    <input type="hidden" class="form-control" name="form_id" value="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Unit</label>
                        <input type="text" class="form-control" name="name" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Unit</label>
                        <input type="text" class="form-control" name="code" value="" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control" name="duedate" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Edit Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>