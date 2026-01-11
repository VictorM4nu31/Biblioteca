<?php

namespace App\Http\Requests\User\Collection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La política se encarga de verificar la propiedad en el controlador, 
        // aquí solo necesitamos que el usuario tenga permiso de editar colecciones en general
        return $this->user()->can('edit collections');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ];
    }
}
