<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuamiIstriRequest extends FormRequest
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
        $rules = [
            'nama' => ['required'],
            'tempat_lahir' => ['required'],
            'tgl_lahir' => ['required','date_format:d/m/Y'],
            'pekerjaan' => ['required'],
            'status' => ['required'],
            'no_bpjs' => ['required','numeric'],
            'kelas' => ['required'],
            'file_bpjs' => ['required','file','mimes:jpg,png,jpeg','max:200'],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['file_bpjs'] = ['file','mimes:jpg,png,jpeg','max:200'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nama.required' => 'nama harus diisi',
            'tempat_lahir.required' => 'tempat lahir harus diisi',
            'tgl_lahir.required' => 'tanggal lahir harus diisi',
            'tgl_lahir.date_format' => 'format tanggal tidak sesuai',
            'pekerjaan.required' => 'pekerjaan harus dipilih',
            'status.required' => 'status harus dipilih',
            'no_bpjs.required' => 'nomor bpjs harus diisi',
            'no_bpjs.numeric' => 'nomor bpjs hanya boleh diisi angka',
            'kelas.required' => 'kelas bpjs harus dipilih',
            'file_bpjs.required' => 'kartu bpjs harus diupload',
            'file_bpjs.mimes' => 'format file harus jpg/png',
            'file_bpjs.max' => 'file yang diupload maksimal 200 KB'
        ];

    }
}
