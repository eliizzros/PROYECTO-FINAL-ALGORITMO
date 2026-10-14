<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Tasks.delete_title') ?></h1>
    <p><?= lang('Tasks.are_you_sure') ?></p>
    <p><strong><?= esc($task['description']) ?></strong></p>

    <form action="<?= site_url('tasks/' . $task['id'] . '/delete') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <div class="actions">
            <button type="submit" class="btn-danger"><?= lang('Tasks.delete') ?></button>
            <a href="<?= site_url('tasks/' . $task['id']) ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>