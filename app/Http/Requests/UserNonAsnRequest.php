<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserNonAsnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => ['min:8'],
        ];
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
            'password.min' => 'password minimal 8 karakter',
        ];
    }
}
