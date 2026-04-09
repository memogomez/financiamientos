<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
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

            $resultados = DB::table('solicitudes as s')
                ->join('areas as a', 's.id_area', '=', 'a.id')
                ->select(
                    'a.nombre_area',
                    DB::raw('COUNT(s.id) as num_solicitudes'),
                    DB::raw('SUM(s.monto_solicitado) as total_solicitado'),
                    DB::raw('SUM(s.monto_total) as total_aprobado'),
                    DB::raw('SUM(s.total) as total')
                )
                ->whereBetween('s.fecha', [$fechaInicio, $fechaFin])
                ->where('a.estatus', 1)
                ->groupBy('a.id', 'a.nombre_area')
                ->orderBy('a.nombre_area')
                ->get();

            $totales = [
                'num_solicitudes' => $resultados->sum('num_solicitudes'),
                'total_solicitado' => $resultados->sum('total_solicitado'),
                'total_aprobado'   => $resultados->sum('total_aprobado'),
                'total'            => $resultados->sum('total'),
            ];
        }

        return view('reportes.fechas', compact('resultados', 'totales', 'fechaInicio', 'fechaFin'));
    }
}
