<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('App.profile') ?></h1>

    <img src="<?= esc($avatar, 'attr') ?>" alt="" class="avatar">

    <?php if (! empty($user['avatar'])): ?>
        <p>
            <a href="<?= site_url('profile/image/delete') ?>" class="btn btn-danger">
                <?= lang('Profile.delete_image') ?>
            </a>
        </p>
    <?php endif ?>

    <p><strong><?= lang('App.name') ?>:</strong><br><?= esc($user['name']) ?></p>
    <p><strong><?= lang('App.email') ?>:</strong><br><?= esc($user['email']) ?></p>

    <a href="<?= site_url('profile/edit/confirm') ?>" class="btn"><?= lang('App.edit') ?></a>
    <a href="<?= site_url('profile/password') ?>" class="btn"><?= lang('App.change_password') ?></a>
    <a href="<?= site_url('profile/image') ?>" class="btn"><?= lang('Profile.change_image') ?></a>
<?= $this->endSection() ?>