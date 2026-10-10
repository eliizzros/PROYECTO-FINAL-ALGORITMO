<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $helpers = ['url', 'form'];

    public function index(): string
    {
        $user = (new UserModel())->find(session()->get('user_id'));

        return view('profile/index', ['user' => $user]);
    }
}