<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('App.edit_profile_title') ?></h1>

    <form action="<?= site_url('profile/edit') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="name"><?= lang('App.name') ?></label>
        <input type="text" id="name" name="name"
               value="<?= esc(old('name', null, false) ?? $user['name']) ?>">

        <label for="email"><?= lang('App.email') ?></label>
        <input type="email" id="email" name="email"
               value="<?= esc(old('email', null, false) ?? $user['email']) ?>">

        <div class="actions">
            <button type="submit"><?= lang('App.save') ?></button>
            <a href="<?= site_url('profile') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>