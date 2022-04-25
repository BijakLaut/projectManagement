<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2><?= $judul; ?></h2>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/detailRuangan'); ?>">Detail Ruangan - 01UA</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pemasangan Bata</li>
        </ol>
    </nav>
    <div class="row ms-0 justify-content-start">
        <div class="detail-summary col-6">
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Progress Pekerjaan</span>
                </li>
                <li class="list-group-item col-6 d-flex flex-column justify-content-center">
                    <div class="progress gx-0">
                        <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                    </div>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Target Penyelesaian</span>
                </li>
                <li class="list-group-item col-6">
                    <span class="text-danger">7 April 2022</span>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Sisa Waktu</span>
                </li>
                <li class="list-group-item col-6">
                    <span class="text-danger">7 Hari</span>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Gambar Pekerjaan</span>
                </li>
                <li class="list-group-item col-6">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="modal" data-bs-target="#modalGambarPekerjaan">Lihat</button>
                    </div>
                </li>
            </ul>
            <ul class="list-group list-group-horizontal-md row">
                <li class="list-group-item col-6">
                    <span>Metode Pekerjaan</span>
                </li>
                <li class="list-group-item col-6">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-info" type="button">Lihat</button>
                    </div>
                </li>
            </ul>
        </div>
        <div class="dropdown col-4 justify-content-end">
            <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="width: 200px;">
                Pemasangan Bata
            </button>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton1">
                <li>
                    <h4 class="dropdown-header">Jenis Pekerjaan</h4>
                </li>
                <li><a class="dropdown-item disabled" href="#">Pemasangan Bata</a></li>
                <li><a class="dropdown-item" href="#">Plester Dinding</a></li>
                <li><a class="dropdown-item" href="#">Pengacian Dinding</a></li>
                <li><a class="dropdown-item" href="#">Pengecatan Dinding</a></li>
            </ul>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col">
            <h3>Forum Koordinasi Lapangan</h3>
            <button class="btn btn-sm btn-primary" type="button" style="width: 200px;">Buat Diskusi</button>

            <table class="table table-hover align-middle text-center">
                <thead>
                    <th>Topik Diskusi</th>
                    <th>Dimulai oleh</th>
                    <th>Balasan</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start">
                            <a href="#">Penyelesaian Pemasangan Bata</a>
                        </td>
                        <td>
                            <div class="topic-author d-flex flex-row align-items-center justify-content-center">
                                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                                <div class="name-time d-flex flex-column ms-3">
                                    <span>Nama Author</span>
                                    <span>DD-MMM-YYYY</span>
                                </div>
                            </div>
                        </td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td class="text-start">
                            <a href="#">Pemasangan Bata ZZZ</a>
                        </td>
                        <td>
                            <div class="topic-author d-flex flex-row align-items-center justify-content-center">
                                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                                <div class="name-time d-flex flex-column ms-3">
                                    <span>Nama Author</span>
                                    <span>DD-MMM-YYYY</span>
                                </div>
                            </div>
                        </td>
                        <td>16</td>
                    </tr>
                    <tr>
                        <td class="text-start">
                            <a href="#">Pemasangan Bata YYY</a>
                        </td>
                        <td>
                            <div class="topic-author d-flex flex-row align-items-center justify-content-center">
                                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                                <div class="name-time d-flex flex-column ms-3">
                                    <span>Nama Author</span>
                                    <span>DD-MMM-YYYY</span>
                                </div>
                            </div>
                        </td>
                        <td>22</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Modal Gambar Pekerjaan -->
<div class="modal fade" id="modalGambarPekerjaan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalGambarPekerjaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGambarPekerjaanLabel">Daftar Gambar Pekerjaan - Pemasangan Bata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <label for="unggahGambarPekerjaan" class="form-label">Unggah Berkas</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="unggahGambarPekerjaan" aria-describedby="inputgroupspk" aria-label="Upload" name="GambarPekerjaan">
                        <button class="btn btn-outline-primary" type="submit" id="inputGambarPekerjaan">Unggah</button>
                    </div>
                </form>
                <ul class="list-group my-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Gambar Pemasangan Bata A</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Gambar Pemasangan Bata B</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Gambar Pemasangan Bata C</span>
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

<?= $this->endSection(); ?>