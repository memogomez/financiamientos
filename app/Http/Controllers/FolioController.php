<?php

namespace App\Http\Controllers;

use App\Http\Requests\FolioSolictanteRequest;
use App\Models\Delito;
use App\Models\Folio;
use App\Models\Solicitante;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FolioController extends Controller
{
    public function create()
    {
        $delitos = Delito::where('estatus', 1)->orderBy('delito', 'asc')->get();
        return view('folios.create', compact('delitos'));
    }

    public function store(FolioSolictanteRequest $request)
    {
        DB::connection('mysql')->beginTransaction();

        try {
            $solicitante = Solicitante::create([
                'nombre_solicitante' => $request->nombre_solicitante,
                'plaza' => $request->plaza,
                'gafete' => $request->gafete,
                'agencia_mp' => $request->agencia_mp,
                'turno' => $request->turno,
            ]);

            $folio = Folio::create([
                'id_solicitante' => $solicitante->id_solicitante,
                'ticket' => $request->ticket,
                'acronimo' => $request->acronimo,
                'hora' => $request->hora,
                'dia_mes' => $request->dia_mes,
                'anio' => $request->anio,
                'razon' => $request->razon,
                'numero_registro' => $request->numero_registro,
                'id_delito' => $request->id_delito,
                'detenido' => $request->detenido,
                'id_user' => Auth::user()->id,
            ]);


            $folio->folio = "{$folio->ticket}{$folio->hora}{$folio->dia_mes}{$folio->anio}";
            $folio->save();

            DB::connection('mysql')->commit();
            return redirect()->route('folios.create')->with('success', 'El folio se registro correctamente: ' . $folio->folio);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Hubo un error al registrar la información del folio');
        }
    }

    public function show()
    {
        return view('folios.show');
    }

    public function paginate(Request $request)
    {

        if (!$request->ajax()) {
            return response()->json([]);
        }

        $query = DB::table('folios')
            ->select(
                'id_folio',
                'ticket',
                'folio',
                'created_at'
            );

        if ($request->has('search') && $request->get('search')['value']) {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($query) use ($searchValue) {
                $query->where('id_folio', 'like', '%' . $searchValue . '%')
                    ->orWhere('ticket', 'like', '%' . $searchValue . '%')
                    ->orWhere('folio', 'like', '%' . $searchValue . '%')
                    ->orWhere('created_at', 'like', '%' . $searchValue . '%');
            });
        }

        $query->orderBy('id_folio', 'desc');

        $start = $request->get('start');
        $length = $request->get('length');
        $folios = $query->offset($start)->limit($length)->get();
        $recordsFiltered = Folio::count();

        return response()->json([
            'draw' => (int) $request->get('draw'),
            'recordsTotal' => $recordsFiltered,
            'recordsFiltered' => $recordsFiltered,
            'data' => $folios,
        ]);
    }

    public function detail($id_folio)
    {
        $folio = Folio::with(['solicitante', 'delito'])->findOrFail($id_folio);
        return view('folios.detail', compact('folio'));
    }

    public function edit($id_folio)
    {
        $folio = Folio::with(['solicitante', 'delito'])->findOrFail($id_folio);
        $delitos = Delito::where('estatus', 1)->orderBy('delito', 'asc')->get();
        return view('folios.edit', compact('folio', 'delitos'));
    }

    public function update(FolioSolictanteRequest $request, Folio $folio) {
        return [
            'Laravel now()' => now(),
            'Carbon::now()' => \Carbon\Carbon::now(),
            'PHP date()' => date('Y-m-d H:i:s'),
        ];
        // try {
        //     $solicitante = Solicitante::findOrFail($folio->id_solicitante);
        //     $solicitante->fill($request->all());
        //     $solicitante->update();

        //     $folio->fill($request->all());
        //     $folio->folio = "{$folio->ticket}{$folio->hora}{$folio->dia_mes}{$folio->anio}";
        //     $folio->id_user = Auth::id();
        //     $folio->update();

        //     return back()->with('success','El registro se actualizó correctamente.');
        // } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        //     return back()->with('error', 'El solicitante no fue encontrado.');
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Ocurrió un error al actualizar el registro. ');
        // }
    }
}
