<?=
$this->extend('layout/template');
?>

<?= $this->section('content'); ?>

<div class="container">
    <h2><?= $judul; ?></h2>
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('pesan'); ?>
        </div>
    <?php elseif (session()->getFlashdata('forumSuccess')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('forumSuccess'); ?>
        </div>
    <?php elseif (session()->getFlashdata('forumDeleted')) : ?>
        <div class="alert alert-warning" role="alert">
            <?= session()->getFlashdata('forumDeleted'); ?>
        </div>
    <?php endif; ?>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/unitDetail/' . $unit['unit_id'] . '/' . $unit['segment_id']); ?>" class="text-decoration-none">Detail Unit - <?= $unit['code'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $job['name'] ?></li>
        </ol>
    </nav>
    <div class="row ms-0 pt-lg-3 justify-content-start">
        <div class="detail-summary col-6">
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Progress Pekerjaan</span>
                </li>
                <li class="list-group-item col-6 d-flex flex-column justify-content-center">
                    <div class="progress gx-0">
                        <div class="progress-bar" role="progressbar" style="width: <?= $job['progress'] ?>%;" aria-valuenow="<?= $job['progress'] ?>" aria-valuemin="0" aria-valuemax="100"><?= $job['progress'] ?>%</div>
                    </div>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Target Penyelesaian</span>
                </li>
                <li class="list-group-item col-6">
                    <span> <?= date('d M Y', strtotime($job['duedate'])) ?></span>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Status</span>
                </li>
                <li class="list-group-item col-6">
                    <?php if ($job['status'] == 'Berjalan') :  ?>
                        <span class="text-primary"><?= $job['status'] ?></span>
                    <?php elseif ($job['status'] == 'Selesai') :  ?>
                        <span class="text-success"><?= $job['status'] ?></span>
                    <?php elseif ($job['status'] == 'Tertunda') :  ?>
                        <span class="text-danger"><?= $job['status'] ?></span>
                    <?php endif; ?>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <?php if ($job['status'] == 'Selesai') : ?>
                        <span>Keterangan</span>
                    <?php else : ?>
                        <span>Sisa Waktu</span>
                    <?php endif; ?>
                </li>
                <li class="list-group-item col-6">
                    <?php if ($job['status'] == 'Selesai') :  ?>
                        <?php if ($job['datediff'] < 0) : ?>
                            <span class="text-primary">Lebih awal <?= abs($job['datediff']); ?> Hari</span>
                        <?php elseif ($job['datediff'] == 0) : ?>
                            <span class="text-success">Tepat Waktu</span>
                        <?php elseif ($job['datediff'] > 0) : ?>
                            <span class="text-danger">Terlambat <?= abs($job['datediff']); ?> Hari</span>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if ($job['datediff'] < 0) : ?>
                            <span class="text-danger">Terlambat <?= abs($job['datediff']); ?> Hari</span>
                        <?php else : ?>
                            <?= $job['datediff']; ?> Hari
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
                        <a class="btn btn-sm btn-info" href="<?= base_url('uploadAttachment/' . 'job/' . $job['job_id']) ?>">Lihat</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-4">
            <button class="btn btn-sm btn-outline-info" style="width:200px;" data-bs-toggle="modal" data-bs-target="#modalEditJob" data-segmentid="<?= $unit['segment_id'] ?>" data-jobname="<?= $job['name'] ?>" onclick="populate(this, <?= htmlentities(json_encode($job)); ?>); ">Ubah Detail</button>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-success dropdown-toggle mt-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="width: 200px;">
                    <?= $job['name'] ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item disabled">Jenis Pekerjaan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <?php foreach ($jobList as $jobs) : ?>
                        <li>
                            <a class="dropdown-item <?= ($jobs['name'] == $job['name']) ? 'active disabled' : '' ?>" href="<?= base_url('/jobDetail/' . $jobs['job_id'] . '/' . $unit['unit_id']) ?>">
                                <?= $jobs['name'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col">
            <h3>Forum Koordinasi Lapangan</h3>
            <a class="btn btn-sm btn-primary mb-lg-3" style="width: 200px;" href="<?= base_url('createForum/' . $job['job_id']) ?>">Buat Diskusi</a>
            <?php if (count($forums) > 0) : ?>
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <th>Topik Diskusi</th>
                        <th>Dimulai oleh</th>
                        <th>Balasan</th>
                    </thead>
                    <tbody>
                        <?php foreach ($forums as $forum) : ?>
                            <tr>
                                <td class="text-start">
                                    <a href="<?= base_url('detailForum/' . $forum['forum_id']) ?>" class="text-decoration-none"><?= $forum['topic'] ?></a>
                                </td>
                                <td>
                                    <div class="topic-author d-flex flex-row align-items-center justify-content-center">
                                        <img src="<?= base_url() . '/assets/img/' . $forum['user_image'] ?>" alt="mdo" width="32" height="32" class="rounded-circle">
                                        <div class="name-time d-flex flex-column ms-3">
                                            <span><?= ($forum['fullname'] === null) ? $forum['username'] : $forum['fullname'] ?></span>
                                            <span><?= date('d M Y', strtotime($forum['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <?php
                                $i = 0;
                                foreach ($replies as $reply) {
                                    if ($reply['forum_parent'] == $forum['forum_id']) {
                                        $i++;
                                    }
                                } ?>
                                <td><?= $i ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="alert alert-warning text-center" role="alert">
                    Tidak ada forum untuk ditampilkan
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Edit Pekerjaan -->
<div class="modal fade" id="modalEditJob" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditJobLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('/editJob') ?>" method="POST" id="editJob" novalidate>
                    <?php csrf_field(); ?>
                    <input type="hidden" class="form-control" name="job_id" value="">
                    <input type="hidden" class="form-control" name="unit_id" value="">
                    <input type="hidden" class="form-control" name="form_id" value="">
                    <input type="hidden" class="form-control" name="segment_id" value="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Pekerjaan</label>
                        <input type="text" class="form-control live" name="name" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select live" name="status" onchange="enableProgress()">
                            <option value="" selected>Pilih status Segmen</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Tertunda">Tertunda</option>
                            <?php if ($job['status'] == 'Selesai') : ?>
                                <option value="Selesai">Selesai</option>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="progress" class="form-label">Progress Pekerjaan</label>
                        <input type="number" class="form-control live" name="progress" <?= ($job['status'] == 'Berjalan') ? '' : 'disabled' ?> value="" min="0" max="100" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="duedate" class="form-label">Target Penyelesaian</label>
                        <input type="date" class="form-control live" name="duedate" min="<?= date('Y-m-d') ?>" autocomplete="off" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Ubah Detail</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>