<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $helpers = ['url', 'form'];

    // ---------- PERFIL ----------
    public function index(): string
    {
        session()->remove('edit_ok');

        $user = (new UserModel())->find(session()->get('user_id'));

        if (!$user) {
            return redirect()->to('/login')->with('errors', [
                'No se encontró el usuario.'
            ])->getBody();
        }

        return view('profile/index', [
            'user'   => $user,
            'avatar' => $this->avatarUrl($user),
        ]);
    }

    // ---------- CONFIRMAR CONTRASEÑA ----------
    public function confirmForm(): string
    {
        return view('profile/confirm');
    }

    public function confirm()
    {
        $rules = [
            'password' => 'required',
        ];

        $messages = [
            'password' => [
                'required' => lang('App.v_required', [
                    lang('App.password')
                ]),
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $user = (new UserModel())->find(session()->get('user_id'));

        if (
            !$user ||
            !password_verify(
                (string) $this->request->getPost('password'),
                $user['password']
            )
        ) {
            return redirect()->back()->with('errors', [
                lang('App.wrong_password')
            ]);
        }

        session()->set('edit_ok', true);

        return redirect()->to('/profile/edit');
    }

    // ---------- EDITAR PERFIL ----------
    public function editForm()
    {
        if (!session()->get('edit_ok')) {
            return redirect()->to('/profile/edit/confirm');
        }

        $user = (new UserModel())->find(session()->get('user_id'));

        if (!$user) {
            return redirect()->to('/login');
        }

        return view('profile/edit', [
            'user' => $user,
        ]);
    }

    public function update()
    {
        if (!session()->get('edit_ok')) {
            return redirect()->to('/profile/edit/confirm');
        }

        $userId = (int) session()->get('user_id');

        $name = lang('App.name');
        $mail = lang('App.email');

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email,id,' . $userId . ']',
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
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $newName = trim((string) $this->request->getPost('name'));
        $newMail = trim((string) $this->request->getPost('email'));

        $users = new UserModel();
        $user  = $users->find($userId);

        if (!$user) {
            return redirect()->to('/login');
        }

        $users->update($userId, [
            'name'  => $newName,
            'email' => $newMail,
        ]);

        session()->set('name', $newName);
        session()->remove('edit_ok');

        return redirect()->to('/profile')
            ->with('success', lang('App.profile_updated'));
    }

    // ---------- CAMBIAR CONTRASEÑA ----------
    public function passwordForm(): string
    {
        return view('profile/password');
    }

    public function passwordUpdate()
    {
        $current = lang('App.current_password');
        $new     = lang('App.new_password');
        $repeat  = lang('App.repeat_new_password');

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]|max_length[72]',
            'new_password2'    => 'required|matches[new_password]',
        ];

        $messages = [
            'current_password' => [
                'required' => lang('App.v_required', [$current]),
            ],
            'new_password' => [
                'required'   => lang('App.v_required', [$new]),
                'min_length' => lang('App.v_min', [$new, 8]),
            ],
            'new_password2' => [
                'required' => lang('App.v_required', [$repeat]),
                'matches'  => lang('App.v_matches'),
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $users = new UserModel();
        $user  = $users->find(session()->get('user_id'));

        if (
            !$user ||
            !password_verify(
                (string) $this->request->getPost('current_password'),
                $user['password']
            )
        ) {
            return redirect()->back()->with('errors', [
                lang('App.wrong_password')
            ]);
        }

        $users->update($user['id'], [
            'password' => password_hash(
                (string) $this->request->getPost('new_password'),
                PASSWORD_DEFAULT
            ),
        ]);

        return redirect()->to('/profile')
            ->with('success', lang('App.password_updated'));
    }

    // ---------- MOSTRAR AVATAR ----------
    private function avatarUrl(array $user): string
    {
        if (
            !empty($user['avatar']) &&
            is_file(FCPATH . 'uploads/avatars/' . basename($user['avatar']))
        ) {
            return base_url(
                'uploads/avatars/' . basename($user['avatar'])
            );
        }

        return base_url('img/avatar-default.svg');
    }

    // ---------- CAMBIAR IMAGEN DE PERFIL ----------
    public function imageForm(): string
    {
        return view('profile/image');
    }

    public function imageUpdate()
    {
        $rules = [
            'avatar' => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
        ];

        $messages = [
            'avatar' => [
                'uploaded' => lang('Profile.v_image_required'),
                'is_image' => lang('Profile.v_image_invalid'),
                'mime_in'  => lang('Profile.v_image_invalid'),
                'max_size' => lang('Profile.v_image_size'),
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $users = new UserModel();
        $user  = $users->find(session()->get('user_id'));

        if (!$user) {
            return redirect()->to('/login');
        }

        $file = $this->request->getFile('avatar');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('errors', [
                lang('Profile.v_image_invalid')
            ]);
        }

        $uploadPath = FCPATH . 'uploads/avatars';

        if (
            !is_dir($uploadPath) &&
            !mkdir($uploadPath, 0755, true) &&
            !is_dir($uploadPath)
        ) {
            return redirect()->back()->with('errors', [
                'No se pudo crear la carpeta para las imágenes.'
            ]);
        }

        $newName = $file->getRandomName();

        if (!$file->move($uploadPath, $newName)) {
            return redirect()->back()->with('errors', [
                'No se pudo guardar la imagen.'
            ]);
        }

        $oldAvatar = $user['avatar'] ?? null;

        if (!$users->update($user['id'], ['avatar' => $newName])) {
            $this->removeAvatarFile($newName);

            return redirect()->back()->with('errors', [
                'No se pudo actualizar la imagen de perfil.'
            ]);
        }

        $this->removeAvatarFile($oldAvatar);

        return redirect()->to('/profile')
            ->with('success', lang('Profile.image_updated'));
    }

    // ---------- ELIMINAR IMAGEN DE PERFIL ----------
    public function imageDeleteForm(): string
    {
        return view('profile/image_delete');
    }

    public function imageDelete()
    {
        $users = new UserModel();
        $user  = $users->find(session()->get('user_id'));

        if (!$user) {
            return redirect()->to('/login');
        }

        if (!$users->update($user['id'], ['avatar' => null])) {
            return redirect()->back()->with('errors', [
                'No se pudo eliminar la imagen de perfil.'
            ]);
        }

        $this->removeAvatarFile($user['avatar'] ?? null);

        return redirect()->to('/profile')
            ->with('success', lang('Profile.image_deleted'));
    }

    // ---------- ELIMINAR ARCHIVO FÍSICO ----------
    private function removeAvatarFile(?string $file): void
    {
        if (!$file) {
            return;
        }

        $path = FCPATH . 'uploads/avatars/' . basename($file);

        if (is_file($path)) {
            unlink($path);
        }
    }
}
