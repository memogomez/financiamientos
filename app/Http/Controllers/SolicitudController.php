<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitudRequest;
use App\Models\Area;
use App\Models\Oficio;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    public function show()
    {
        return view('solicitudes.show');
    }

    public function paginate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([]);
        }

        $query = DB::table('solicitudes as s')
            ->leftJoin('areas as a', 's.id_area', '=', 'a.id')
            ->select(
                's.id',
                'a.nombre_area',
                's.solicita',
                's.dirigido',
                's.fecha',
                's.comprobacion'
            );

        if ($request->has('search') && $request->get('search')['value']) {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('s.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('a.nombre_area', 'like', '%' . $searchValue . '%')
                    ->orWhere('s.solicita', 'like', '%' . $searchValue . '%')
                    ->orWhere('s.dirigido', 'like', '%' . $searchValue . '%');
            });
        }

        $query->orderBy('s.id', 'desc');

        $total = DB::table('solicitudes')->count();
        $start  = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 10);
        $solicitudes = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw'            => (int) $request->get('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $solicitudes,
        ]);
    }

    public function create()
    {
        $areas = Area::query()->where('estatus', 1)->orderBy('nombre_area')->get();

        return view('solicitudes.create', compact('areas'));
    }

    public function store(SolicitudRequest $request)
    {
        DB::connection('mysql')->beginTransaction();

        try {
            $solicitud = Solicitud::create([
                'id_area'          => $request->id_area,
                'id_usuario'       => Auth::id(),
                'fecha'            => $request->fecha,
                'solicita'         => $request->solicita,
                'dirigido'         => $request->dirigido,
                'monto_solicitado' => $request->monto_solicitado,
                'monto_total'      => $request->monto_total,
                'total'            => $request->total,
                'comprobacion'     => (bool) $request->boolean('comprobacion'),
                'estatus'          => $request->input('estatus', 1),
            ]);

            $this->procesarOficio($request, $solicitud->id, null, 'oficio_inicio', 'num_oficio_inicio', 'archivo_oficio_inicio');
            $this->procesarOficio($request, $solicitud->id, null, 'oficio_oficial_mayor', 'num_oficio_oficial_mayor', 'archivo_oficio_oficial_mayor');
            $this->procesarOficio($request, $solicitud->id, null, 'oficio_fiscal', 'num_oficio_fiscal', 'archivo_oficio_fiscal');

            DB::connection('mysql')->commit();

            return redirect()
                ->route('solicitudes.success', $solicitud)
                ->with('success', 'La solicitud se registró correctamente.');
        } catch (\Exception $e) {
            DB::connection('mysql')->rollBack();

            return back()->withInput()->with('error', 'Hubo un error al registrar la solicitud.');
        }
    }

    public function edit(Solicitud $solicitud)
    {
        $areas = Area::query()->where('estatus', 1)->orderBy('nombre_area')->get();
        $solicitud->load('oficios');
        $oficiosPorTipo = $solicitud->oficios->keyBy('tipo_oficio');

        return view('solicitudes.edit', compact('solicitud', 'areas', 'oficiosPorTipo'));
    }

    public function update(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'id_area'                      => 'required|integer|exists:areas,id',
            'fecha'                        => 'required|date',
            'solicita'                     => 'required|string|max:255',
            'dirigido'                     => 'required|string|max:255',
            'monto_solicitado'             => 'required|numeric|min:0',
            'monto_total'                  => 'required|numeric|min:0',
            'total'                        => 'required|numeric|min:0',
            'comprobacion'                 => 'nullable|boolean',
            'num_oficio_inicio'            => 'nullable|string|max:100',
            'archivo_oficio_inicio'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'num_oficio_oficial_mayor'     => 'nullable|string|max:100',
            'archivo_oficio_oficial_mayor' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'num_oficio_fiscal'            => 'nullable|string|max:100',
            'archivo_oficio_fiscal'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::connection('mysql')->beginTransaction();

        try {
            $solicitud->update([
                'id_area'          => $request->id_area,
                'fecha'            => $request->fecha,
                'solicita'         => $request->solicita,
                'dirigido'         => $request->dirigido,
                'monto_solicitado' => $request->monto_solicitado,
                'monto_total'      => $request->monto_total,
                'total'            => $request->total,
                'comprobacion'     => (bool) $request->boolean('comprobacion'),
                'estatus'          => $request->input('estatus', $solicitud->estatus),
            ]);

            $solicitud->load('oficios');
            $oficiosPorTipo = $solicitud->oficios->keyBy('tipo_oficio');

            $this->procesarOficio($request, $solicitud->id, $oficiosPorTipo->get('oficio_inicio'),        'oficio_inicio',        'num_oficio_inicio',            'archivo_oficio_inicio');
            $this->procesarOficio($request, $solicitud->id, $oficiosPorTipo->get('oficio_oficial_mayor'), 'oficio_oficial_mayor', 'num_oficio_oficial_mayor',     'archivo_oficio_oficial_mayor');
            $this->procesarOficio($request, $solicitud->id, $oficiosPorTipo->get('oficio_fiscal'),        'oficio_fiscal',        'num_oficio_fiscal',            'archivo_oficio_fiscal');

            DB::connection('mysql')->commit();

            return redirect()
                ->route('solicitudes.show')
                ->with('success', 'La solicitud se actualizó correctamente.');
        } catch (\Exception $e) {
            DB::connection('mysql')->rollBack();

            return back()->withInput()->with('error', 'Hubo un error al actualizar la solicitud.');
        }
    }

    public function success(Solicitud $solicitud)
    {
        $solicitud->load(['area', 'usuario', 'oficios']);

        return view('solicitudes.success', compact('solicitud'));
    }

    /**
     * Crea o actualiza un oficio para la solicitud.
     * Si $oficioExistente es null se intenta crear (solo si hay datos).
     * Si ya existe se actualiza número y/o archivo.
     */
    private function procesarOficio(
        Request $request,
        int $idSolicitud,
        ?Oficio $oficioExistente,
        string $tipoOficio,
        string $campoNumero,
        string $campoArchivo
    ): void {
        $numeroOficio = $request->input($campoNumero);
        $rutaArchivo  = null;

        if ($request->hasFile($campoArchivo)) {
            $rutaArchivo = $request->file($campoArchivo)->store('oficios', 'public');
        }

        if ($oficioExistente) {
            $datos = [];
            if (!empty($numeroOficio)) {
                $datos['num_oficio'] = $numeroOficio;
            }
            if ($rutaArchivo) {
                $datos['url'] = 'storage/' . $rutaArchivo;
            }
            if (!empty($datos)) {
                $oficioExistente->update($datos);
            }
        } else {
            if (empty($numeroOficio) && empty($rutaArchivo)) {
                return;
            }
            Oficio::create([
                'id_solicitud' => $idSolicitud,
                'tipo_oficio'  => $tipoOficio,
                'num_oficio'   => $numeroOficio ?? '',
                'url'          => $rutaArchivo ? 'storage/' . $rutaArchivo : '',
                'estatus'      => 1,
            ]);
        }
    }
}
