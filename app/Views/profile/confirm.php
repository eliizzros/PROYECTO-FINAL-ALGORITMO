        <?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('App.confirm_title') ?></h1>

    <form action="<?= site_url('profile/edit/confirm') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="password"><?= lang('App.password') ?></label>
        <input type="password" id="password" name="password">

        <div class="actions">
            <button type="submit"><?= lang('App.send') ?></button>
            <a href="<?= site_url('profile') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>