<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendidikanPtRequest extends FormRequest
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
            'jenjang_pt' => ['required'],
            'nama_pt' => ['required'],
            'fakultas_pt' => ['required'],
            'jurusan_prodi_pt' => ['required'],
            'akreditasi_pt' => ['required'],
            'thn_lulus_pt' => ['required','numeric'],
            'no_ijazah_pt' => ['required'],
            'tgl_ijazah_pt' => ['required','date_format:d/m/Y'],
            'ipk_pt' => ['required', 'gte:0'],
            'file_ijazah_pt' => ['required','file','mimes:pdf','max:1024'],
            'file_nilai_pt' => ['required','file','mimes:pdf','max:1024']
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['file_ijazah_pt'] = ['file','mimes:pdf','max:1024'];
            $rules['file_nilai_pt'] = ['file','mimes:pdf','max:1024'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'jenjang_pt.required' => 'jenjang pendidikan harus dipilih',
            'nama_pt.required' => 'nama perguruan tinggi harus diisi',
            'fakultas_pt.required' => 'fakultas harus diisi',
            'jurusan_prodi_pt.required' => 'jurusan harus diisi',
            'akreditasi_pt.required' => 'akreditasi harus dipilih',
            'thn_lulus_pt.required' => 'tahun lulus harus diisi',
            'thn_lulus_pt.numeric' => 'tahun lulus hanya boleh diisi angka',
            'no_ijazah_pt.required' => 'nomor ijazah harus diisi',
            'tgl_ijazah_pt.required' => 'tanggal ijazah harus diisi',
            'tgl_ijazah_pt.date_format' => 'format tanggal tidak sesuai',
            'ipk_pt.required' => 'ipk harus diisi',
            'ipk_pt.gte' => 'ipk minimal 0',
            'file_ijazah_pt.required' => 'dokumen harus diupload',
            'file_ijazah_pt.mimes' => 'format dokumen harus pdf',
            'file_ijazah_pt.max' => 'file yang diupload maksimal 1 MB',
            'file_nilai_pt.required' => 'dokumen harus diupload',
            'file_nilai_pt.mimes' => 'format dokumen harus pdf',
            'file_nilai_pt.max' => 'file yang diupload maksimal 1 MB'
        ];
    }
}
