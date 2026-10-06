<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <title><?= lang('App.brand') ?></title>
</head>
<body>
    <nav>
        <a href="<?= site_url('/') ?>"><?= lang('App.home') ?></a> |
        <a href="<?= site_url('lang/en') ?>"><?= lang('App.english') ?></a> |
        <a href="<?= site_url('lang/es') ?>"><?= lang('App.spanish') ?></a>
        &nbsp;&nbsp;&nbsp;
        <a href="#"><?= lang('App.signup') ?></a> |
        <a href="#"><?= lang('App.login') ?></a>
    </nav>

    <h1><?= lang('App.welcome') ?></h1>
    <p><?= lang('App.welcome_text') ?></p>
</body>
</html>