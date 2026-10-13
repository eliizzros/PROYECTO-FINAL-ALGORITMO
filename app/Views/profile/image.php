<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Profile.change_image_title') ?></h1>

    <form action="<?= site_url('profile/image') ?>" method="post" enctype="multipart/form-data" class="form">
        <?= csrf_field() ?>

        <label for="avatar"><?= lang('Profile.choose_image') ?></label>
        <input type="file" id="avatar" name="avatar" accept="image/png,image/jpeg,image/webp">

        <div class="actions">
            <button type="submit"><?= lang('App.save') ?></button>
            <a href="<?= site_url('profile') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>