<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GajiNonPttRequest extends FormRequest
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
            'tmt_awal' => ['required','date_format:d/m/Y'],
            'tmt_akhir' => ['required','date_format:d/m/Y'],
            'nominal_gaji' => ['required', 'numeric'],
            'link_gdrive' => ['sometimes', 'url'],
            // 'file_gaji' => ['required','file','mimes:pdf','max:1024']
        ];

        // if ($this->has('link_gdrive')) {
        //     $rules['link_gdrive'] = ['required', 'url'];
        // }

        // if (in_array($this->method(), ['PUT', 'PATCH'])) {
        //     $rules['file_dpa'] = ['file','mimes:pdf','max:1024'];
        //     $rules['file_gaji'] = ['file','mimes:pdf','max:1024'];
        // }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['file_dpa'] = ['file','mimes:pdf','max:1024'];

            // Validasi file_gaji hanya jika file diupload saat update
            if ($this->hasFile('file_gaji')) {
                $rules['file_gaji'] = ['file','mimes:pdf','max:1024'];
            }
        }

        return $rules;
    }

    // protected function prepareForValidation()
    // {
    //     // if file field is empty then remove its validation
    //     if ($this->file_gaji == null) {
    //         $this->request->remove('file_gaji');
    //     }
    // }

    public function messages()
    {
        return [
            'tahun.required' => 'tahun harus diisi',
            'tahun.numeric' => 'tahun hanya boleh diisi angka',
            'tmt_awal.required' => 'tmt awal kontrak harus diisi',
            'tmt_awal.date_format' => 'tanggal tidak sesuai format (d/m/Y)',
            'tmt_akhir.required' => 'tmt akhir kontrak harus diisi',
            'tmt_akhir.date_format' => 'tanggal tidak sesuai format (d/m/Y)',
            'nominal_gaji.required' => 'gaji harus diisi',
            'nominal_gaji.numeric' => 'gaji hanya boleh diisi angka',
            'file_gaji.required' => 'dokumen harus diupload',
            'file_gaji.mimes' => 'format dokumen harus pdf',
            'file_gaji.max' => 'file yang diupload maksimal 1 MB',
            'link_gdrive.url' => 'url harus valid',
        ];
    }
}
