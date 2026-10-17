<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = ['url', 'form'];

    // REGISTRO
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
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generar token de activación
        $token = bin2hex(random_bytes(32));

        $users = new UserModel();

        $users->insert([
            'name'             => trim($this->request->getPost('name')),
            'email'            => trim($this->request->getPost('email')),
            'password'         => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'active'           => 0,
            'is_admin'         => 0,
            'activation_token' => hash('sha256', $token),
        ]);

        $url = site_url('activate/' . $token);

        log_message('info', 'Activation link: ' . $url);

        $sent = $this->sendMail(
            trim($this->request->getPost('email')),
            lang('App.mail_activation_subject'),
            view('emails/activation', ['url' => $url])
        );

        if (! $sent) {
            return redirect()->to('/login')
                ->with('errors', [lang('App.mail_failed')]);
        }

        return redirect()->to('/login')
            ->with('success', lang('App.registered'));
    }
// MOSTRAR FORMULARIO DE INICIO DE SESIÓN
public function loginForm(): string
{
    return view('auth/login');
}
    // INICIO DE SESIÓN
    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        $messages = [
            'email' => [
                'required'    => lang('App.v_required', [lang('App.email')]),
                'valid_email' => lang('App.v_email'),
            ],
            'password' => [
                'required' => lang('App.v_required', [lang('App.password')]),
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $users = new UserModel();

        $user = $users->where(
            'email',
            trim($this->request->getPost('email'))
        )->first();

        if (
            ! $user ||
            ! password_verify(
                $this->request->getPost('password'),
                $user['password']
            )
        ) {
            return redirect()->back()
                ->withInput()
                ->with('errors', [lang('App.bad_credentials')]);
        }

        if (! $user['active']) {
            return redirect()->back()
                ->withInput()
                ->with('errors', [lang('App.not_active')]);
        }

        session()->set([
            'user_id'  => $user['id'],
            'name'     => $user['name'],
            'is_admin' => (bool) $user['is_admin'],
        ]);

        session()->regenerate(true);

        $response = redirect()->to('/')
            ->with('success', lang('App.login_ok'));

        // Recordarme durante 30 días
        if ($this->request->getPost('remember')) {
            $token = bin2hex(random_bytes(32));

            $users->update($user['id'], [
                'remember_token' => hash('sha256', $token),
            ]);

            $response->setCookie(
                'remember',
                $user['id'] . ':' . $token,
                60 * 60 * 24 * 30
            );
        }

        return $response;
    }

    // CERRAR SESIÓN
    public function logout()
    {
        $userId = session()->get('user_id');

        if ($userId) {
            (new UserModel())->update($userId, [
                'remember_token' => null,
            ]);
        }

        session()->remove([
            'user_id',
            'name',
            'is_admin',
            'edit_ok',
        ]);

        session()->regenerate(true);

        return redirect()->to('/')
            ->deleteCookie('remember')
            ->with('success', lang('App.logout_ok'));
    }

    // ACTIVAR CUENTA
    public function activate(string $token)
    {
        $users = new UserModel();

        $user = $users->where(
            'activation_token',
            hash('sha256', $token)
        )->first();

        if (! $user) {
            return redirect()->to('/login')
                ->with('errors', [lang('App.activation_invalid')]);
        }

        $users->update($user['id'], [
            'active'           => 1,
            'activation_token' => null,
        ]);

        return redirect()->to('/login')
            ->with('success', lang('App.activated'));
    }

    // ENVIAR CORREOS
    private function sendMail(
        string $to,
        string $subject,
        string $html
    ): bool {
        $email = service('email');

        $email->initialize([
            'protocol'    => 'smtp',
            'SMTPHost'    => 'smtp.gmail.com',
            'SMTPUser'    => env('email.SMTPUser'),
            'SMTPPass'    => env('email.SMTPPass'),
            'SMTPPort'    => 587,
            'SMTPCrypto'  => 'tls',
            'SMTPTimeout' => 30,
            'mailType'    => 'html',
            'charset'     => 'UTF-8',
        ]);

        $email->setFrom(
            env('email.fromEmail'),
            env('email.fromName')
        );

        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($html);

        if (! $email->send(false)) {
            log_message(
                'error',
                'No se pudo enviar el correo: ' .
                $email->printDebugger(['headers'])
            );

            return false;
        }

        return true;
    }

    // FORMULARIO PARA RECUPERAR CONTRASEÑA
    public function forgotForm(): string
    {
        return view('auth/forgot');
    }

    // SOLICITAR RECUPERACIÓN
    public function forgot()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        $messages = [
            'email' => [
                'required'    => lang('App.v_required', [lang('App.email')]),
                'valid_email' => lang('App.v_email'),
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $users = new UserModel();

        $user = $users->where(
            'email',
            trim($this->request->getPost('email'))
        )->first();

        // Se responde igual exista o no la cuenta
        if ($user && $user['active']) {
            $token = bin2hex(random_bytes(32));

            $users->update($user['id'], [
                'reset_token'   => hash('sha256', $token),
                'reset_expires' => date(
                    'Y-m-d H:i:s',
                    time() + 3600
                ),
            ]);

            $url = site_url('reset/' . $token);

            log_message('info', 'Password reset link: ' . $url);

            $sent = $this->sendMail(
                $user['email'],
                lang('Auth.mail_reset_subject'),
                view('emails/reset', ['url' => $url])
            );

            if (! $sent) {
                log_message(
                    'error',
                    'Falló el envío del correo de recuperación.'
                );
            }
        }

        return redirect()->to('/login')
            ->with('success', lang('Auth.reset_sent'));
    }

    // MOSTRAR FORMULARIO PARA CAMBIAR CONTRASEÑA
    public function resetForm(string $token)
    {
        if (! $this->userByResetToken($token)) {
            return redirect()->to('/forgot')
                ->with('errors', [lang('Auth.reset_invalid')]);
        }

        return view('auth/reset', ['token' => $token]);
    }

    // GUARDAR NUEVA CONTRASEÑA
    public function reset(string $token)
    {
        $user = $this->userByResetToken($token);

        if (! $user) {
            return redirect()->to('/forgot')
                ->with('errors', [lang('Auth.reset_invalid')]);
        }

        $pass = lang('App.password');
        $rep  = lang('App.repeat_password');

        $rules = [
            'password'  => 'required|min_length[8]|max_length[72]',
            'password2' => 'required|matches[password]',
        ];

        $messages = [
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
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new UserModel())->update($user['id'], [
            'password'       => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'reset_token'    => null,
            'reset_expires'  => null,
            'remember_token' => null,
        ]);

        return redirect()->to('/login')
            ->with('success', lang('Auth.reset_ok'));
    }

    // BUSCAR USUARIO POR TOKEN VÁLIDO
    private function userByResetToken(string $token): ?array
    {
        $user = (new UserModel())
            ->where('reset_token', hash('sha256', $token))
            ->first();

        if (
            ! $user ||
            empty($user['reset_expires']) ||
            strtotime($user['reset_expires']) < time()
        ) {
            return null;
        }

        return $user;
    }
}
