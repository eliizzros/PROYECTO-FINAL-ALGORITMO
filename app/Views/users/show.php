<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Users.show_title') ?></h1>
    <p><a href="<?= site_url('users') ?>"><?= lang('Users.back_to_index') ?></a></p>

    <p><strong><?= lang('App.name') ?>:</strong><br><?= esc($user['name']) ?></p>
    <p><strong><?= lang('App.email') ?>:</strong><br><?= esc($user['email']) ?></p>
    <p><strong><?= lang('Users.active') ?>:</strong><br><?= $user['active'] ? lang('Users.yes') : lang('Users.no') ?></p>
    <p><strong><?= lang('Users.administrator') ?>:</strong><br><?= $user['is_admin'] ? lang('Users.yes') : lang('Users.no') ?></p>
    <p><strong><?= lang('Users.created_at') ?>:</strong><br><?= esc($user['created_at']) ?></p>
    <p><strong><?= lang('Users.updated_at') ?>:</strong><br><?= esc($user['updated_at']) ?></p>

    <p>
        <a href="<?= site_url('users/' . $user['id'] . '/edit') ?>"><?= lang('App.edit') ?></a> |
        <a href="<?= site_url('users/' . $user['id'] . '/delete') ?>"><?= lang('Users.delete') ?></a>
    </p>
<?= $this->endSection() ?>