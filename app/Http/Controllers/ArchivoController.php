<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchivoController extends Controller
{
    public function show()
    {
        return view('archivos.show');
    }

    public function paginate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([]);
        }

        $query = DB::table('archivos as a')
            ->leftJoin('areas as ar', 'a.id_area', '=', 'ar.id')
            ->select('a.id', 'a.nombre_archivo', 'a.ruta_archivo', 'a.fecha_subida', 'a.fecha_archivo', 'a.numero_oficio', 'ar.nombre_area');

        if ($request->has('search') && $request->get('search')['value']) {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('a.nombre_archivo', 'like', '%' . $searchValue . '%')
                  ->orWhere('a.numero_oficio', 'like', '%' . $searchValue . '%')
                  ->orWhere('ar.nombre_area', 'like', '%' . $searchValue . '%');
            });
        }

        $query->orderBy('a.id', 'desc');

        $total = DB::table('archivos')->count();
        $start  = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 10);
        $archivos = $query->offset($start)->limit($length)->get();

        $archivos = $archivos->map(function ($archivo) {
            $archivo->archivo_fisico = basename($archivo->ruta_archivo);
            return $archivo;
        });

        return response()->json([
            'draw'            => (int) $request->get('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $archivos,
        ]);
    }

    public function create()
    {
        $areas = Area::where('estatus', 1)->orderBy('nombre_area')->get();
        return view('archivos.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_input' => 'required|file|mimes:pdf|max:20480',
            'fecha_archivo' => 'required|date',
            'numero_oficio' => 'required|string|unique:archivos,numero_oficio',
            'id_area' => 'required|exists:areas,id',
        ]);

        try {
            $file = $request->file('file_input');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[[:^print:]]/', '', $originalName);

            $baseFileName = pathinfo($cleanName, PATHINFO_FILENAME);
            $slugged = Str::slug($baseFileName) ?: 'archivo';
            $fileName = time() . '_' . $slugged . '.pdf';

            $uploadDir = storage_path('app/upload');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $absolutePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
            $file->move($uploadDir, $fileName);

            if (!file_exists($absolutePath)) {
                return back()->withInput()->withErrors([
                    'file_input' => 'No se pudo guardar el archivo. Verifica que la carpeta storage/app/upload/ tenga permisos de escritura.',
                ]);
            }

            $rutaArchivo = 'upload/' . $fileName;

            Archivo::create([
                'nombre_archivo' => $cleanName,
                'ruta_archivo' => 'storage/' . $rutaArchivo,
                'fecha_subida' => now(),
                'fecha_archivo' => $request->input('fecha_archivo'),
                'numero_oficio' => $request->input('numero_oficio'),
                'id_area' => $request->input('id_area'),
            ]);

            return redirect()->route('archivos.show')->with('success', 'Archivo subido correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'file_input' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function descargar($nombre)
    {
        $rutaArchivo = "upload/$nombre";

        if (!Storage::exists($rutaArchivo)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::download($rutaArchivo);
    }

    public function buscar(Request $request)
    {
        $numeroOficio = trim($request->input('numero_oficio', ''));
        $nombreArchivo = trim($request->input('nombre_archivo', ''));
        $idArea = $request->input('id_area');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $areas = Area::where('estatus', 1)->orderBy('nombre_area')->get();
        $resultados = [];

        if ($numeroOficio || $nombreArchivo || $idArea || $fechaInicio || $fechaFin) {
            $query = DB::table('archivos as a')
                ->leftJoin('areas as ar', 'a.id_area', '=', 'ar.id')
                ->select('a.id', 'a.nombre_archivo', 'a.numero_oficio', 'a.fecha_archivo', 'ar.nombre_area', 'a.ruta_archivo');

            if ($numeroOficio) {
                $query->where('a.numero_oficio', 'like', "%{$numeroOficio}%");
            }

            if ($nombreArchivo) {
                $query->where('a.nombre_archivo', 'like', "%{$nombreArchivo}%");
            }

            if ($idArea) {
                $query->where('a.id_area', $idArea);
            }

            if ($fechaInicio) {
                $query->whereDate('a.fecha_archivo', '>=', $fechaInicio);
            }

            if ($fechaFin) {
                $query->whereDate('a.fecha_archivo', '<=', $fechaFin);
            }

            $resultados = $query->orderBy('a.fecha_archivo', 'desc')->get();

            $resultados = $resultados->map(function ($archivo) {
                $archivo->archivo_fisico = basename($archivo->ruta_archivo);
                return $archivo;
            });
        }

        return view('archivos.buscar', compact('resultados', 'numeroOficio', 'nombreArchivo', 'idArea', 'fechaInicio', 'fechaFin', 'areas'));
    }
}
