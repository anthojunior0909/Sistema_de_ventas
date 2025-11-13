<?php

namespace App\Controllers;

use App\Models\CajaModel;
use CodeIgniter\I18n\Time;

class CajaController extends BaseController
{
    public function index()
    {
        $cajaModel = new CajaModel();
        $userId = session()->get('user_id');

        // Verificar si ya tiene una caja abierta
        $cajaAbierta = $cajaModel->obtenerCajaAbierta($userId);

        // Obtener historial de cajas de este usuario
        $historial = $cajaModel->where('usuario_id', $userId)
                               ->orderBy('id', 'DESC')
                               ->findAll(10); // Últimas 10

        return view('caja/index', [
            'cajaAbierta' => $cajaAbierta,
            'historial'   => $historial
        ]);
    }

    // Abrir Caja
    public function abrir()
    {
        $cajaModel = new CajaModel();
        $userId = session()->get('user_id');

        // Validar que no tenga una abierta
        if ($cajaModel->obtenerCajaAbierta($userId)) {
            return redirect()->back()->with('error', 'Ya tienes una caja abierta.');
        }

        $montoInicial = $this->request->getPost('monto_inicial');

        $cajaModel->insert([
            'usuario_id'    => $userId,
            'monto_inicial' => $montoInicial,
            'estado'        => 'abierta',
            'fecha_apertura'=> date('Y-m-d H:i:s')
        ]);

        return redirect()->to('caja')->with('success', 'Caja aperturada correctamente.');
    }

    // Cerrar Caja
    public function cerrar()
    {
        $cajaModel = new CajaModel();
        $userId = session()->get('user_id');
        $cajaAbierta = $cajaModel->obtenerCajaAbierta($userId);

        if (!$cajaAbierta) {
            return redirect()->back()->with('error', 'No hay caja para cerrar.');
        }

        $montoFinal = $this->request->getPost('monto_final');

        $cajaModel->update($cajaAbierta['id'], [
            'monto_final'  => $montoFinal,
            'estado'       => 'cerrada',
            'fecha_cierre' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('caja')->with('success', 'Caja cerrada correctamente.');
    }
}