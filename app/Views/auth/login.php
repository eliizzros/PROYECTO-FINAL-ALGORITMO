<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('App.login_title') ?></h1>

    <form action="<?= site_url('login') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="email"><?= lang('App.email') ?></label>
        <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>">

        <label for="password"><?= lang('App.password') ?></label>
        <input type="password" id="password" name="password">

        <div class="actions">
            <button type="submit"><?= lang('App.login') ?></button>
            <a href="<?= site_url('/') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>