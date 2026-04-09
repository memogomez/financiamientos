<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreaRequest;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function show()
    {
        $areas = Area::orderBy('nombre_area')->get();

        return view('areas.show', compact('areas'));
    }

    public function store(AreaRequest $request)
    {
        try {
            Area::create([
                'nombre_area' => $request->nombre_area,
                'estatus'     => 1,
            ]);

            return redirect()
                ->route('areas.show')
                ->with('success', 'El área se registró correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->route('areas.show')
                ->withInput()
                ->with('error', 'Hubo un error al registrar el área.');
        }
    }

    public function update(AreaRequest $request, Area $area)
    {
        try {
            $area->update([
                'nombre_area' => $request->nombre_area,
            ]);

            return redirect()
                ->route('areas.show')
                ->with('success', 'El área se actualizó correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->route('areas.show')
                ->withInput()
                ->with('error', 'Hubo un error al actualizar el área.');
        }
    }

    public function toggleEstatus(Request $request, Area $area)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        $area->estatus = $area->estatus ? 0 : 1;
        $area->save();

        return response()->json([
            'success' => true,
            'estatus' => $area->estatus,
            'label'   => $area->estatus ? 'Activo' : 'Inactivo',
        ]);
    }
}
