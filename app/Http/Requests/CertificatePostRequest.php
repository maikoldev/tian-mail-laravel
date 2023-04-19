<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificatePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avatar' => 'required|file|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'avatar.required' => 'El campo Foto es obligatorio.',
            'avatar.file' => 'El campo Foto debe ser un archivo.',
            'avatar.mimes' => 'El campo Foto debe ser un archivo de tipo: jpg, png, jpeg.',
            'avatar.max' => 'El campo Foto no debe ser mayor a 2MB.',
        ];
    }
}
