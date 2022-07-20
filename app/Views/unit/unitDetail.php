<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2><?= $judul; ?></h2>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Unit - <?= $unit['code'] ?></li>
        </ol>
    </nav>
    <?php if (session()->getFlashdata(('pesan'))) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('pesan'); ?>
        </div>
    <?php elseif (session()->getFlashdata(('hapus'))) : ?>
        <div class="alert alert-warning" role="alert">
            <?= session()->getFlashdata('hapus'); ?>
        </div>
    <?php endif; ?>
    <div class="row ms-0 justify-content-start">
        <div class="detail-summary col-6">
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Progress Ruangan</span>
                </li>
                <li class="list-group-item col-6 d-flex flex-column justify-content-center">
                    <div class="progress gx-0">
                        <div class="progress-bar" role="progressbar" style="width: <?= $unit['progress'] ?>%;" aria-valuenow="<?= $unit['progress'] ?>" aria-valuemin="0" aria-valuemax="100"><?= $unit['progress'] ?>%</div>
                    </div>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Target Penyelesaian</span>
                </li>
                <li class="list-group-item col-6">
                    <span><?= $unit['duedate'] ?></span>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Status</span>
                </li>
                <li class="list-group-item col-6">
                    <?php if ($unit['status'] == 'Berjalan') :  ?>
                        <span class="text-primary"><?= $unit['status'] ?></span>
                    <?php elseif ($unit['status'] == 'Selesai') :  ?>
                        <span class="text-success"><?= $unit['status'] ?></span>
                    <?php elseif ($unit['status'] == 'Tertunda') :  ?>
                        <span class="text-danger"><?= $unit['status'] ?></span>
                    <?php endif; ?>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <?php if ($unit['status'] == 'Selesai') : ?>
                        <span>Keterangan</span>
                    <?php else : ?>
                        <span>Sisa Waktu</span>
                    <?php endif; ?>
                </li>
                <li class="list-group-item col-6">
                    <?php if ($unit['status'] == 'Selesai') :  ?>
                        <?php if ($unit['datediff'] < 0) : ?>
                            <span class="text-primary">Lebih awal <?= abs($unit['datediff']); ?> Hari</span>
                        <?php elseif ($unit['datediff'] == 0) : ?>
                            <span class="text-success">Tepat Waktu</span>
                        <?php elseif ($unit['datediff'] > 0) : ?>
                            <span class="text-danger">Terlambat <?= abs($unit['datediff']); ?> Hari</span>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if ($unit['datediff'] < 0) : ?>
                            <span class="text-danger">Terlambat <?= abs($unit['datediff']); ?> Hari</span>
                        <?php else : ?>
                            <?= $unit['datediff']; ?> Hari
                        <?php endif; ?>
                    <?php endif; ?>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>File Lampiran</span>
                </li>
                <li class="list-group-item col-6">
                    <div class="d-grid gap-2">
                        <a class="btn btn-sm btn-info" href="<?= base_url('uploadAttachment/' . 'unit/' . $unit['unit_id']) ?>">Lihat</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="dropdown col-4 justify-content-end">
            <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="width: 200px;">
                <?= $segment['name'] . ' - ' . $unit['name'] ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item disabled"><?= $segment['name'] ?></a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <?php foreach ($unitList as $units) : ?>
                    <li>
                        <a class="dropdown-item <?= ($units['name'] == $unit['name']) ? 'active disabled' : '' ?>" href="<?= base_url('/unitDetail/' . $units['unit_id'] . '/' . $units['segment_id']) ?>">
                            <?= $units['name'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="list-pekerjaan mt-5">
        <div class="header-list-pekerjaan d-flex flex-row align-items-center">
            <h3 class="me-4">Daftar Pekerjaan</h3>
            <?php if (in_groups('SuperAdmin')) : ?>
                <button class="btn btn-sm btn-primary my-2" type="button" data-bs-toggle="modal" data-bs-target="#modalTambahPekerjaan" data-unitname="<?= $unit['name'] ?>" data-unitid="<?= $unit['unit_id'] ?>">Tambah Pekerjaan</button>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php if (count($jobList) > 0) : ?>
                <div class="col-9">
                    <table class="table table-bordered mt-1 align-middle text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Progress</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($jobList as $job) : ?>
                                <tr>
                                    <th width="40"><?= $i; ?></th>
                                    <td width="150" class="text-start"><?= $job['name'] ?></td>
                                    <td width="150">
                                        <div class="progress gx-0">
                                            <div class="progress-bar" role="progressbar" style="width: <?= $job['progress'] . '%;' ?>" aria-valuenow="<?= $job['progress'] ?>" aria-valuemin="0" aria-valuemax="100"><?= $job['progress'] . '%' ?></div>
                                        </div>
                                    </td>
                                    <td width="80">
                                        <div class="row d-flex flex-lg-row justify-content-evenly">
                                            <a href="<?= base_url('jobDetail/' . $job['job_id'] . '/' . $unit['unit_id']) ?>" class="text-decoration-none btn btn-sm btn-warning col-lg-5">
                                                Detail
                                            </a>
                                            <div class="col-lg-5 justify-content-lg-center p-0">
                                                <form action="<?= base_url('/delete/job/' . $job['job_id'])  ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button class="btn btn-sm btn-danger col-lg-12" type="submit" onclick="return confirm('Hapus Pekerjaan? *Lampiran dan Forum di dalamnya akan terhapus* ')">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="alert alert-warning text-center mt-lg-3" role="alert">
                    Tidak ada pekerjaan untuk ditampilkan
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Tambah Pekerjaan -->
<div class="modal fade" id="modalTambahPekerjaan" data-bs-backdrop="static" data-bs-keyboard="false" data-unitname="<?= $unit['name'] ?>" tabindex="-1" aria-labelledby="modalTambahPekerjaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('addJob/' . $unit['unit_id'] . '/' . $unit['segment_id']) ?>" method="POST" id="addJob" novalidate>
                    <?php csrf_field(); ?>
                    <input type="hidden" class="form-control" name="unit_id" value="">
                    <input type="hidden" class="form-control" name="form_id" value="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Pekerjaan</label>
                        <input type="text" class="form-control live" id="name" name="name" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control live" id="duedate" name="duedate" min="<?= date('Y-m-d') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Tambah Pekerjaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>