<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnakRequest extends FormRequest
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
            'ortu' => ['required'],
            'nama' => ['required'],
            'status' => ['required'],
            'tempat_lahir' => ['required'],
            'tgl_lahir' => ['required','date_format:d/m/Y'],
            'pekerjaan' => ['required'],
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
            'ortu.required' => 'orang tua harus dipilih',
            'nama.required' => 'nama anak harus diisi',
            'status.required' => 'status anak harus dipilih',
            'tempat_lahir.required' => 'tempat lahir harus diisi',
            'tgl_lahir.required' => 'tanggal lahir harus diisi',
            'tgl_lahir.date_format' => 'tanggal surat tidak sesuai format (d/m/Y)',
            'pekerjaan.required' => 'pekerjaan harus dipilih',
            'no_bpjs.required' => 'nomor bpjs harus diisi',
            'no_bpjs.numeric' => 'nomor bpjs hanya boleh diisi angka',
            'kelas.required' => 'kelas bpjs harus dipilih',
            'file_bpjs.required' => 'kartu bpjs harus diupload',
            'file_bpjs.mimes' => 'format file harus jpg/png',
            'file_bpjs.max' => 'file yang diupload maksimal 200 KB'
        ];
    }
}
