<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Tasks.edit_title') ?></h1>

    <form action="<?= site_url('tasks/' . $task['id'] . '/edit') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="description"><?= lang('Tasks.description') ?></label>
        <input type="text" id="description" name="description" maxlength="255"
               value="<?= esc(old('description', null, false) ?? $task['description']) ?>">

        <div class="actions">
            <button type="submit"><?= lang('App.save') ?></button>
            <a href="<?= site_url('tasks/' . $task['id']) ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>