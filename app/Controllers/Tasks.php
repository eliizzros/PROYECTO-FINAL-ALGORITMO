<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Tasks extends BaseController
{
    protected $helpers = ['url', 'form'];

    private const PER_PAGE = 5;

    public function index(): string
    {
        $model = new TaskModel();
        $q     = trim((string) $this->request->getGet('q'));
        $page  = max(1, (int) $this->request->getGet('page'));

        if ($q !== '') {
            $model->like('description', $q);
        }

        $total = $model->countAllResults(false);

        $tasks = $model->orderBy('created_at', 'DESC')
                       ->orderBy('id', 'DESC')
                       ->findAll(self::PER_PAGE, ($page - 1) * self::PER_PAGE);

        return view('tasks/index', [
            'tasks'    => $tasks,
            'q'        => $q,
            'page'     => $page,
            'hasNewer' => $page > 1,
            'hasOlder' => $page * self::PER_PAGE < $total,
        ]);
    }

   // 1
    public function show(int $id): string
    {
        return view('tasks/show', ['task' => $this->findOr404($id)]);
    }

    // ---------- CREAR ----------
    public function newForm(): string
    {
        return view('tasks/new');
    }

    public function create()
    {
        if (! $this->validate($this->rules(), $this->messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new TaskModel())->insert([
            'description' => trim($this->request->getPost('description')),
        ]);

        return redirect()->to('/tasks')->with('success', lang('Tasks.task_created'));
    }

    // ---------- EDITAR ----------
    public function editForm(int $id): string
    {
        return view('tasks/edit', ['task' => $this->findOr404($id)]);
    }

    public function update(int $id)
    {
        $this->findOr404($id);

        if (! $this->validate($this->rules(), $this->messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new TaskModel())->update($id, [
            'description' => trim($this->request->getPost('description')),
        ]);

        return redirect()->to('/tasks/' . $id)->with('success', lang('Tasks.task_updated'));
    }

    // ---------- ELIMINAR ----------
    public function deleteForm(int $id): string
    {
        return view('tasks/delete', ['task' => $this->findOr404($id)]);
    }

    public function delete(int $id)
    {
        $this->findOr404($id);

        (new TaskModel())->delete($id);

        return redirect()->to('/tasks')->with('success', lang('Tasks.task_deleted'));
    }

    // ---------- PRIVADOS ----------
    private function findOr404(int $id): array
    {
        $task = (new TaskModel())->find($id);

        if (! $task) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $task;
    }

    private function rules(): array
    {
        return ['description' => 'required|max_length[255]'];
    }

    private function messages(): array
    {
        $label = lang('Tasks.description');

        return [
            'description' => [
                'required'   => lang('App.v_required', [$label]),
                'max_length' => lang('Tasks.v_max', [$label, 255]),
            ],
        ];
    }
}