<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Tasks.new_title') ?></h1>

    <form action="<?= site_url('tasks/new') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label for="description"><?= lang('Tasks.description') ?></label>
        <input type="text" id="description" name="description" maxlength="255"
               value="<?= esc(old('description')) ?>">

        <div class="actions">
            <button type="submit"><?= lang('App.send') ?></button>
            <a href="<?= site_url('tasks') ?>"><?= lang('App.cancel') ?></a>
        </div>
    </form>
<?= $this->endSection() ?>