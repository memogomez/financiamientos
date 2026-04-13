<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReporteController extends Controller
{
    private function consultarResultados(string $fechaInicio, string $fechaFin)
    {
        return DB::table('solicitudes as s')
            ->join('areas as a', 's.id_area', '=', 'a.id')
            ->select(
                'a.nombre_area',
                DB::raw('COUNT(s.id) as num_solicitudes'),
                DB::raw('SUM(s.monto_solicitado) as total_solicitado')
            )
            ->whereBetween('s.fecha', [$fechaInicio, $fechaFin])
            ->where('a.estatus', 1)
            ->groupBy('a.id', 'a.nombre_area')
            ->orderBy('a.nombre_area')
            ->get();
    }

    public function fechas(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin    = $request->input('fecha_fin');
        $resultados  = collect();
        $totales     = null;

        if ($fechaInicio && $fechaFin) {
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            ], [
                'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
            ]);

            $resultados = $this->consultarResultados($fechaInicio, $fechaFin);

            $totales = [
                'num_solicitudes'  => $resultados->sum('num_solicitudes'),
                'total_solicitado' => $resultados->sum('total_solicitado'),
            ];
        }

        return view('reportes.fechas', compact('resultados', 'totales', 'fechaInicio', 'fechaFin'));
    }

    public function exportarExcel(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ], [
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
        ]);

        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin    = $request->input('fecha_fin');
        $resultados  = $this->consultarResultados($fechaInicio, $fechaFin);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte por Fechas');

        // ----- Título -----
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'Reporte de Solicitudes por Intervalo de Fechas');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2D4A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ----- Subtítulo (rango de fechas) -----
        $sheet->mergeCells('A2:D2');
        $fechaInicioFmt = \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y');
        $fechaFinFmt    = \Carbon\Carbon::parse($fechaFin)->format('d/m/Y');
        $sheet->setCellValue('A2', "Del {$fechaInicioFmt} al {$fechaFinFmt}");
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['argb' => 'FF555555']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ----- Encabezados de columna -----
        $headers = ['#', 'Área', 'Solicitudes', 'Monto Solicitado'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}3", $header);
            $col++;
        }
        $sheet->getStyle('A3:D3')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF495057']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD0D0D0']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(22);

        // ----- Datos -----
        $fila = 4;
        foreach ($resultados as $i => $row) {
            $bgColor = ($i % 2 === 0) ? 'FFFFFFFF' : 'FFF8F9FA';

            $sheet->setCellValue("A{$fila}", $i + 1);
            $sheet->setCellValue("B{$fila}", $row->nombre_area);
            $sheet->setCellValue("C{$fila}", (int) $row->num_solicitudes);
            $sheet->setCellValue("D{$fila}", (float) $row->total_solicitado);

            $sheet->getStyle("A{$fila}:D{$fila}")->applyFromArray([
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD0D0D0']]],
            ]);
            $sheet->getStyle("A{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$fila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$fila}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $fila++;
        }

        // ----- Fila de totales -----
        $totales = [
            'num_solicitudes'  => $resultados->sum('num_solicitudes'),
            'total_solicitado' => $resultados->sum('total_solicitado'),
        ];

        $sheet->mergeCells("A{$fila}:B{$fila}");
        $sheet->setCellValue("A{$fila}", 'TOTALES');
        $sheet->setCellValue("C{$fila}", (int) $totales['num_solicitudes']);
        $sheet->setCellValue("D{$fila}", (float) $totales['total_solicitado']);

        $sheet->getStyle("A{$fila}:D{$fila}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2D4A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF9AAFD4']]],
        ]);
        $sheet->getStyle("D{$fila}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // ----- Ancho de columnas -----
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(22);

        $nombreArchivo = "reporte_solicitudes_{$fechaInicio}_{$fechaFin}.xlsx";

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $nombreArchivo, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
