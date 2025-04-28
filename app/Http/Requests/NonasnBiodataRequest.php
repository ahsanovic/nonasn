<?php

namespace App\Http\Requests;

use Hashids\Hashids;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'), 10);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $decodedId = $this->_hashId()->decode($this->route('id'))[0] ?? null;
        return [
            'nik' => ['required', 'numeric', 'digits:16', Rule::unique('ptt_biodata', 'nik')->ignore($decodedId, 'id_ptt')],
            'kk' => ['numeric'],
            'no_hp' => ['required', 'numeric', 'digits_between:10,12'],
            'no_bpjs' => ['required', 'numeric'],
            'kelas' => ['required'],
            'no_bpjs_naker' => ['nullable', 'numeric', 'digits:11'],
            'tempat_lahir' => ['required'],
            'thn_lahir' => ['required', 'date_format:d/m/Y'],
            'foto' => ['image', 'mimes:jpg,png,jpeg', 'max:200'],
            'email' => ['email'],
            'jk' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'nik.required' => 'nik harus diisi',
            'nik.numeric' => 'nik hanya boleh diisi angka',
            'nik.digits' => 'nik harus 16 digit',
            'nik.unique' => 'nik sudah terdaftar',
            'kk.numeric' => 'kk hanya boleh diisi angka',
            'no_hp.required' => 'nomor hp harus diisi',
            'no_hp.numeric' => 'nomor hp hanya boleh diisi angka',
            'tempat_lahir.required' => 'tempat lahir harus diisi',
            'thn_lahir.required' => 'tanggal lahir harus diisi',
            'thn_lahir.date_format' => 'format tanggal lahir tidak sesuai',
            'no_bpjs.required' => 'nomor bpjs/kis harus diisi',
            'no_bpjs.numeric' => 'nomor bpjs hanya boleh diisi angka',
            'kelas.required' => 'kelas bpjs/kis harus dipilih',
            'no_bpjs_naker.numeric' => 'nomor bpjs ketenagakerjaan hanya boleh diisi angka',
            'no_bpjs_naker.digits' => 'nomor bpjs ketenagakerjaan harus 11 digit',
            'foto.mimes' => 'format file harus jpg/png',
            'foto.max' => 'foto yang diupload maksimal 200 KB',
            'no_hp.digits_between' => 'nomor hp minimal 10 digit dan maksimal 12 digit',
            'email.email' => 'format email tidak valid',
            'jk.required' => 'jenis kelamin harus dipilih'
        ];
    }
}
