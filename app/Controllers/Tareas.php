<?php

namespace App\Controllers;

use App\Models\TareaModel;

class Tareas extends BaseController
{
    public function index()
    {
        $modelo = new TareaModel();

        $tareas = $modelo->findAll();

        return view('tareas/index', [
            'tareas' => $tareas
        ]);
    }
}