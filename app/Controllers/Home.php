<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $helpers = ['url'];

    public function index(): string
    {
        $locale = session()->get('locale') ?? 'es';
        service('request')->setLocale($locale);
        service('language')->setLocale($locale);

        return view('home/index');
    }
}