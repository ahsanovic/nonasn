<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordValidateRequest extends FormRequest
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
            'password' => [
                'required',
                // 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                'regex:/^(?=.*[A-Z])(?=.*[0-9])/',
                'min:8',
                'confirmed'
            ],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'password baru harus diisi',
            'password.regex' => 'password baru harus berupa kombinasi huruf kecil, huruf besar dan angka',
            'password.min' => 'password minimal 8 karakter',
        ];
    }
}
