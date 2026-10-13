<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('App.change_password_title') ?></h1>

    <form action="<?= site_url('profile/password') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="current_password"><?= lang('App.current_password') ?></label>
        <input type="password" id="current_password" name="current_password">

        <label for="new_password"><?= lang('App.new_password') ?></label>
        <input type="password" id="new_password" name="new_password">

        <label for="new_password2"><?= lang('App.repeat_new_password') ?></label>
        <input type="password" id="new_password2" name="new_password2">

        <div class="actions">
            <button type="submit"><?= lang('App.save') ?></button>
            <a href="<?= site_url('profile') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>