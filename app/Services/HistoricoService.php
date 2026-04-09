<?php

namespace App\Services;

use App\Models\Historico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoricoService {

  public function paginate(Request $request) {
    if (!$request->ajax()) {
      return response()->json([]);
    }

    $query = DB::table('historicos')
          ->select(
            'id_historico',
            'consecutivo',
            'nombre_solicitante',
            'agencia_mp',
            'fecha'
          );

    if ($request->has('search') && $request->get('search')['value']) {
      $searchValue = $request->get('search')['value'];
      $query->where(function ($query) use ($searchValue) {
        $query->where('consecutivo', 'like', '%' . $searchValue . '%')
              ->orWhere('nombre_solicitante', 'like', '%' . $searchValue . '%')
              ->orWhere('agencia_mp', 'like', '%' . $searchValue . '%')
              ->orWhere('fecha', 'like', '%' . $searchValue . '%');
      });
    }

    $query->orderBy('id_historico', 'desc');

    $start = $request->get('start');
    $length = $request->get('length');
    $historicos = $query->offset($start)->limit($length)->get();
    $recordsFiltered = Historico::count();

    return response()->json([
      'draw' => (int) $request->get('draw'),
      'recordsTotal' => $recordsFiltered,
      'recordsFiltered' => $recordsFiltered,
      'data' => $historicos,
    ]);
  }

}