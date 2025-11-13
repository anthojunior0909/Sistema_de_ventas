<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\VentaModel;
use App\Models\ClienteModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $productoModel = new ProductoModel();
        $clienteModel = new ClienteModel();
        $ventaModel = new VentaModel();

        // 1. DATOS PARA TARJETAS
        // Total productos
        $totalProductos = $productoModel->countAll();
        
        // Total clientes
        $totalClientes = $clienteModel->countAll();
        
        // Ventas de HOY
        $hoy = date('Y-m-d');
        $ventasHoy = $ventaModel->selectSum('total')
                                ->where("DATE(fecha)", $hoy)
                                ->first();
        
        // 2. DATOS PARA EL GRÁFICO (Últimos 7 días)
        // Hacemos una consulta SQL pura para agrupar por día
        $sqlGrafico = "SELECT DATE(fecha) as fecha, SUM(total) as total 
                       FROM ventas 
                       WHERE fecha >= DATE(NOW()) - INTERVAL 7 DAY 
                       GROUP BY DATE(fecha) 
                       ORDER BY fecha ASC";
        
        $query = $db->query($sqlGrafico);
        $datosGrafico = $query->getResultArray();

        // Preparamos arreglos para Chart.js
        $fechas = [];
        $montos = [];
        foreach($datosGrafico as $row) {
            $fechas[] = date('d/m', strtotime($row['fecha']));
            $montos[] = $row['total'];
        }

        // Enviamos todo a la vista
        return view('dashboard/index', [
            'totalProductos' => $totalProductos,
            'totalClientes'  => $totalClientes,
            'ventasHoy'      => $ventasHoy['total'] ?? 0,
            'graficoFechas'  => json_encode($fechas),
            'graficoMontos'  => json_encode($montos)
        ]);
    }
}