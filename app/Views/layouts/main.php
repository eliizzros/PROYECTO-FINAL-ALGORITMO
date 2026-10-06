<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= lang('App.brand') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <nav>
        <div>
            <a href="<?= site_url('/') ?>"><?= lang('App.home') ?></a>
            <a href="<?= site_url('lang/en') ?>"><?= lang('App.english') ?></a>
            <a href="<?= site_url('lang/es') ?>"><?= lang('App.spanish') ?></a>
        </div>
        <div>
            <a href="<?= site_url('register') ?>"><?= lang('App.signup') ?></a>
            <a href="#"><?= lang('App.login') ?></a>
        </div>
    </nav>

    <main>
        <?php if ($msg = session()->getFlashdata('success')): ?>
            <div class="alert ok"><?= esc($msg) ?></div>
        <?php endif ?>

        <?php if ($errs = session()->getFlashdata('errors')): ?>
            <div class="alert err">
                <ul>
                    <?php foreach ((array) $errs as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>