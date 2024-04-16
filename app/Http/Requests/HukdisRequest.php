<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HukdisRequest extends FormRequest
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
            'jenis_hukdis_id' => ['required'],
            'no_sk' => ['required'],
            'tgl_sk' => ['required','date_format:d/m/Y'],
            'tmt_awal' => ['required','date_format:d/m/Y'],
            'file_hukdis' => ['required','file','mimes:pdf','max:1024'],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['file_hukdis'] = ['file','mimes:pdf','max:1024'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        // if file field is empty then remove its validation
        if ($this->file == null) {
            $this->request->remove('file_hukdis');
        }
    }

    public function messages()
    {
        return [
            'jenis_hukdis_id.required' => 'jenis hukuman disiplin harus dipilih',
            'no_sk.required' => 'nomor sk harus diisi',
            'tgl_sk.required' => 'tanggal sk harus diisi',
            'tgl_sk.date_format' => 'tanggal sk tidak sesuai format (d/m/Y)',
            'tmt_awal.required' => 'tmt hukuman disiplin harus diisi',
            'tmt_awal.date_format' => 'tanggal tmt tidak sesuai format (d/m/Y)',
            'file_hukdis.required' => 'dokumen harus diupload',
            'file_hukdis.mimes' => 'format file dokumen harus pdf',
            'file_hukdis.max' => 'file yang diupload maksimal 1 MB'
        ];
    }
}
