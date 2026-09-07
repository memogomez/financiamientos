<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitudRequest;
use App\Models\Area;
use App\Models\Oficio;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\IOFactory;

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
                'observaciones'    => $request->observaciones,
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
            'observaciones'                => 'nullable|string',
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
                'observaciones'    => $request->observaciones,
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

    public function generarOficio(Solicitud $solicitud)
    {
        $solicitud->load(['area', 'oficios']);
        $oficiosPorTipo = $solicitud->oficios->keyBy('tipo_oficio');
        $oficioInicio   = $oficiosPorTipo->get('oficio_inicio');

        $numOficio  = $oficioInicio?->num_oficio ?: '_______________';
        $fechaDoc   = $solicitud->fecha
            ? \Carbon\Carbon::parse($solicitud->fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY')
            : now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
        $monto      = '$' . number_format((float) $solicitud->monto_solicitado, 2, '.', ',');
        $montoLetras = strtoupper(number_format((float) $solicitud->monto_solicitado, 2)) . ' M.N.';
        $dirigido   = $solicitud->dirigido ?? '_______________';
        $solicita   = $solicitud->solicita ?? '_______________';
        $area       = $solicitud->area?->nombre_area ?? '_______________';

        // ── Imágenes ──────────────────────────────────────────────────────────
        $imgEdomex = public_path('images/edomex.png');
        $imgLogo   = public_path('images/logo.png');

        // ── Documento ─────────────────────────────────────────────────────────
        $phpWord = new PhpWord();
        // Márgenes en twips (1 cm ≈ 567 twips)
        $phpWord->addSection([
            'marginTop'    => 850,
            'marginBottom' => 850,
            'marginLeft'   => 1200,
            'marginRight'  => 1200,
            'paperSize'    => 'Letter',
        ]);
        $section = $phpWord->getSection(0);

        // ── Estilos globales ──────────────────────────────────────────────────
        $phpWord->addFontStyle('normal',    ['name' => 'Arial', 'size' => 10]);
        $phpWord->addFontStyle('bold',      ['name' => 'Arial', 'size' => 10, 'bold' => true]);
        $phpWord->addFontStyle('boldUnder', ['name' => 'Arial', 'size' => 10, 'bold' => true, 'underline' => 'single']);
        $phpWord->addFontStyle('center10',  ['name' => 'Arial', 'size' => 10]);
        $phpWord->addFontStyle('small',     ['name' => 'Arial', 'size' => 8]);
        $phpWord->addFontStyle('italic10',  ['name' => 'Arial', 'size' => 10, 'italic' => true]);

        // ── ENCABEZADO: tabla con logos ───────────────────────────────────────
        $tblHeader = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF', 'cellMargin' => 0]);
        $tblHeader->addRow(1200);

        $cellLeft = $tblHeader->addCell(2000);
        if (file_exists($imgEdomex)) {
            $cellLeft->addImage($imgEdomex, ['width' => 70, 'height' => 70, 'alignment' => Jc::LEFT]);
        }

        $cellCenter = $tblHeader->addCell(6500);
        $cellCenter->addText(''); // espaciado

        $cellRight = $tblHeader->addCell(2000);
        if (file_exists($imgLogo)) {
            $cellRight->addImage($imgLogo, ['width' => 70, 'height' => 70, 'alignment' => Jc::RIGHT]);
        }

        // ── Línea separadora ─────────────────────────────────────────────────
        $section->addTextBreak(1);
        $parBorder = $section->addText('', 'normal', ['borderBottomSize' => 6, 'borderBottomColor' => '000000', 'spaceAfter' => 60]);

        // ── Año del lema ─────────────────────────────────────────────────────
        $section->addText(
            '"' . now()->year . '. AÑO DEL HUMANISMO MEXICANO EN EL ESTADO DE MÉXICO"',
            ['name' => 'Arial', 'size' => 9, 'bold' => true],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 100]
        );

        // ── Número de oficio y fecha (derecha) ────────────────────────────────
        $section->addText(
            "Oficio Número: {$numOficio}.",
            ['name' => 'Arial', 'size' => 10],
            ['alignment' => Jc::RIGHT, 'spaceAfter' => 0]
        );
        $section->addText(
            "Toluca, México, a {$fechaDoc}.",
            ['name' => 'Arial', 'size' => 10],
            ['alignment' => Jc::RIGHT, 'spaceAfter' => 300]
        );

        // ── Destinatario ─────────────────────────────────────────────────────
        $section->addText($dirigido . ',', 'bold', ['spaceAfter' => 0]);
        $section->addText("Área: {$area}.", 'normal', ['spaceAfter' => 0]);
        $section->addText('P r e s e n t e.', 'bold', ['spaceAfter' => 300]);

        // ── Cuerpo del oficio ─────────────────────────────────────────────────
        $cuerpo = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 200]);
        $cuerpo->addText("\t");
        $cuerpo->addText(
            "Me refiero al oficio presentado por ",
            ['name' => 'Arial', 'size' => 10]
        );
        $cuerpo->addText(
            $solicita,
            ['name' => 'Arial', 'size' => 10, 'bold' => true]
        );
        $cuerpo->addText(
            ", mediante el cual, entre otros, solicita se autorice la ministración de recursos por la cantidad de ",
            ['name' => 'Arial', 'size' => 10]
        );
        $cuerpo->addText(
            "{$monto} ({$montoLetras})",
            ['name' => 'Arial', 'size' => 10, 'bold' => true]
        );
        $cuerpo->addText(
            ", con el propósito de solventar los gastos inherentes a las actividades del área solicitante.",
            ['name' => 'Arial', 'size' => 10]
        );

        if ($solicitud->observaciones) {
            $section->addTextBreak(1);
            $obs = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 200]);
            $obs->addText("\t");
            $obs->addText($solicitud->observaciones, ['name' => 'Arial', 'size' => 10]);
        }

        // ── Párrafo de cierre ─────────────────────────────────────────────────
        $section->addTextBreak(1);
        $cierre = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 400]);
        $cierre->addText("\t");
        $cierre->addText(
            'Lo anterior, para los efectos y trámites correspondientes.',
            ['name' => 'Arial', 'size' => 10]
        );

        // ── Atentamente + firma ───────────────────────────────────────────────
        $section->addText('Atentamente', ['name' => 'Arial', 'size' => 10], ['alignment' => Jc::CENTER, 'spaceAfter' => 800]);
        $section->addText(
            'LIC. JOSÉ LUIS CERVANTES MARTÍNEZ,',
            ['name' => 'Arial', 'size' => 10, 'bold' => true],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
        );
        $section->addText(
            'Fiscal General de Justicia del Estado de México.',
            ['name' => 'Arial', 'size' => 10],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 600]
        );

        // ── Línea pie + datos institucionales ────────────────────────────────
        $section->addText('', 'normal', ['borderTopSize' => 6, 'borderTopColor' => '000000', 'spaceAfter' => 60]);
        $section->addText(
            'FISCALÍA GENERAL DE JUSTICIA DEL ESTADO DE MÉXICO',
            ['name' => 'Arial', 'size' => 8, 'bold' => true],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
        );
        $section->addText(
            'AV. MORELOS ORIENTE NO. 1300, 6TO PISO, COL. SAN SEBASTIÁN, TOLUCA, ESTADO DE MÉXICO, C.P. 50090',
            ['name' => 'Arial', 'size' => 7],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
        );
        $section->addText(
            'TELS. 722 226 16 00, 722 226 17 00. Ext. 73325',
            ['name' => 'Arial', 'size' => 7],
            ['alignment' => Jc::CENTER]
        );

        // ── Descarga ──────────────────────────────────────────────────────────
        $nombreArchivo = "oficio_solicitud_{$solicitud->id}.docx";

        return response()->streamDownload(function () use ($phpWord) {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        }, $nombreArchivo, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Cache-Control' => 'max-age=0',
        ]);
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
