<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = ['url', 'form'];

    public function registerForm(): string
    {
        return view('auth/register');
    }

    public function register()
    {
        $name = lang('App.name');
        $mail = lang('App.email');
        $pass = lang('App.password');
        $rep  = lang('App.repeat_password');

        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'password'  => 'required|min_length[8]|max_length[72]',
            'password2' => 'required|matches[password]',
        ];

        $messages = [
            'name' => [
                'required'   => lang('App.v_required', [$name]),
                'min_length' => lang('App.v_min', [$name, 3]),
            ],
            'email' => [
                'required'    => lang('App.v_required', [$mail]),
                'valid_email' => lang('App.v_email'),
                'is_unique'   => lang('App.v_unique'),
            ],
            'password' => [
                'required'   => lang('App.v_required', [$pass]),
                'min_length' => lang('App.v_min', [$pass, 8]),
            ],
            'password2' => [
                'required' => lang('App.v_required', [$rep]),
                'matches'  => lang('App.v_matches'),
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new UserModel())->insert([
            'name'     => trim($this->request->getPost('name')),
            'email'    => trim($this->request->getPost('email')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'active'   => 0,
            'is_admin' => 0,
        ]);

        return redirect()->to('/')->with('success', lang('App.registered'));
    }
}