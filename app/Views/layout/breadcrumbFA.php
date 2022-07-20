<?php if ($parent['parent'] == 'project') : ?>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>" class="text-decoration-none">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                File Lampiran
            </li>
        </ol>
    </nav>
<?php elseif ($parent['parent'] == 'unit') : ?>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>" class="text-decoration-none">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('unitDetail/' . $breadcrumb['unit_id'] . '/' . $breadcrumb['segment_id']); ?>" class="text-decoration-none">
                    Detail Unit - <?= $breadcrumb['code'] ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                File Lampiran
            </li>
        </ol>
    </nav>
<?php else : ?>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>" class="text-decoration-none">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('unitDetail/' . $breadcrumb['unit_id'] . '/' . $breadcrumb['segment_id']); ?>" class="text-decoration-none">
                    Detail Unit - <?= $breadcrumb['code'] ?>
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('jobDetail/' . $breadcrumb['job_id'] . '/' . $breadcrumb['unit_id']); ?>" class="text-decoration-none">
                    <?= $breadcrumb['job_name'] ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                File Lampiran
            </li>
        </ol>
    </nav>
<?php endif; ?>