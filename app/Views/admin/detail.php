<?= $this->extend('admin/layout/template.php') ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="d-flex flex-row align-items-baseline">
        <a class="text-decoration-none" href="<?= route_to('admin') ?>"><i class="fa-solid fa-arrow-left fa-2x"></i></a>
        <h2 class="mx-4">User Detail</h2>
    </div>
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('pesan'); ?>
        </div>
    <?php endif; ?>
    <div class="row mt-4 align-content-center">
        <div class="col-lg-10">
            <div class="row">
                <div class="col-lg-4">
                    <img class="img-thumbnail" src="<?= base_url() . '/assets/img/' . $user->user_image ?>" alt="" width="200">
                </div>
                <div class="col-lg-8">
                    <form action="<?= route_to('updateUser') ?>" method="POST" enctype="multipart/form-data" id="formedit">
                        <?= csrf_field() ?>
                        <input type="hidden" name="userid" value="<?= $user->userid ?>">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : '' ?>" name="username" value="<?= (old('username') === null) ? $user->username : old('username') ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('username') ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Fullname</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control <?= ($validation->hasError('fullname')) ? 'is-invalid' : '' ?>" name="fullname" value="<?= (old('fullname') === null) ? $user->fullname : old('fullname') ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('fullname') ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" name="email" value="<?= (old('email') === null) ? $user->email : old('email') ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('email') ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="groupid">
                                    <?php foreach ($groups as $group) : ?>
                                        <?php if ($group->id == old('groupid')) : ?>
                                            <option value="<?= $group->id ?>" selected><?= $group->name ?></option>
                                        <?php elseif ($group->name == $user->groupname) : ?>
                                            <option value="<?= $group->id ?>" selected><?= $group->name ?></option>
                                        <?php else : ?>
                                            <option value="<?= $group->id ?>"><?= $group->name ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-5 row">
                            <label class="col-sm-3 col-form-label">Upload Picture</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control <?= ($validation->hasError('file')) ? 'is-invalid' : '' ?>" name="file">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('file') ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 text-center" id="buttoned">
                            <button class="btn btn-sm btn-primary px-lg-3 mx-2" type="submit">Save Changes</button>
                            <button class="btn btn-sm btn-primary px-lg-4 mx-2" type="button" onclick="previous(<?= htmlentities(json_encode($user)); ?>)">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>