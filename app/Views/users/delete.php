<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Users.delete_title') ?></h1>
    <p><?= lang('Users.are_you_sure') ?></p>
    <p><strong><?= esc($user['name']) ?></strong> (<?= esc($user['email']) ?>)</p>

    <form action="<?= site_url('users/' . $user['id'] . '/delete') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <div class="actions">
            <button type="submit" class="btn-danger"><?= lang('Users.delete') ?></button>
            <a href="<?= site_url('users/' . $user['id']) ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>