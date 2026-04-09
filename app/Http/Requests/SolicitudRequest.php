<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_area' => 'required|integer|exists:areas,id',
            'fecha' => 'required|date',
            'solicita' => 'required|string|max:255',
            'dirigido' => 'required|string|max:255',
            'monto_solicitado' => 'required|numeric|min:0',
            'monto_total' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'comprobacion' => 'nullable|boolean',
            'estatus' => 'sometimes|integer',
            'num_oficio_inicio' => 'nullable|string|max:100',
            'archivo_oficio_inicio' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'num_oficio_oficial_mayor' => 'nullable|string|max:100',
            'archivo_oficio_oficial_mayor' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'num_oficio_fiscal' => 'nullable|string|max:100',
            'archivo_oficio_fiscal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'id_area.required' => 'Debes seleccionar un área',
            'id_area.exists' => 'El área seleccionada no es válida',
            'fecha.required' => 'La fecha es obligatoria',
            'solicita.required' => 'El campo "Solicita" es obligatorio',
            'dirigido.required' => 'El campo "Dirigido" es obligatorio',
            'monto_solicitado.required' => 'El monto solicitado es obligatorio',
            'monto_total.required' => 'El monto total es obligatorio',
            'total.required' => 'El total es obligatorio',
        ];
    }
}
