<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DokumenTesNarkobaRequest extends FormRequest
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
            'tahun' => ['required', 'numeric'],
            'nomor_surat' => ['required'],
            'tgl_surat' => ['required', 'date_format:d/m/Y'],
            'dokter_pemeriksa' => ['required'],
            'instansi' => ['required'],
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
    }

    public function messages()
    {
        return [
            'tahun.required' => 'tahun tes harus diisi',
            'tahun.numeric' => 'tahun tes harus angka',
            'nomor_surat.required' => 'nomor surat harus diisi',
            'tgl_surat.required' => 'tanggal surat harus diisi',
            'dokter_pemeriksa.required' => 'nama dokter harus diisi',
            'instansi.required' => 'instansi harus diisi',
            'file.required' => 'dokumen harus diupload',
            'file.mimes' => 'format dokumen harus pdf',
            'file.max' => 'dokumen yang diupload maksimal 1 MB'
        ];
    }
}
