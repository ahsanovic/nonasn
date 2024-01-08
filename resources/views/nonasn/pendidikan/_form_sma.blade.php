@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="jenjang-sma" class="font-weight-bold">Jenjang Pendidikan</label>
        <select class="form-control form-control-sm @error('jenjang_sma') is-invalid @enderror"" name="jenjang_sma">
            <option value="0" selected disabled>- pilih jenjang pendidikan -</option>
            @foreach ($jenjang as $id => $item)
                <option value="{{ $id }}" {{ old('jenjang_sma') == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('jenjang_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama-sekolah" class="font-weight-bold">Nama Sekolah</label>
        <input name="nama_sekolah" id="nama-sekolah" type="text" class="form-control form-control-sm @error('nama_sekolah') is-invalid @enderror" value="{{ old('nama_sekolah') }}">
        @error('nama_sekolah')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="jurusan-sma" class="font-weight-bold">Jurusan</label>
        <input
            name="jurusan_sma"
            id="jurusan-sma"
            type="text"
            class="form-control form-control-sm @error('jurusan_sma') is-invalid @enderror"
            value="{{ old('jurusan_sma') }}"
        >
        @error('jurusan_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="kelas" class="font-weight-bold">Akreditasi</label>
        <select class="form-control form-control-sm @error('akreditasi_sma') is-invalid @enderror" name="akreditasi_sma">
                <option value="" selected disabled>- pilih akreditasi -</option>
            @foreach ($akreditasi as $id => $item)
                <option value="{{ $item }}" {{ old('akreditasi_sma') == $item ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('akreditasi_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="thn-lulus" class="font-weight-bold">Tahun Lulus</label>
        <input
            name="thn_lulus_sma"
            id="thn-lulus"
            data-toggle="datepicker-year"
            type="text"
            class="form-control form-control-sm @error('thn_lulus_sma') is-invalid @enderror"
            value="{{ old('thn_lulus_sma') }}"
        >
        @error('thn_lulus_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no-ijazah-sma" class="font-weight-bold">Nomor Ijazah</label>
        <input
            name="no_ijazah_sma"
            id="no-ijazah-sma"
            type="text"
            class="form-control form-control-sm @error('no_ijazah_sma') is-invalid @enderror"
            value="{{ old('no_ijazah_sma') }}"
        >
        @error('no_ijazah_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-ijazah-sma" class="font-weight-bold">Tanggal Ijazah</label>
        <input
            name="tgl_ijazah_sma"
            id="tgl-ijazah-sma"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_ijazah_sma') is-invalid @enderror"
            value="{{ old('tgl_ijazah_sma') }}"
        >
        @error('tgl_ijazah_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nilai-akhir" class="font-weight-bold">Nilai Akhir</label>
        <input
            name="nilai_akhir_sma"
            id="nilai-akhir"
            type="text"
            class="form-control form-control-sm @error('nilai_akhir_sma') is-invalid @enderror"
            value="{{ old('nilai_akhir_sma') }}"
        >
        @error('nilai_akhir_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nilai-un" class="font-weight-bold">Nilai Ujian Nasional</label>
        <input
            name="nilai_un_sma"
            id="nilai-un"
            type="text"
            class="form-control form-control-sm @error('nilai_un_sma') is-invalid @enderror"
            value="{{ old('nilai_un_sma') }}"
        >
        @error('nilai_un_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file-ijazah" class="font-weight-bold">Dokumen Ijazah <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_ijazah_sma" id="file-ijazah" accept="application/pdf" type="file" class="form-control-file @error('file_ijazah_sma') is-invalid @enderror">
        @error('file_ijazah_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file-nilai" class="font-weight-bold">Dokumen Transkrip Nilai <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_nilai_sma" id="file-nilai" accept="application/pdf" type="file" class="form-control-file @error('file_nilai_sma') is-invalid @enderror">
        @error('file_nilai_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    
    <x-button-loader />
    <button
        class="mt-3 btn btn-success btn-sm btn-square btn-hover-shine"
        id="btn-submit"
        type="submit"
    >
        {{ $submit }}
    </button>
    <a
        href="{{ route('nonasn.pendidikan-sma') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@else
<div>
    <div class="position-relative form-group">
        <label for="jenjang-sma" class="font-weight-bold">Jenjang Pendidikan</label>
        <select class="form-control form-control-sm @error('jenjang_sma') is-invalid @enderror"" name="jenjang_sma">
            @foreach ($jenjang as $id => $item)
                <option value="{{ $id }}" {{ $data->id_jenjang == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('jenjang_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama-sekolah" class="font-weight-bold">Nama Sekolah</label>
        <input
            name="nama_sekolah"
            id="nama-sekolah"
            type="text"
            class="form-control form-control-sm @error('nama_sekolah') is-invalid @enderror"
            value="{{ $data->nama_sekolah_sma }}"
        >
        @error('nama_sekolah')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="jurusan-sma" class="font-weight-bold">Jurusan</label>
        <input
            name="jurusan_sma"
            id="jurusan-sma"
            type="text"
            class="form-control form-control-sm @error('jurusan_sma') is-invalid @enderror"
            value="{{ $data->jurusan_sma }}"
        >
        @error('jurusan_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="kelas" class="font-weight-bold">Akreditasi</label>
        <select class="form-control form-control-sm @error('akreditasi_sma') is-invalid @enderror" name="akreditasi_sma">
            @foreach ($akreditasi as $id => $item)
                <option value="{{ $item }}" {{ $data->akreditasi_sma == $item ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('akreditasi_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="thn-lulus" class="font-weight-bold">Tahun Lulus</label>
        <input
            name="thn_lulus_sma"
            id="thn-lulus"
            data-toggle="datepicker-year"
            type="text"
            class="form-control form-control-sm @error('thn_lulus_sma') is-invalid @enderror"
            value="{{ $data->thn_lulus_sma }}"
        >
        @error('thn_lulus_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no-ijazah-sma" class="font-weight-bold">Nomor Ijazah</label>
        <input
            name="no_ijazah_sma"
            id="no-ijazah-sma"
            type="text"
            class="form-control form-control-sm @error('no_ijazah_sma') is-invalid @enderror"
            value="{{ $data->no_ijazah_sma }}"
        >
        @error('no_ijazah_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-ijazah-sma" class="font-weight-bold">Tanggal Ijazah</label>
        <input
            name="tgl_ijazah_sma"
            id="tgl-ijazah-sma"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_ijazah_sma') is-invalid @enderror"
            value="{{ $data->tgl_ijazah_sma }}"
        >
        @error('tgl_ijazah_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nilai-akhir" class="font-weight-bold">Nilai Akhir</label>
        <input
            name="nilai_akhir_sma"
            id="nilai-akhir"
            type="text"
            class="form-control form-control-sm @error('nilai_akhir_sma') is-invalid @enderror"
            value="{{ $data->nilai_akhir_sma }}"
        >
        @error('nilai_akhir_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nilai-un" class="font-weight-bold">Nilai Ujian Nasional</label>
        <input
            name="nilai_un_sma"
            id="nilai-un"
            type="text"
            class="form-control form-control-sm @error('nilai_un_sma') is-invalid @enderror"
            value="{{ $data->nilai_un_sma }}"
        >
        @error('nilai_un_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file-ijazah" class="font-weight-bold">Dokumen Ijazah <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_ijazah_sma" id="file-ijazah" accept="application/pdf" type="file" class="form-control-file @error('file_ijazah_sma') is-invalid @enderror">
        @error('file_ijazah_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="accordion" class="accordion-wrapper mb-3">
                <div class="card">
                    <div id="headingTwo" class="b-radius-0 card-header">
                        <button
                            type="button"
                            data-toggle="collapse"
                            data-target="#collapseOne"
                            aria-expanded="false"
                            aria-controls="collapseTwo"
                            class="text-left m-0 p-0 btn btn-link btn-block"
                        >
                            <h5 class="m-0 p-0">Lihat Dokumen Ijazah</h5>
                        </button>
                    </div>
                    <div data-parent="#accordion" id="collapseOne" class="collapse">
                        <div class="card-body">
                            <iframe
                                src="{{ route('nonasn.pendidikan.file-ijazah-sma', ['file' => $data->file_ijazah_sma]) }}"
                                align="top"
                                height="800"
                                width="100%"
                                frameborder="0"
                                scrolling="auto">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="position-relative form-group">
        <label for="file-nilai" class="font-weight-bold">Dokumen Transkrip Nilai <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_nilai_sma" id="file-nilai" accept="application/pdf" type="file" class="form-control-file @error('file_nilai_sma') is-invalid @enderror">
        @error('file_nilai_sma')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="accordion2" class="accordion-wrapper mb-3">
                <div class="card">
                    <div id="headingTwo" class="b-radius-0 card-header">
                        <button
                            type="button"
                            data-toggle="collapse"
                            data-target="#collapseOne2"
                            aria-expanded="false"
                            aria-controls="collapseTwo"
                            class="text-left m-0 p-0 btn btn-link btn-block"
                        >
                            <h5 class="m-0 p-0">Lihat Dokumen Transkrip</h5>
                        </button>
                    </div>
                    <div data-parent="#accordion2" id="collapseOne2" class="collapse">
                        <div class="card-body">
                            <iframe
                                src="{{ route('nonasn.pendidikan.file-transkrip-sma', ['file' => $data->file_nilai_sma]) }}"
                                align="top"
                                height="800"
                                width="100%"
                                frameborder="0"
                                scrolling="auto">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <x-button-loader />
    <button
        class="mt-3 btn btn-success btn-sm btn-square btn-hover-shine"
        id="btn-submit"
        type="submit"
    >
        {{ $submit }}
    </button>
    <a
        href="{{ route('nonasn.pendidikan-sma') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@endif