<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NonasnBiodataRequest extends FormRequest
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
            'nik' => ['numeric'],
            'kk' => ['numeric'],
            'no_hp' => ['required','numeric'],
            'no_bpjs' => ['required','numeric'],
            'kelas' => ['required'],
            'tempat_lahir' => ['required'],
            'thn_lahir' => ['required','date_format:d/m/Y'],
            'foto' => ['image', 'mimes:jpg,png,jpeg','max:200']
        ];
    }

    public function messages()
    {
        return [
            'nik.numeric' => 'nik hanya boleh diisi angka',
            'kk.numeric' => 'kk hanya boleh diisi angka',
            'no_hp.required' => 'nomor hp harus diisi',
            'no_hp.numeric' => 'nomor hp hanya boleh diisi angka',
            'tempat_lahir.required' => 'tempat lahir harus diisi',
            'thn_lahir.required' => 'tanggal lahir harus diisi',
            'thn_lahir.date_format' => 'format tanggal lahir tidak sesuai',
            'no_bpjs.required' => 'nomor bpjs/kis harus diisi',
            'no_bpjs.numeric' => 'nomor bpjs hanya boleh diisi angka',
            'kelas.required' => 'kelas bpjs/kis harus dipilih',
            'foto.mimes' => 'format file harus jpg/png',
            'foto.max' => 'foto yang diupload maksimal 200 KB'
        ];
    }
}
