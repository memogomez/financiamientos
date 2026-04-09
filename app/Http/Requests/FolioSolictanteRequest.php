<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FolioSolictanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'ticket' => 'max:255',
            'acronimo' => 'required|string|max:255',
            'hora' => 'required|digits:4',
            'dia_mes' => 'required|digits:4',
            'anio' => 'required|digits:4',
            'razon' => 'required',
            'numero_registro' => 'required|string|max:255',
            'detenido' => 'required|string|max:255',
            'id_delito' => 'required|integer',
            'nombre_solicitante' => 'required|string|max:255',
            'plaza' => 'required|string|max:255',
            'gafete' => 'required|string|max:255',
            'agencia_mp' => 'required|string|max:255',
            'turno' => 'required|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'ticket.max' => 'El campo no debe ser mayor a 255 caracteres',
            'acronimo.required' => 'El campo acrónimo es obligatorio',
            'acronimo.string' => 'Ingresa una cadena de texto',
            'acronimo.max' => 'El campo no debe ser mayor a 255 caracteres',
            'hora.required' => 'El campo hora es obligatorio',
            'hora.digits' => 'Debes agregar un número de 4 digitos',
            'dia_mes.required' => 'El campo día/mes es obligatorio',
            'dia_mes.digits' => 'Debes agregar un número de 4 digitos',
            'anio.required' => 'El campo año es obligatorio',
            'anio.digits' => 'Debes agregar un número de 4 digitos',
            'razon' => 'El campo razón es obligatorio',
            'numero_registro.required' => 'Este campo es obligatorio',
            'numero_registro.string' => 'Ingresa una cadena de texto',
            'numero_registro.max' => 'El campo no debe ser mayor a 255 caracteres',
            'detenido.required' => 'El campo detenido es obligatorio',
            'detenido.string' => 'Ingresa una cadena de texto',
            'detenido.max' => 'El campo no debe ser mayor a 255 caracteres',
            'id_delito.required' => 'El campo delito es obligatorio',
            'id_delito.integer' => 'Este campo solo acepta números',
            'nombre_solicitante.required' => 'El campo nombre es obligatorio',
            'nombre_solicitante.string' => 'Ingresa una cadena de texto',
            'nombre_solicitante.max' => 'El campo no debe ser mayor a 255 caracteres',
            'plaza.required' => 'El campo plaza es obligatorio',
            'plaza.string' => 'Ingresa una cadena de texto',
            'plaza.max' => 'El campo no debe ser mayor a 255 caracteres',
            'gafete.required' => 'El campo gafete es obligatorio',
            'gafete.string' => 'Ingresa una cadena de texto',
            'gafete.max' => 'El campo no debe ser mayor a 255 caracteres',
            'agencia_mp.required' => 'El campo agencia es obligatorio',
            'agencia_mp.string' => 'Ingresa una cadena de texto',
            'agencia_mp.max' => 'El campo no debe ser mayor a 255 caracteres',
            'turno.required' => 'El campo turno es obligatorio',
            'turno.string' => 'Ingresa una cadena de texto',
            'turno.max' => 'El campo no debe ser mayor a 255 caracteres',
        ];
    }

    public function folioData(): array {
        return $this->only([
            'ticket',
            'acronimo',
            'hora',
            'dia_mes',
            'anio',
            'folio',
            'razon',
            'numero_registro',
            'id_delito',
            'detenido',
        ]);
    }

    public function solicitanteData(): array {
        return $this->only([
            'nombre_solicitante',
            'plaza',
            'gafete',
            'agencia_mp',
            'turno',
        ]);
    }
    
}
