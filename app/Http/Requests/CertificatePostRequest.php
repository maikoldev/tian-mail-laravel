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
            'avatar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'avatar.required' => 'El campo imagen es obligatorio.',
            'avatar.image' => 'El campo imagen debe ser una imagen.',
            'avatar.mimes' => 'El campo imagen debe ser un archivo de tipo: jpg, png, jpeg.',
            'avatar.max' => 'El campo imagen no debe ser mayor a 2MB.',
        ];
    }
}
