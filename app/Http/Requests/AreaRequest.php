<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_area' => 'required|string|max:150',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_area.required' => 'El nombre del área es obligatorio',
            'nombre_area.max'      => 'El nombre no debe superar 150 caracteres',
        ];
    }
}
