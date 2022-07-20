<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2>Forum Koordinasi Pekerjaan</h2>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('/unitDetail/' . $breadcrumb['unit_id'] . '/' . $breadcrumb['segment_id']); ?>" class="text-decoration-none">Detail Unit - <?= $breadcrumb['unit_code'] ?></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('/jobDetail/' . $breadcrumb['job_id'] . '/' . $breadcrumb['unit_id']); ?>" class="text-decoration-none"><?= $breadcrumb['job_name']; ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Forum - <?= $forumDetail['topic']; ?>
            </li>
        </ol>
    </nav>
    <?php if (session()->getFlashdata('forumEdited')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('forumEdited'); ?>
        </div>
    <?php elseif (session()->getFlashdata('replySuccess')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('replySuccess'); ?>
        </div>
    <?php elseif (session()->getFlashdata('replyDeleted')) : ?>
        <div class="alert alert-warning" role="alert">
            <?= session()->getFlashdata('replyDeleted'); ?>
        </div>
    <?php endif; ?>
    <div class="row justify-content-center">
        <div class="col-lg-9 mt-lg-5">
            <div class="card border-primary p-lg-3">
                <div class="card-body">
                    <h5 class="card-title"><?= $forumDetail['topic'] ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        oleh <span class="text-primary"><?= ($forumDetail['user_name'] !== null) ? $forumDetail['user_name'] : $forumDetail['user_username'] ?></span> pada <?= date('d M Y, H:i:s', strtotime($forumDetail['forum_updated'])) ?>
                    </h6>
                    <p class="card-text my-lg-5" style="text-align: justify;"><?= $forumDetail['forum_description'] ?></p>
                    <?php if ($forumDetail['forum_att'] != 0) : ?>
                        <button class="btn btn-sm btn-outline-primary px-lg-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAtt" aria-expanded="false" aria-controls="collapseAtt">
                            Lampiran
                        </button>
                        <ul class="collapse list-group col-lg-7 mt-lg-3" id="collapseAtt">
                            <?php foreach ($attachments as $attachment) : ?>
                                <?php if ($attachment['parent_id'] == $forumDetail['forum_id'] && $attachment['parent'] == 'forum') : ?>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>
                                            <div><?= $attachment['name'] ?></div>
                                        </div>
                                        <div>
                                            <a class="btn btn-sm btn-primary" href="<?= base_url('forumAttDownload/' . $attachment['name']) ?>">Unduh</a>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-row justify-content-end">
                    <a href="<?= base_url('createReply/' . $forumDetail['forum_id']) ?>" class="card-link text-decoration-none">Balas Diskusi</a>
                    <?php if (user()->id == $forumDetail['user_id']) : ?>
                        <a href="<?= base_url('editForum/' . $forumDetail['forum_id']) ?>" class="card-link text-decoration-none">Edit Forum</a>
                        <form id="deleteForum" action="<?= base_url('deleteForum/' . $forumDetail['forum_id']) ?>" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <a href="#" class="card-link text-danger text-decoration-none mx-lg-3" onclick="submit('forum');">
                                Hapus Diskusi
                            </a>
                        </form>
                    <?php endif; ?>
                    <?php if (user()->id != $forumDetail['user_id']) : ?>
                        <a href="#" class="card-link text-decoration-none text-danger">Laporkan</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 1 ?>
    <?php foreach ($replies as $reply) : ?>
        <div class="row">
            <div class="col mt-lg-5">
                <div class="card border-success p-lg-3">
                    <div class="card-body">
                        <h5 class="card-title">Balasan: <?= $forumDetail['topic'] ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            oleh <span class="text-primary"><?= ($reply['user_name'] !== null) ? $reply['user_name'] : $reply['user_username'] ?></span> pada <?= date('d M Y, H:i:s', strtotime($reply['updated_at'])) ?>
                        </h6>
                        <p class="card-text my-lg-5" style="text-align: justify;"><?= $reply['description'] ?></p>
                        <?php if ($reply['has_attachment'] != 0) : ?>
                            <button class="btn btn-sm btn-outline-primary px-lg-4" type="button" data-bs-toggle="collapse" data-bs-target="#replycollapse<?= $i; ?>" aria-expanded="false" aria-controls="replycollapse">
                                Lampiran
                            </button>
                            <ul class="collapse list-group col-lg-7 mt-lg-3" id="replycollapse<?= $i; ?>">
                                <?php foreach ($attachments as $attachment) : ?>
                                    <?php if ($attachment['parent_id'] == $reply['reply_id'] && $attachment['parent'] == 'reply') : ?>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <div><?= $attachment['name'] ?></div>
                                            </div>
                                            <div>
                                                <a class="btn btn-sm btn-primary" href="<?= base_url('forumAttDownload/' . $attachment['name']) ?>">Unduh</a>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-row justify-content-end">
                        <?php if (user()->id == $reply['author_id']) : ?>
                            <a href="<?= base_url('editReply/' . $reply['reply_id']) ?>" class="card-link text-decoration-none">Edit Balasan</a>
                            <form action="<?= base_url('deleteReply/' . $reply['reply_id']) ?>" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <a href="#" class="card-link text-danger text-decoration-none mx-lg-3" onclick="submit('balasan');">
                                    Hapus Balasan
                                </a>
                            </form>
                        <?php endif; ?>
                        <?php if (user()->id != $reply['author_id']) : ?>
                            <a href="#" class="card-link text-decoration-none text-danger">Laporkan</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php $i++; ?>
    <?php endforeach; ?>
</div>
<script>
    function submit($target) {
        if (confirm("Hapus " + $target + "?")) {
            $(event.target).parents('form').submit();
        }
    }
</script>
<?= $this->endsection(); ?>