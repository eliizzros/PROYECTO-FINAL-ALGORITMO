<?php

namespace App\Controllers;

class Lang extends BaseController
{
    public function set(string $locale)
    {
        if (in_array($locale, ['en', 'es'], true)) {
            session()->set('locale', $locale);
        }

        return redirect()->to('/');
    }
}