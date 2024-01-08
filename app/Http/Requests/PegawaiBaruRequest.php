<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiBaruRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'niptt' => ['required', 'numeric'],
            'nama' => ['required', 'string'],
            'skpd' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'niptt.required' => 'niptt harus diisi',
            'niptt.numeric' => 'niptt hanya boleh diisi angka',
            'nama.required' => 'nama lengkap harus diisi',
            'skpd.required' => 'unit kerja harus diisi',
        ];
    }
}
