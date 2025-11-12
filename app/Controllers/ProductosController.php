<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class ProductosController extends BaseController
{
    /**
     * Muestra la lista de productos
     */
    public function index()
    {
        // ... (tu código del index) ...
        $model = new ProductoModel();
        $data = [
            'productos' => $model->findAll()
        ];
        return view('productos/index', $data);
    }

    /**
     * Procesa la creación de un nuevo producto (vía AJAX)
     */
    public function create()
    {
        // Verificamos que sea una petición AJAX
        if ($this->request->isAJAX()) {
            
            $model = new ProductoModel();
            
            // Reglas de validación (puedes hacerlas más estrictas)
            $rules = [
                'nombre'       => 'required|min_length[3]|max_length[255]',
                'precio_venta' => 'required|decimal',
                'stock'        => 'required|integer',
            ];

            if (!$this->validate($rules)) {
                // Si la validación falla, devolvemos los errores
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }

            // Recogemos los datos del POST
            $data = [
                'codigo'        => $this->request->getPost('codigo'),
                'nombre'        => $this->request->getPost('nombre'),
                'precio_compra' => $this->request->getPost('precio_compra'),
                'precio_venta'  => $this->request->getPost('precio_venta'),
                'stock'         => $this->request->getPost('stock'),
            ];

            // Insertamos en la BD
            if ($model->insert($data)) {
                // Éxito
                return $this->response->setJSON(['success' => true, 'message' => 'Producto guardado exitosamente.']);
            } else {
                // Error al guardar
                return $this->response->setJSON(['success' => false, 'message' => 'No se pudo guardar el producto.']);
            }

        } else {
            // Si no es AJAX, muestra un error 404
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Obtiene los datos de un producto para editar (vía AJAX)
     */
    public function edit($id = null)
    {
        if ($this->request->isAJAX()) {
            $model = new ProductoModel();
            $producto = $model->find($id);

            if ($producto) {
                return $this->response->setJSON(['success' => true, 'data' => $producto]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado.']);
            }
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    /**
     * Procesa la actualización de un producto (vía AJAX)
     */
    public function update($id = null)
    {
        if ($this->request->isAJAX()) {
            $model = new ProductoModel();

            // Reglas de validación
            $rules = [
                'nombre'       => 'required|min_length[3]|max_length[255]',
                'precio_venta' => 'required|decimal',
                'stock'        => 'required|integer',
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }

            // Recogemos los datos (usamos getRawInput() porque vienen como JSON/PUT)
            // CodeIgniter maneja esto automáticamente si envías como POST y spooffing
            $data = [
                'codigo'        => $this->request->getPost('codigo'),
                'nombre'        => $this->request->getPost('nombre'),
                'precio_compra' => $this->request->getPost('precio_compra'),
                'precio_venta'  => $this->request->getPost('precio_venta'),
                'stock'         => $this->request->getPost('stock'),
            ];

            if ($model->update($id, $data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Producto actualizado exitosamente.']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'No se pudo actualizar el producto.']);
            }
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    /**
     * Elimina un producto (vía AJAX)
     */
    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $model = new ProductoModel();

            if ($model->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Producto eliminado exitosamente.']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'No se pudo eliminar el producto.']);
            }
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
}