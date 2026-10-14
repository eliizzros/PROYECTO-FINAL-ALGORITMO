<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1><?= lang('Tasks.title') ?></h1>

    <p><a href="<?= site_url('tasks/new') ?>" class="btn"><?= lang('Tasks.new_task') ?></a></p>

    <form id="search-form" action="<?= site_url('tasks') ?>" method="get" class="search">
        <label for="q"><?= lang('Tasks.search') ?></label>
        <input type="text" id="q" name="q" value="<?= esc($q) ?>" autocomplete="off">
    </form>

    <?php if (empty($tasks)): ?>
        <p><?= lang('Tasks.no_tasks') ?></p>
    <?php else: ?>
        <ul class="task-list">
            <?php foreach ($tasks as $t): ?>
                <li><a href="<?= site_url('tasks/' . $t['id']) ?>"><?= esc($t['description']) ?></a></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

    <div class="pager">
        <a href="<?= site_url('tasks') . '?' . http_build_query(['q' => $q, 'page' => $page - 1]) ?>"
           class="btn <?= $hasNewer ? '' : 'disabled' ?>"><?= lang('Tasks.newer') ?></a>
        <a href="<?= site_url('tasks') . '?' . http_build_query(['q' => $q, 'page' => $page + 1]) ?>"
           class="btn <?= $hasOlder ? '' : 'disabled' ?>"><?= lang('Tasks.older') ?></a>
    </div>

    <script>
        // Filtrado dinámico: busca solo, medio segundo después de dejar de escribir
        (function () {
            var input = document.getElementById('q');
            var form  = document.getElementById('search-form');
            var timer;

            input.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(function () { form.submit(); }, 500);
            });

            // Tras recargar, deja el cursor al final del texto
            if (input.value) {
                input.focus();
                input.setSelectionRange(input.value.length, input.value.length);
            }
        })();
    </script>
<?= $this->endSection() ?>