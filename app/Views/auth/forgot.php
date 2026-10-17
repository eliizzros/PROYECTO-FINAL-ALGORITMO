<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Auth.forgot_title') ?></h1>

    <form action="<?= site_url('forgot') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="email"><?= lang('App.email') ?></label>
        <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>">

        <div class="actions">
            <button type="submit"><?= lang('App.send') ?></button>
            <a href="<?= site_url('login') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>