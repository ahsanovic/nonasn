<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserFasilitatorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'username' => ['required', 'min:4', 'unique:users,username'],
            'nama_lengkap' => ['required', 'string'],
            'password' => ['required', 'min:8'],
            'email' => ['required', 'email'],
            'no_telp' => ['required', 'numeric'],
            'skpd' => ['required'],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['password'] = ['min:8'];
            $rules['username'] = [Rule::unique('users', 'username')->ignore($this->username, 'username')];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        // if password field is empty then remove its validation
        if ($this->password == null) {
            $this->request->remove('password');
        }
    }

    public function messages()
    {
        return [
            'username.required' => 'username harus diisi',
            'username.min' => 'username minimal 4 karakter',
            'username.unique' => 'username sudah ada',
            'nama_lengkap.required' => 'nama harus diisi',
            'password.required' => 'password harus diisi',
            'password.min' => 'password minimal 8 karakter',
            'email.required' => 'email harus diisi',
            'email.email' => 'format email harus benar',
            'no_telp.required' => 'no hp harus diisi',
            'no_telp.numeric' => 'no hp hanya boleh diisi angka',
            'skpd.required' => 'unit kerja harus diisi',
        ];
    }

    public function filters()
    {
        return [
            'email' => 'trim|lowercase',
            'username' => 'trim|lowercase|escape'
        ];
    }
}
