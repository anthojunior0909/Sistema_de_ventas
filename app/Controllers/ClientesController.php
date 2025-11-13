<?php

namespace App\Controllers;

use App\Models\ClienteModel;

class ClientesController extends BaseController
{
    // 1. Cargar la vista con la lista de clientes
    public function index()
    {
        $model = new ClienteModel();
        $data = [
            'clientes' => $model->findAll()
        ];
        return view('clientes/index', $data);
    }

    // 2. Guardar nuevo cliente (AJAX)
    public function create()
    {
        if ($this->request->isAJAX()) {
            $model = new ClienteModel();
            
            // Validación simple
            if (!$this->validate(['nombre' => 'required|min_length[3]'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'El nombre es obligatorio.']);
            }

            $data = [
                'nombre'    => $this->request->getPost('nombre'),
                'documento' => $this->request->getPost('documento'),
                'telefono'  => $this->request->getPost('telefono'),
            ];

            $model->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Cliente registrado correctamente.']);
        }
    }

    // 3. Obtener datos para editar (AJAX)
    public function edit($id = null)
    {
        if ($this->request->isAJAX()) {
            $model = new ClienteModel();
            $cliente = $model->find($id);
            return $this->response->setJSON(['success' => true, 'data' => $cliente]);
        }
    }

    // 4. Actualizar cliente (AJAX)
    public function update($id = null)
    {
        if ($this->request->isAJAX()) {
            $model = new ClienteModel();
            $data = [
                'nombre'    => $this->request->getRawInput()['nombre'],
                'documento' => $this->request->getRawInput()['documento'],
                'telefono'  => $this->request->getRawInput()['telefono'],
            ];

            $model->update($id, $data);
            return $this->response->setJSON(['success' => true, 'message' => 'Cliente actualizado correctamente.']);
        }
    }

    // 5. Eliminar cliente (AJAX)
    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $model = new ClienteModel();
            $model->delete($id);
            return $this->response->setJSON(['success' => true, 'message' => 'Cliente eliminado.']);
        }
    }
}