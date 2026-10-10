<div style="font-family: Arial, sans-serif; max-width: 480px;">
    <h2><?= lang('App.brand') ?></h2>
    <p><?= lang('App.mail_activation_body') ?></p>
    <p>
        <a href="<?= esc($url, 'attr') ?>"
           style="background:#16a34a;color:#fff;padding:10px 18px;border-radius:6px;text-decoration:none;">
            <?= lang('App.mail_activation_button') ?>
        </a>
    </p>
    <p style="color:#666;font-size:12px;"><?= lang('App.mail_ignore') ?></p>
</div>