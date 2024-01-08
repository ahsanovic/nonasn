<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenilaianRequest extends FormRequest
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
            'tahun' => ['required','numeric'],
            'nilai' => ['required','gte:0','numeric', 'max:100'],
            'rekomendasi' => ['required'],
            'file' => ['required','file','mimes:pdf','max:1024']
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
            'tahun.required' => 'tahun penilaian harus diisi',
            'tahun.numeric' => 'tahun hanya boleh diisi angka',
            'nilai.required' => 'nilai harus diisi',
            'nilai.gte' => 'nilai harus lebih dari nol',
            'nilai.numeric' => 'nilai hanya boleh diisi angka dan tanda baca (.)',
            'nilai.max' => 'nilai maksimal 100',
            'rekomendasi.required' => 'rekomendasi harus dipilih',
            'file.required' => 'dokumen harus diupload',
            'file.mimes' => 'format dokumen harus pdf',
            'file.max' => 'file yang diupload maksimal 1 MB'
        ];
    }
}
