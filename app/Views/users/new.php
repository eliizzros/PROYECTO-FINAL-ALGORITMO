<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Users.new_title') ?></h1>
    <p><a href="<?= site_url('users') ?>"><?= lang('Users.back_to_index') ?></a></p>

    <form action="<?= site_url('users/new') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="name"><?= lang('App.name') ?></label>
        <input type="text" id="name" name="name" value="<?= esc(old('name')) ?>">

        <label for="email"><?= lang('App.email') ?></label>
        <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>">

        <label for="password"><?= lang('App.password') ?></label>
        <input type="password" id="password" name="password">

        <label for="password2"><?= lang('App.repeat_password') ?></label>
        <input type="password" id="password2" name="password2">

        <div class="actions">
            <button type="submit"><?= lang('App.save') ?></button>
            <a href="<?= site_url('users') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>