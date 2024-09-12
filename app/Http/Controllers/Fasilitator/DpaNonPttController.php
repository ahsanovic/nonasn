<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Dpa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Skpd;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DpaNonPttController extends Controller
{  
    public function index(Request $request)
    {
        if (auth()->user()->id_skpd == 1 && auth()->user()->level == 'admin') {
            $tahun = [2022, 2023, 2024];
            $opd = Skpd::where(function($query) {
                    $query->whereRaw('LENGTH(id) = ?', [3])
                            ->orWhere('name', 'like', 'BIRO%');
                    })   
                    ->where('id', '!=', '101')
                    ->with(['dpa' => function($query) use ($tahun) {
                        $query->whereIn('tahun', $tahun)
                                ->select('id', 'id_skpd', 'tahun', 'file_dpa', 'data_dpa');
                    }])
                    ->get();

            $data_2022 = $opd->map(function($item) {
                return $item->dpa->where('tahun', 2022);
            });

            $data_2023 = $opd->map(function($item) {
                return $item->dpa->where('tahun', 2023);
            });

            $data_2024 = $opd->map(function($item) {
                return $item->dpa->where('tahun', 2024);
            });

            return view('fasilitator.dpa_non_ptt.index', compact(
                'opd',
                'data_2022',
                'data_2023',
                'data_2024'
            ));
        } else {
            $fetch_data_2022 = Dpa::whereId_skpd(auth()->user()->id_skpd)->whereTahun(2022)->first(['id', 'file_dpa', 'data_dpa']);
            $data_2022 = $fetch_data_2022 ? json_decode($fetch_data_2022->data_dpa, true) : null;
            $count_jml_pegawai_2022 = $data_2022 ? array_sum(array_column($data_2022, 'jml_pegawai')) : null;

            $fetch_data_2023 = Dpa::whereId_skpd(auth()->user()->id_skpd)->whereTahun(2023)->first(['id', 'file_dpa', 'data_dpa']);
            $data_2023 = $fetch_data_2023 ? json_decode($fetch_data_2023->data_dpa, true) : null;
            $count_jml_pegawai_2023 = $data_2023 ? array_sum(array_column($data_2023, 'jml_pegawai')) : null;

            $fetch_data_2024 = Dpa::whereId_skpd(auth()->user()->id_skpd)->whereTahun(2024)->first(['id', 'file_dpa', 'data_dpa']);
            $data_2024 = $fetch_data_2024 ? json_decode($fetch_data_2024->data_dpa, true) : null;
            $count_jml_pegawai_2024 = $data_2024 ? array_sum(array_column($data_2024, 'jml_pegawai')) : null;

            return view('fasilitator.dpa_non_ptt.index', compact(
                'data_2022',
                'fetch_data_2022',
                'count_jml_pegawai_2022',
                'data_2023',
                'fetch_data_2023',
                'count_jml_pegawai_2023',
                'data_2024',
                'fetch_data_2024',
                'count_jml_pegawai_2024',
            ));
        }
    }

    public function create(Request $request)
    {
        $submit = 'Simpan';
        return view('fasilitator.dpa_non_ptt.create', compact('submit'));
    }

    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_dpa/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    private function _uploadFile($file)
    {
        $filenameWithExt = $file->getClientOriginalName();
        // Get only filename without extension
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get extension
        $extension = $file->getClientOriginalExtension();
        // Give a new name
        $time = date('YmdHis', time());
        $filenameToStore = $time . '-' . uniqid() . '.' . $extension;
        // Upload file
        Storage::disk('local')->put('/upload_dpa/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function store(Request $request)
    {
        $data = Dpa::whereId_skpd(auth()->user()->id_skpd)->whereTahun($request->tahun)->first(['tahun']);
        if ($data) return back()->with(["type" => "error", "message" => "data tahun " . $request->tahun . " sudah ada!"]);

        $validated_static_form = $request->validate([
            'tahun' => 'required|numeric',
            'file_dpa' => 'required|file|mimes:pdf|max:1024',
        ], [
            'tahun.required' => 'tahun harus diisi',
            'file_dpa.required' => 'dokumen harus diupload',
            'file_dpa.mimes' => 'format dokumen harus pdf',
            'file_dpa.max' => 'ukuran dokumen maksimal 1 MB',
        ]);

        $validated_dynamic_form = $request->validate([
            'dpa.*.kode_rekening' => 'required|string',
            'dpa.*.jml_pegawai' => 'required|numeric',
        ]);

        Dpa::create([
            'id_skpd' => auth()->user()->id_skpd,
            'tahun' => $validated_static_form['tahun'],
            'file_dpa' => $request->hasFile('file_dpa') ? $this->_uploadFile($request->file('file_dpa')) : null,
            'data_dpa' => json_encode($validated_dynamic_form['dpa']),
        ]);

        logDpaFasilitator(auth()->user()->username, auth()->user()->id_skpd, 'dpa', 'input');

        return redirect()->route('fasilitator.dpanonptt')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
    }

    public function edit($id)
    {
        $submit = 'Update';
        $data = Dpa::findOrFail($id);
        $dpa = json_decode($data->data_dpa, true);
        $count_index_array = count($dpa);

        return view('fasilitator.dpa_non_ptt.edit', compact('submit', 'data', 'dpa', 'count_index_array'));
    }

    public function update(Request $request, $id)
    {
        try {
            $data = Dpa::whereId($id)->first();

            $existing_data_dpa = json_decode($data->data_dpa, true);

            if ($request->hasFile('file_dpa')) {
                $file_dpa = $this->_uploadFile($request->file('file_dpa'));
                if (Storage::disk('local')->exists('/upload_dpa/' . $data->file_dpa) && $data->file_dpa != null) {
                    Storage::delete('upload_dpa/' . $data->file_dpa);
                } else {
                    $file_dpa = $file_dpa;
                }
            } else {
                $file_dpa = $data->file_dpa;
            }

            $validated_static_form = $request->validate([
                'tahun' => 'required|numeric',
                'file_dpa' =>  $request->hasFile('file_dpa') ? 'required|file|mimes:pdf|max:1024' : '',
            ], [
                'tahun.required' => 'tahun harus diisi',
                'file_dpa.required' => 'dokumen harus diupload',
                'file_dpa.mimes' => 'format dokumen harus pdf',
                'file_dpa.max' => 'ukuran dokumen maksimal 1 MB',
            ]);

            $validated_dynamic_form = $request->validate([
                'dpa.*.kode_rekening' => 'required|string',
                'dpa.*.jml_pegawai' => 'required|numeric',
            ]);

            // Loop melalui data yang divalidasi dari request
            foreach ($validated_dynamic_form['dpa'] as $index => $new_item) {
                $found = false;
                // Cek apakah sudah ada di array existing_data_dpa
                foreach ($existing_data_dpa as $key => &$item) {
                    if ($index == $key) {
                        // Update data yang sesuai dengan index array
                        $item['kode_rekening'] = $new_item['kode_rekening'];
                        $item['jml_pegawai'] = $new_item['jml_pegawai'];
                        $found = true;
                        break;
                    }
                }
                // Jika index tidak ditemukan, tambahkan data baru
                if (!$found) {
                    $existing_data_dpa[] = $new_item;
                }
            }

            $updated_data_dpa = json_encode($existing_data_dpa);

            if ($data) {
                $data->update([
                    'tahun' => $validated_static_form['tahun'],
                    'file_dpa' => $file_dpa,
                    'data_dpa' => $updated_data_dpa,
                ]);
            }

            logDpaFasilitator(auth()->user()->username, auth()->user()->id_skpd, 'dpa', 'update');

            return redirect()->route('fasilitator.dpanonptt')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $data = Dpa::whereId($id)->first();

            $existing_data_dpa = json_decode($data->data_dpa, true);
            $index_to_delete = $request->index;

            if (isset($existing_data_dpa[$index_to_delete])) {
                unset($existing_data_dpa[$index_to_delete]);
            }

            // re-index array setelah penghapusan
            $existing_data_dpa = array_values($existing_data_dpa);

            // encode kembali array menjadi json
            $updated_data_dpa = json_encode($existing_data_dpa);

            // update data di db
            $data->update([
                'data_dpa' => $updated_data_dpa
            ]);

            logDpaFasilitator(auth()->user()->username, auth()->user()->id_skpd, 'dpa', 'hapus');

            return redirect()->route('fasilitator.dpanonptt')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }

    }        
}
