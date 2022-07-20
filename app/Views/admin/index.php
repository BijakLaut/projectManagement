<?= $this->extend('admin/layout/template.php') ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2>User Management</h2>
    <div class="row mt-4">
        <div class="col-lg-8">
            <table class="table table-hover table-responsive">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <th scope="row"><?= $i++ ?></th>
                            <td><?= $user->username ?></td>
                            <td><?= $user->email ?></td>
                            <td><?= $user->groupname ?></td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-outline-info" href="<?= base_url('admin/' . $user->userid) ?>">Detail</a>
                                <a class="btn btn-sm btn-outline-danger" href="#">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>