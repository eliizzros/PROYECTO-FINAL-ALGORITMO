<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Users.title') ?></h1>

    <p><a href="<?= site_url('users/new') ?>" class="btn"><?= lang('Users.new_user') ?></a></p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th><?= lang('App.name') ?></th>
                    <th><?= lang('App.email') ?></th>
                    <th><?= lang('Users.active') ?></th>
                    <th><?= lang('Users.administrator') ?></th>
                    <th><?= lang('Users.created_at') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><a href="<?= site_url('users/' . $u['id']) ?>"><?= esc($u['name']) ?></a></td>
                        <td><?= esc($u['email']) ?></td>
                        <td><?= $u['active'] ? lang('Users.yes') : lang('Users.no') ?></td>
                        <td><?= $u['is_admin'] ? lang('Users.yes') : lang('Users.no') ?></td>
                        <td><?= esc($u['created_at']) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="pager">
        <a href="<?= site_url('users?page=' . ($page - 1)) ?>"
            class="btn <?= $hasNewer ? '' : 'disabled' ?>"><?= lang('Users.newer') ?></a>
        <a href="<?= site_url('users?page=' . ($page + 1)) ?>"
        class="btn <?= $hasOlder ? '' : 'disabled' ?>"><?= lang('Users.older') ?></a>
    </div>
<?= $this->endSection() ?>