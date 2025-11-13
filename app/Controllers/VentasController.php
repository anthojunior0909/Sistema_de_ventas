<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\DetalleVentaModel;
use App\Models\ProductoModel;
use App\Models\ClienteModel;

class VentasController extends BaseController
{
    // 1. Mostrar la pantalla de "Nueva Venta"
    public function new()
    {
        $productoModel = new ProductoModel();
        $clienteModel = new ClienteModel();

        $data = [
            // Enviamos productos y clientes para los selectores
            'productos' => $productoModel->findAll(),
            'clientes'  => $clienteModel->findAll()
        ];

        return view('ventas/new', $data);
    }

    // 2. Procesar la venta (Guardar todo)
    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $ventaModel = new VentaModel();
        $detalleModel = new DetalleVentaModel();
        $productoModel = new ProductoModel();
        $db = \Config\Database::connect();

        // Obtenemos los datos del JSON enviado por JS
        $json = $this->request->getJSON();
        
        // Validaciones básicas
        if (empty($json->productos) || count($json->productos) == 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'El carrito está vacío.']);
        }

        // --- INICIO DE LA TRANSACCIÓN ---
        $db->transStart();

        try {
            // A. Guardar la Venta (Cabecera)
            $dataVenta = [
                'cliente_id'  => $json->cliente_id,
                'usuario_id'  => session()->get('user_id'), // El usuario logueado
                'total'       => $json->total,
                'metodo_pago' => 'efectivo' // Por ahora fijo, luego dinámico
            ];
            
            $ventaModel->insert($dataVenta);
            $ventaId = $ventaModel->getInsertID(); // Obtenemos el ID generado

            // B. Guardar los Detalles y Descontar Stock
            foreach ($json->productos as $item) {
                // 1. Insertar detalle
                $detalleModel->insert([
                    'venta_id'        => $ventaId,
                    'producto_id'     => $item->id,
                    'cantidad'        => $item->cantidad,
                    'precio_unitario' => $item->precio
                ]);

                // 2. Descontar stock
                // Buscamos el producto actual
                $productoActual = $productoModel->find($item->id);
                $nuevoStock = $productoActual['stock'] - $item->cantidad;

                // Validamos que no quede negativo (opcional)
                if ($nuevoStock < 0) {
                    throw new \Exception("Stock insuficiente para el producto: " . $productoActual['nombre']);
                }

                $productoModel->update($item->id, ['stock' => $nuevoStock]);
            }

            // --- FIN DE LA TRANSACCIÓN ---
            $db->transComplete(); 

            if ($db->transStatus() === false) {
                // Si algo falló en la BD
                return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar en base de datos.']);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Venta registrada correctamente.']);

        } catch (\Exception $e) {
            // Si hubo error lógico (ej. stock negativo), cancelamos todo
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}