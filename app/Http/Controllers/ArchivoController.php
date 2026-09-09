<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

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
            ->select('a.id', 'a.nombre_archivo', 'a.fecha_subida', 'a.fecha_archivo');

        if ($request->has('search') && $request->get('search')['value']) {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('a.nombre_archivo', 'like', '%' . $searchValue . '%');
            });
        }

        $query->orderBy('a.id', 'desc');

        $total = DB::table('archivos')->count();
        $start  = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 10);
        $archivos = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw'            => (int) $request->get('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $archivos,
        ]);
    }

    public function create()
    {
        return view('archivos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_input' => 'required|file|mimes:pdf|max:20480',
            'fecha_archivo' => 'required|date',
        ]);

        try {
            DB::connection('mysql')->beginTransaction();

            $file = $request->file('file_input');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[[:^print:]]/', '', $originalName);

            $baseFileName = pathinfo($cleanName, PATHINFO_FILENAME);
            $slugged = Str::slug($baseFileName) ?: 'archivo';
            $fileName = time() . '_' . $slugged . '.pdf';

            $rutaArchivo = $file->storeAs('upload', $fileName, 'local');
            $absolutePath = storage_path('app/' . $rutaArchivo);

            $archivo = Archivo::create([
                'nombre_archivo' => $cleanName,
                'ruta_archivo' => 'storage/' . $rutaArchivo,
                'fecha_subida' => now(),
                'fecha_archivo' => $request->input('fecha_archivo'),
            ]);

            $pythonPath = env('PYTHON_PATH', 'python');
            $scriptPath = base_path('scripts/extraer_pdf.py');

            $processEnv = [
                'DB_HOST' => (string) config('database.connections.mysql.host'),
                'DB_USERNAME' => (string) config('database.connections.mysql.username'),
                'DB_PASSWORD' => (string) config('database.connections.mysql.password'),
                'DB_DATABASE' => (string) config('database.connections.mysql.database'),
            ];

            if ($pythonPathEnv = env('PYTHONPATH')) {
                $processEnv['PYTHONPATH'] = $pythonPathEnv;
            }

            $process = new Process([
                $pythonPath,
                $scriptPath,
                (string) $archivo->id,
                $absolutePath,
            ], null, $processEnv);

            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                $archivo->delete();
                DB::connection('mysql')->rollBack();

                return back()->withInput()->withErrors([
                    'file_input' => 'Error al procesar el PDF: ' . $process->getErrorOutput() . $process->getOutput(),
                ]);
            }

            DB::connection('mysql')->commit();

            return redirect()->route('archivos.show')->with('success', 'Archivo subido y procesado correctamente.');
        } catch (\Exception $e) {
            DB::connection('mysql')->rollBack();

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
        $query = trim($request->input('query', ''));
        $modo = $request->input('modo', 'palabras');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $resultados = [];

        if ($query) {
            $resultados = DB::table('paginas')
                ->join('archivos', 'archivos.id', '=', 'paginas.archivo_id')
                ->select('archivos.nombre_archivo', 'paginas.numero_pagina', 'paginas.texto', 'archivos.fecha_archivo')
                ->when($modo === 'frase', fn ($q) => $q->where('paginas.texto', 'LIKE', "%{$query}%"))
                ->when($modo !== 'frase', function ($q) use ($query) {
                    $palabras = preg_split('/\s+/', $query);
                    foreach ($palabras as $palabra) {
                        $q->where('paginas.texto', 'LIKE', "%{$palabra}%");
                    }
                    return $q;
                })
                ->when($fechaInicio, fn ($q) => $q->whereDate('archivos.fecha_archivo', '>=', $fechaInicio))
                ->when($fechaFin, fn ($q) => $q->whereDate('archivos.fecha_archivo', '<=', $fechaFin))
                ->orderBy('archivos.fecha_archivo', 'desc')
                ->get();
        }

        return view('archivos.buscar', compact('resultados', 'query', 'modo', 'fechaInicio', 'fechaFin'));
    }
}
