<!-- Modal Doc/Img-->
<div class="modal fade" id="modalDoc" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDocLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDocLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <label for="unggahBerkas" class="form-label">Unggah Berkas</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="unggahBerkas" aria-describedby="inputGroupFile" aria-label="Upload">
                        <button class="btn btn-outline-primary" type="submit" id="inputGroupFile">Unggah</button>
                    </div>
                </form>
                <ul class="list-group my-3">
                    <!-- <?php foreach ($a as $b) : ?> -->
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="col">Surat Perintah Kerja Maret 2022</span>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger me-2" type="button">Hapus</button>
                            <button class="btn btn-sm btn-outline-warning me-0" type="button">Unduh</button>
                        </div>
                    </li>
                    <!-- <?php endforeach; ?> -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Segmen (Tambah-Ubah) -->
<div class="modal fade" id="modalSegment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSegmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSegmentLabel"></h5>
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