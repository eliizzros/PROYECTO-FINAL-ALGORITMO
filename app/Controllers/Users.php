<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Users extends BaseController
{
    protected $helpers = ['url', 'form'];

    private const PER_PAGE = 5;

    // ---------- LISTADO CON PAGINACIÓN ----------
    public function index(): string
    {
        $model = new UserModel();
        $page  = max(1, (int) $this->request->getGet('page'));
        $total = $model->countAllResults();

        $users = $model->orderBy('created_at', 'DESC')
                       ->orderBy('id', 'DESC')
                       ->findAll(self::PER_PAGE, ($page - 1) * self::PER_PAGE);

        return view('users/index', [
            'users'    => $users,
            'page'     => $page,
            'hasNewer' => $page > 1,
            'hasOlder' => $page * self::PER_PAGE < $total,
        ]);
    }

    // ---------- VER ----------
    public function show(int $id): string
    {
        return view('users/show', ['user' => $this->findOr404($id)]);
    }

    // ---------- CREAR ----------
    public function newForm(): string
    {
        return view('users/new');
    }

    public function create()
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'password'  => 'required|min_length[8]|max_length[72]',
            'password2' => 'required|matches[password]',
        ];

        if (! $this->validate($rules, $this->messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Un usuario creado por el administrador queda activo (no necesita correo)
        (new UserModel())->insert([
            'name'     => trim($this->request->getPost('name')),
            'email'    => trim($this->request->getPost('email')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'active'   => 1,
            'is_admin' => 0,
        ]);

        return redirect()->to('/users')->with('success', lang('Users.user_created'));
    }

    // ---------- EDITAR ----------
    public function editForm(int $id): string
    {
        $user = $this->findOr404($id);

        return view('users/edit', [
            'user'   => $user,
            'isSelf' => $this->isSelf($id),
        ]);
    }

    public function update(int $id)
    {
        $user = $this->findOr404($id);

        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            // is_unique ignora al propio usuario, para que pueda guardar su mismo correo
            'email'     => 'required|valid_email|max_length[150]|is_unique[users.email,id,' . $id . ']',
            'password'  => 'permit_empty|min_length[8]|max_length[72]',
            'password2' => 'matches[password]',
        ];

        if (! $this->validate($rules, $this->messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'     => trim($this->request->getPost('name')),
            'email'    => trim($this->request->getPost('email')),
            'active'   => $this->request->getPost('active') ? 1 : 0,
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0,
        ];

        // Un administrador no puede quitarse a sí mismo el acceso ni el rol
        if ($this->isSelf($id)) {
            $data['active']   = 1;
            $data['is_admin'] = 1;
        }

        // Contraseña vacía = no se cambia
        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        (new UserModel())->update($id, $data);

        if ($this->isSelf($id)) {
            session()->set('name', $data['name']);   // para que el menú muestre el nombre nuevo
        }

        return redirect()->to('/users/' . $id)->with('success', lang('Users.user_updated'));
    }

    // ---------- ELIMINAR ----------
    public function deleteForm(int $id)
    {
        $user = $this->findOr404($id);

        if ($this->isSelf($id)) {
            return redirect()->to('/users/' . $id)->with('errors', [lang('Users.cannot_delete_self')]);
        }

        return view('users/delete', ['user' => $user]);
    }

    public function delete(int $id)
    {
        $user = $this->findOr404($id);

        if ($this->isSelf($id)) {
            return redirect()->to('/users/' . $id)->with('errors', [lang('Users.cannot_delete_self')]);
        }

        // Borra también su imagen de perfil, si tenía
        if (! empty($user['avatar'])) {
            $path = FCPATH . 'uploads/avatars/' . basename($user['avatar']);
            if (is_file($path)) {
                unlink($path);
            }
        }

        (new UserModel())->delete($id);

        return redirect()->to('/users')->with('success', lang('Users.user_deleted'));
    }

    // ---------- PRIVADOS ----------
    private function findOr404(int $id): array
    {
        $user = (new UserModel())->find($id);

        if (! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $user;
    }

    private function isSelf(int $id): bool
    {
        return $id === (int) session()->get('user_id');
    }

    private function messages(): array
    {
        $name = lang('App.name');
        $mail = lang('App.email');
        $pass = lang('App.password');
        $rep  = lang('App.repeat_password');

        return [
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
    }
}