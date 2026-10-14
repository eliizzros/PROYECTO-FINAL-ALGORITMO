<?php
// Si hubo un error de validación se conserva lo escrito; si no, se usan los datos guardados
$hasOld = old('name', null, false) !== null;
$active = $hasOld ? (old('active', null, false) !== null) : (bool) $user['active'];
$admin  = $hasOld ? (old('is_admin', null, false) !== null) : (bool) $user['is_admin'];
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Users.edit_title') ?></h1>
    <p><a href="<?= site_url('users') ?>"><?= lang('Users.back_to_index') ?></a></p>

    <form action="<?= site_url('users/' . $user['id'] . '/edit') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="name"><?= lang('App.name') ?></label>
        <input type="text" id="name" name="name"
               value="<?= esc(old('name', null, false) ?? $user['name']) ?>">

        <label for="email"><?= lang('App.email') ?></label>
        <input type="email" id="email" name="email"
               value="<?= esc(old('email', null, false) ?? $user['email']) ?>">

        <label for="password"><?= lang('App.password') ?></label>
        <input type="password" id="password" name="password">
        <small><?= lang('Users.password_hint') ?></small>

        <label for="password2"><?= lang('App.repeat_password') ?></label>
        <input type="password" id="password2" name="password2">

        <label class="check">
            <input type="checkbox" name="active" value="1" <?= $active ? 'checked' : '' ?> <?= $isSelf ? 'disabled' : '' ?>>
            <?= lang('Users.active') ?>
        </label>

        <label class="check">
            <input type="checkbox" name="is_admin" value="1" <?= $admin ? 'checked' : '' ?> <?= $isSelf ? 'disabled' : '' ?>>
            <?= lang('Users.administrator') ?>
        </label>

        <div class="actions">
            <button type="submit"><?= lang('App.save') ?></button>
            <a href="<?= site_url('users/' . $user['id']) ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>