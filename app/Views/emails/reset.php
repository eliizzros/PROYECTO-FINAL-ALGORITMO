<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Auth.reset_title') ?></h1>

    <form action="<?= site_url('reset/' . esc($token, 'url')) ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="password"><?= lang('App.password') ?></label>
        <input type="password" id="password" name="password">

        <label for="password2"><?= lang('App.repeat_password') ?></label>
        <input type="password" id="password2" name="password2">

        <div class="actions">
            <button type="submit"><?= lang('Auth.reset_button') ?></button>
        </div>
    </form>
<?= $this->endSection() ?>