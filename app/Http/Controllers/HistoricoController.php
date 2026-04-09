<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Services\HistoricoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoricoController extends Controller
{
    public function show() {
        return view('historicos.show');
    }

    public function paginate(Request $request) {
        $historicoService = new HistoricoService();
        return $historicoService->paginate($request);
    }

    public function detail(Historico $historico) {
        return view('historicos.detail', compact('historico'));
    }
    
}
