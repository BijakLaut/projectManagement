<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-2">
    <h2 class="mb-5"><?= $judul; ?></h2>
    <!-- Flash Message -->
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('pesan'); ?>
        </div>
    <?php elseif (session()->getFlashdata('hapus')) : ?>
        <div class="alert alert-warning" role="alert">
            <?= session()->getFlashdata('hapus'); ?>
        </div>
    <?php endif; ?>

    <!-- File Lampiran -->
    <div class="list-group col-6">
        <li class="list-group-item d-flex justify-content-between align-items-start" data-bs-toggle="tooltip" data-bs-placement="right" title="Surat Perintah Kerja & Asbuild Gambar">
            <div class="ms-2 me-auto">
                <div><span class="fw-bold">File Lampiran</span></div>
            </div>
            <div class="d-grid col-1">
                <a class="btn btn-sm btn-outline-info" href="<?= base_url('uploadAttachment/project/0') ?>">Lihat</a>
            </div>
        </li>
    </div>

    <!-- Segmen -->
    <div class="progress-section my-5">
        <!-- Header Segmen -->
        <div class="header-progress mb-lg-4">
            <h3 class="my-lg-3 me-4 row">Daftar Segmen</h3>
            <?php if (in_groups('SuperAdmin')) : ?>
                <div class="row align-items-lg-center justify-content-lg-start d-flex flex-row">
                    <button class="btn btn-sm btn-primary col-lg-2" type="button" data-bs-toggle="modal" data-bs-target="#modalTambahSegmen">Tambah Segmen</button>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <select class="form-select form-select-sm" onchange="enableOption();">
                                <option value="null" selected>Pilih Segmen</option>
                                <?php foreach ($segments as $segment) : ?>
                                    <option value="<?= $segment['segment_id'] ?>"><?= $segment['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-sm btn-outline-primary px-lg-3" disabled type="button" data-bs-toggle="modal" data-bs-target="#modalEditSegment" onclick="populateSegment(this, <?= htmlentities(json_encode($segments)); ?>); ">Edit</button>
                            <form action="<?= base_url('delete')  ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-sm btn-outline-danger px-lg-2" disabled type="button" onclick="deleteSegment()">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Daftar Segmen -->
        <?php if (count($segments) > 0) : ?>
            <?php $i = 1; ?>
            <?php foreach ($segments as $segment) : ?>
                <div class="row">
                    <div class="card col-12 px-0 mb-3">
                        <div class="card-header row align-items-center mx-0" data-bs-toggle="collapse" href="#collapse<?= $i; ?>" role="button" aria-expanded="<?= ($i == 1) ? 'true' : 'false' ?>" aria-controls="#collapse<?= $i; ?>">
                            <span class="text-header col-2">
                                <strong><?= $segment['name'] ?></strong>
                            </span>
                            <div class="progress-div ms-lg-0 col-2">
                                <span><strong>Progress</strong></span>
                                <div class="progress gx-0" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $segment['progress']; ?>%">
                                    <div class="progress-bar" role="progressbar" style="width: <?= $segment['progress'] ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                            </div>
                            <div class="col-lg-2 ps-lg-5">
                                <span>
                                    <strong>Status:</strong>
                                    <?php if ($segment['status'] == 'Berjalan') :  ?>
                                        <span class="text-primary"><?= $segment['status'] ?></span>
                                    <?php elseif ($segment['status'] == 'Selesai') :  ?>
                                        <span class="text-success"><?= $segment['status'] ?></span>
                                    <?php elseif ($segment['status'] == 'Tertunda') :  ?>
                                        <span class="text-danger"><?= $segment['status'] ?></span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="col-lg-3 text-lg-center">
                                <span class="ms-2"><strong>Target Penyelesaian: </strong><span><?= date('d M Y', strtotime($segment['duedate'])); ?></span></span>
                            </div>
                            <div class="time-expand col-3 d-flex flex-row justify-content-lg-between">
                                <span>
                                    <?php if ($segment['status'] == 'Selesai') : ?>
                                        <strong>Keterangan:</strong>
                                        <?php if ($segment['datediff'] < 0) : ?>
                                            <span class="text-primary">Lebih awal <?= abs($segment['datediff']); ?> Hari</span>
                                        <?php elseif ($segment['datediff'] == 0) : ?>
                                            <span class="text-success">Tepat Waktu</span>
                                        <?php elseif ($segment['datediff'] > 0) : ?>
                                            <span class="text-danger">Terlambat <?= abs($segment['datediff']); ?> Hari</span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <strong>Sisa Waktu:</strong>
                                        <?php if ($segment['datediff'] < 0) : ?>
                                            <span class="text-danger">Terlambat <?= abs($segment['datediff']); ?> Hari</span>
                                        <?php else : ?>
                                            <?= $segment['datediff']; ?> Hari
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                                <img src="assets/img/expand_more.png" alt="" width="25px">
                            </div>
                        </div>
                        <div class="collapse <?= ($i == 1) ? 'show' : '' ?> card-body" id="collapse<?= $i; ?>">
                            <?php if (in_groups('SuperAdmin')) : ?>
                                <button type="button" class="btn btn-sm btn-primary mt-2 ms-2" data-bs-toggle="modal" data-bs-target="#modalTambahUnit" data-segmentid="<?= $segment['segment_id']; ?>" data-segmentname="<?= $segment['name']; ?>">Tambah Unit</button>
                            <?php endif; ?>
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
                                                    <?php if (in_groups('SuperAdmin')) : ?>
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
                                                    <?php else : ?>
                                                        <a href="<?= base_url('/unitDetail/' . $unit['unit_id'] . '/' . $unit['segment_id']) ?>">
                                                            <button type="button" class="btn btn-sm btn-light mt-4 me-2">Detail Unit</button>
                                                        </a>
                                                    <?php endif; ?>
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
        <?php else : ?>
            <div class="alert alert-warning text-center" role="alert">
                Tidak ada segmen untuk ditampilkan
            </div>
        <?php endif; ?>
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
                    <input type="hidden" class="form-control live" name="form_id">
                    <input type="hidden" class="form-control live" name="status" value="Berjalan">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Segmen</label>
                        <input type="text" class="form-control live" name="name" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control live" name="duedate" autocomplete="off" min="<?= date('Y-m-d') ?>" required>
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
                    <input type="hidden" class="form-control" name="status" value="Berjalan">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Unit</label>
                        <input type="text" class="form-control live" name="name" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Unit</label>
                        <input type="text" class="form-control live" name="code" value="" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control live" name="duedate" min="<?= date('Y-m-d') ?>" required autocomplete="off">
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
                        <input type="text" class="form-control live" name="name" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Unit</label>
                        <input type="text" class="form-control live" name="code" value="" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select live" name="status">
                            <option value="" selected>Pilih status Segmen</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Tertunda">Tertunda</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control live" name="duedate" min="<?= date('Y-m-d') ?>" autocomplete="off" required>
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

<!-- Modal Edit Segment -->
<div class="modal fade" id="modalEditSegment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditSegmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Segmen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('/editSegment') ?>" method="POST" id="editSegment" novalidate>
                    <?php csrf_field(); ?>
                    <input type="hidden" class="form-control" name="segment_id">
                    <input type="hidden" class="form-control" name="form_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Segmen</label>
                        <input type="text" class="form-control live" name="name" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select live" name="status">
                            <option value="" selected>Pilih status Segmen</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Tertunda">Tertunda</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control live" name="duedate" min="<?= date('Y-m-d') ?>" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Edit Segmen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>