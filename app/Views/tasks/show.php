<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Tasks.show_title') ?></h1>
    <p><a href="<?= site_url('tasks') ?>"><?= lang('Tasks.back_to_index') ?></a></p>

    <p><strong>ID:</strong><br><?= esc($task['id']) ?></p>
    <p><strong><?= lang('Tasks.description') ?>:</strong><br><?= esc($task['description']) ?></p>
    <p><strong><?= lang('Tasks.created_at') ?>:</strong><br><?= esc($task['created_at']) ?></p>
    <p><strong><?= lang('Tasks.updated_at') ?>:</strong><br><?= esc($task['updated_at']) ?></p>

    <p>
        <a href="<?= site_url('tasks/' . $task['id'] . '/edit') ?>"><?= lang('App.edit') ?></a> |
        <a href="<?= site_url('tasks/' . $task['id'] . '/delete') ?>"><?= lang('Tasks.delete') ?></a>
    </p>
<?= $this->endSection() ?>