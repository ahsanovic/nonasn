<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JabatanRequest extends FormRequest
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
            'jabatan' => ['required'],
            'no_surat' => ['required'],
            'pejabat_penetap' => ['required'],
            'tgl_surat' => ['required','date_format:d/m/Y'],
            'tgl_mulai' => ['required','date_format:d/m/Y'],
            'tgl_akhir' => ['required','date_format:d/m/Y'],
            'gaji' => ['required', 'numeric'],
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
            'jabatan.required' => 'nama jabatan harus dipilih',
            'no_surat.required' => 'nomor surat kontrak harus diisi',
            'pejabat_penetap.required' => 'pejabat penetap harus diisi',
            'tgl_surat.required' => 'tanggal surat kontrak harus diisi',
            'tgl_surat.date_format' => 'tanggal akhir tidak sesuai format (d/m/Y)',
            'tgl_mulai.required' => 'tanggal mulai kontrak harus diisi',
            'tgl_mulai.date_format' => 'tanggal akhir tidak sesuai format (d/m/Y)',
            'tgl_akhir.required' => 'tanggal akhir kontrak harus diisi',
            'tgl_akhir.date_format' => 'tanggal akhir tidak sesuai format (d/m/Y)',
            'gaji.required' => 'gaji harus diisi',
            'gaji.numeric' => 'gaji hanya boleh diisi angka',
            'file.required' => 'file SK harus diupload',
            'file.mimes' => 'format file SK harus pdf',
            'file.max' => 'file yang diupload maksimal 1 MB'
        ];
    }
}
