<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('App.profile') ?></h1>

    <p><strong><?= lang('App.name') ?>:</strong><br><?= esc($user['name']) ?></p>
    <p><strong><?= lang('App.email') ?>:</strong><br><?= esc($user['email']) ?></p>
<?= $this->endSection() ?>