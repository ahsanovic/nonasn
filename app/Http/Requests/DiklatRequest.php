<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiklatRequest extends FormRequest
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
            'jenis_diklat' => ['required', 'numeric'],
            'nama_diklat' => ['required'],
            'no_sertifikat' => ['required'],
            'tgl_sertifikat' => ['required', 'date_format:d/m/Y'],
            'tgl_mulai' => ['required', 'date_format:d/m/Y'],
            'tgl_selesai' => ['required', 'date_format:d/m/Y'],
            'penyelenggara' => ['required'],
            'jml_jam' => ['numeric'],
            'file' => ['required','file', 'mimes:pdf', 'max:1024'],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['file'] = ['file','mimes:pdf','max:1024'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        // if file field is empty then remove its validation
        if ($this->file == null) {
            $this->request->remove('file');
        }

        if ($this->jml_jam == null) {
            $this->request->remove('jml_jam');
        }
    }

    public function messages()
    {
        return [
            'jenis_diklat.required' => 'jenis diklat harus dipilih',
            'nama_diklat.required' => 'nama diklat harus diisi',
            'no_sertifikat.required' => 'nomor sertifikat harus diisi',
            'tgl_sertifikat.required' => 'tanggal sertifikat harus diisi',
            'tgl_mulai.required' => 'tanggal mulai harus diisi',
            'tgl_selesai.required' => 'tanggal selesai harus diisi',
            'penyelenggara.required' => 'penyelenggara harus diisi',
            'jml_jam.numeric' => 'jumlah jam harus angka',
            'file.required' => 'dokumen harus diupload',
            'file.mimes' => 'format dokumen harus pdf',
            'file.max' => 'dokumen yang diupload maksimal 1 MB'
        ];
    }
}
