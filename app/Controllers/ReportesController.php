<?php

namespace App\Controllers;

use App\Models\VentaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportesController extends BaseController
{
    // 1. Mostrar el formulario de filtros
    public function index()
    {
        return view('reportes/index');
    }

    // 2. Generar el Excel
    public function generarExcel()
    {
        $fechaInicio = $this->request->getPost('fecha_inicio');
        $fechaFin    = $this->request->getPost('fecha_fin');

        if (!$fechaInicio || !$fechaFin) {
            return redirect()->back()->with('error', 'Selecciona ambas fechas.');
        }

        // Consultar datos
        $db = \Config\Database::connect();
        $builder = $db->table('ventas');
        $builder->select('ventas.id, ventas.fecha, clientes.nombre as cliente, usuarios.username as vendedor, ventas.total, ventas.metodo_pago');
        $builder->join('clientes', 'clientes.id = ventas.cliente_id', 'left');
        $builder->join('usuarios', 'usuarios.id = ventas.usuario_id', 'left');
        
        // Filtro de fechas (incluyendo el final del día para la fecha fin)
        $builder->where('ventas.fecha >=', $fechaInicio . ' 00:00:00');
        $builder->where('ventas.fecha <=', $fechaFin . ' 23:59:59');
        $builder->orderBy('ventas.fecha', 'ASC');
        
        $ventas = $builder->get()->getResultArray();

        // --- CREAR EXCEL ---
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte Ventas');

        // Encabezados
        $headers = ['ID Venta', 'Fecha', 'Cliente', 'Vendedor', 'Método Pago', 'Total'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true); // Ajustar ancho
            $sheet->getStyle($col . '1')->getFont()->setBold(true); // Negrita
            $col++;
        }

        // Llenar datos
        $fila = 2;
        $totalPeriodo = 0;
        foreach ($ventas as $v) {
            $sheet->setCellValue('A' . $fila, $v['id']);
            $sheet->setCellValue('B' . $fila, $v['fecha']);
            $sheet->setCellValue('C' . $fila, $v['cliente'] ?? 'Público General');
            $sheet->setCellValue('D' . $fila, $v['vendedor']);
            $sheet->setCellValue('E' . $fila, ucfirst($v['metodo_pago']));
            $sheet->setCellValue('F' . $fila, $v['total']);
            
            // Formato de moneda para la columna F
            $sheet->getStyle('F' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
            
            $totalPeriodo += $v['total'];
            $fila++;
        }

        // Total Final al pie
        $sheet->setCellValue('E' . $fila, 'TOTAL PERIODO:');
        $sheet->setCellValue('F' . $fila, $totalPeriodo);
        $sheet->getStyle('E' . $fila . ':F' . $fila)->getFont()->setBold(true);

        // --- DESCARGAR ---
        $writer = new Xlsx($spreadsheet);
        $filename = 'Reporte_Ventas_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}