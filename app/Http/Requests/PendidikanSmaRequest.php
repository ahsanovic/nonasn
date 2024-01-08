<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendidikanSmaRequest extends FormRequest
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
            'jenjang_sma' => ['required'],
            'nama_sekolah' => ['required'],
            'akreditasi_sma' => ['required'],
            'thn_lulus_sma' => ['required','numeric'],
            'no_ijazah_sma' => ['required'],
            'tgl_ijazah_sma' => ['required','date_format:d/m/Y'],
            'nilai_akhir_sma' => ['required', 'gte:0'],
            'nilai_un_sma' => ['required', 'gte:0'],
            'file_ijazah_sma' => ['required','file','mimes:pdf','max:1024'],
            'file_nilai_sma' => ['required','file','mimes:pdf','max:1024'],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['file_ijazah_sma'] = ['file','mimes:pdf','max:1024'];
            $rules['file_nilai_sma'] = ['file','mimes:pdf','max:1024'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'jenjang_sma.required' => 'jenjang pendidikan harus dipilih',
            'nama_sekolah.required' => 'nama sekolah harus diisi',
            'akreditasi_sma.required' => 'akreditasi harus dipilih',
            'thn_lulus_sma.required' => 'tahun lulus harus diisi',
            'thn_lulus_sma.numeric' => 'tahun lulus hanya boleh diisi angka',
            'no_ijazah_sma.required' => 'nomor ijazah harus diisi',
            'tgl_ijazah_sma.required' => 'tanggal ijazah harus diisi',
            'tgl_ijazah_sma.date_format' => 'format tanggal tidak sesuai',
            'nilai_akhir_sma.required' => 'nilai harus diisi',
            'nilai_akhir_sma.gte' => 'nilai minimal 0',
            'nilai_un_sma.required' => 'nilai harus diisi',
            'nilai_un_sma.gte' => 'nilai minimal 0',
            'file_ijazah_sma.required' => 'dokumen harus diupload',
            'file_ijazah_sma.mimes' => 'format dokumen harus pdf',
            'file_ijazah_sma.max' => 'file yang diupload maksimal 1 MB',
            'file_nilai_sma.required' => 'dokumen harus diupload',
            'file_nilai_sma.mimes' => 'format dokumen harus pdf',
            'file_nilai_sma.max' => 'file yang diupload maksimal 1 MB',
        ];
    }
}
