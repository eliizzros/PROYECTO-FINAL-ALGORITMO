<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Profile.delete_image') ?></h1>
    <p><?= lang('Profile.are_you_sure') ?></p>

    <form action="<?= site_url('profile/image/delete') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <div class="actions">
            <button type="submit" class="btn-danger"><?= lang('Profile.delete') ?></button>
            <a href="<?= site_url('profile') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>