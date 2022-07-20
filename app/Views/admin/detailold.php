<?= $this->extend('admin/templates/index.php') ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h2>Detail User</h2>
    <div class="row mt-4">
        <div class="col-lg-8">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <!-- <th scope="col">#</th> -->
                        <th scope="col">Username</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Profile Image</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $user->username ?></td>
                        <td><?= $user->fullname ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->groupname ?></td>
                        <td><img src="<?= base_url('assets/img') . '/' . $user->user_image  ?>" class="img-fluid"></td>
                        <td><a class="btn btn-sm btn-outline-info" href="<?= base_url('edit/' . $user->userid) ?>">Edit</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>