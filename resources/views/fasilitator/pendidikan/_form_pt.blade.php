@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="jenjang-pt" class="font-weight-bold">Jenjang Pendidikan</label>
        <select class="form-control form-control-sm @error('jenjang_pt') is-invalid @enderror" name="jenjang_pt">
            <option value="0" selected disabled>- pilih jenjang pendidikan -</option>
            @foreach ($jenjang as $id => $item)
                <option value="{{ $id }}" {{ old('jenjang_pt') == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('jenjang_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama-pt" class="font-weight-bold">Nama Perguruan Tinggi</label>
        <input name="nama_pt" id="nama-pt" type="text" class="form-control form-control-sm @error('nama_pt') is-invalid @enderror" value="{{ old('nama_pt') }}">
        @error('nama_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="fakultas" class="font-weight-bold">Fakultas</label>
        <input
            name="fakultas_pt"
            id="fakultas"
            type="text"
            class="form-control form-control-sm @error('fakultas_pt') is-invalid @enderror"
            value="{{ old('fakultas_pt') }}"
        >
        @error('fakultas_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="jurusan-pt" class="font-weight-bold">Jurusan</label>
        <input
            name="jurusan_prodi_pt"
            id="jurusan-pt"
            type="text"
            class="form-control form-control-sm @error('jurusan_prodi_pt') is-invalid @enderror"
            value="{{ old('jurusan_prodi_pt') }}"
        >
        @error('jurusan_prodi_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="kelas" class="font-weight-bold">Akreditasi</label>
        <select class="form-control form-control-sm @error('akreditasi_pt') is-invalid @enderror" name="akreditasi_pt">
                <option value="" selected disabled>- pilih akreditasi -</option>
            @foreach ($akreditasi as $id => $item)
                <option value="{{ $id }}" {{ old('akreditasi_pt') == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('akreditasi_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="thn-lulus" class="font-weight-bold">Tahun Lulus</label>
        <input
            name="thn_lulus_pt"
            id="thn-lulus"
            data-toggle="datepicker-year"
            type="text"
            class="form-control form-control-sm @error('thn_lulus_pt') is-invalid @enderror"
            value="{{ old('thn_lulus_pt') }}"
        >
        @error('thn_lulus_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no-ijazah-pt" class="font-weight-bold">Nomor Ijazah</label>
        <input
            name="no_ijazah_pt"
            id="no-ijazah-pt"
            type="text"
            class="form-control form-control-sm @error('no_ijazah_pt') is-invalid @enderror"
            value="{{ old('no_ijazah_pt') }}"
        >
        @error('no_ijazah_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-ijazah-pt" class="font-weight-bold">Tanggal Ijazah</label>
        <input
            name="tgl_ijazah_pt"
            id="tgl-ijazah-pt"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_ijazah_pt') is-invalid @enderror"
            value="{{ old('tgl_ijazah_pt') }}"
        >
        @error('tgl_ijazah_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="ipk" class="font-weight-bold">IPK <small class="text-primary"> jika terdapat koma, isi dengan tanda baca "(.)"; Contoh: 90.3</small></label>
        <input
            name="ipk_pt"
            id="ipk"
            type="text"
            class="form-control form-control-sm @error('ipk_pt') is-invalid @enderror"
            value="{{ old('ipk_pt') }}"
        >
        @error('ipk_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file-ijazah-pt" class="font-weight-bold">Dokumen Ijazah <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_ijazah_pt" accept="application/pdf" id="file-ijazah-pt" type="file" class="form-control-file @error('file_ijazah_pt') is-invalid @enderror">
        @error('file_ijazah_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file-nilai-pt" class="font-weight-bold">Dokumen Transkrip Nilai <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_nilai_pt" accept="application/pdf" id="file-nilai-pt" type="file" class="form-control-file @error('file_nilai_pt') is-invalid @enderror">
        @error('file_nilai_pt')
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
        href="{{ route('fasilitator.pendidikan-sma', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@else
<div>
    <div class="position-relative form-group">
        <label for="jenjang-pt" class="font-weight-bold">Jenjang Pendidikan</label>
        <select class="form-control form-control-sm @error('jenjang_pt') is-invalid @enderror" name="jenjang_pt">
            @foreach ($jenjang as $id => $item)
                <option value="{{ $id }}" {{ $data->id_jenjang == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('jenjang_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama-pt" class="font-weight-bold">Nama Perguruan Tinggi</label>
        <input name="nama_pt" id="nama-pt" type="text" class="form-control form-control-sm @error('nama_pt') is-invalid @enderror" value="{{ $data->nama_pt }}">
        @error('nama_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="fakultas" class="font-weight-bold">Fakultas</label>
        <input
            name="fakultas_pt"
            id="fakultas"
            type="text"
            class="form-control form-control-sm @error('fakultas_pt') is-invalid @enderror"
            value="{{ $data->fakultas_pt }}"
        >
        @error('fakultas_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="jurusan-pt" class="font-weight-bold">Jurusan</label>
        <input
            name="jurusan_prodi_pt"
            id="jurusan-pt"
            type="text"
            class="form-control form-control-sm @error('jurusan_prodi_pt') is-invalid @enderror"
            value="{{ $data->jurusan_prodi_pt }}"
        >
        @error('jurusan_prodi_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="kelas" class="font-weight-bold">Akreditasi</label>
        <select class="form-control form-control-sm @error('akreditasi_pt') is-invalid @enderror" name="akreditasi_pt">
            @foreach ($akreditasi as $id => $item)
                <option value="{{ $item }}" {{ $data->akreditasi == $item ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('akreditasi_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="thn-lulus" class="font-weight-bold">Tahun Lulus</label>
        <input
            name="thn_lulus_pt"
            id="thn-lulus"
            data-toggle="datepicker-year"
            type="text"
            class="form-control form-control-sm @error('thn_lulus_pt') is-invalid @enderror"
            value="{{ $data->thn_lulus_pt }}"
        >
        @error('thn_lulus_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no-ijazah-pt" class="font-weight-bold">Nomor Ijazah</label>
        <input
            name="no_ijazah_pt"
            id="no-ijazah-pt"
            type="text"
            class="form-control form-control-sm @error('no_ijazah_pt') is-invalid @enderror"
            value="{{ $data->no_ijazah_pt }}"
        >
        @error('no_ijazah_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-ijazah-pt" class="font-weight-bold">Tanggal Ijazah</label>
        <input
            name="tgl_ijazah_pt"
            id="tgl-ijazah-pt"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_ijazah_pt') is-invalid @enderror"
            value="{{ $data->tgl_ijazah_pt }}"
        >
        @error('tgl_ijazah_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="ipk" class="font-weight-bold">IPK <small class="text-primary"> jika terdapat koma, isi dengan tanda baca "(.)"; Contoh: 90.3</small></label>
        <input
            name="ipk_pt"
            id="ipk"
            type="text"
            class="form-control form-control-sm @error('ipk_pt') is-invalid @enderror"
            value="{{ $data->ipk_pt }}"
        >
        @error('ipk_pt')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file-ijazah-pt" class="font-weight-bold">Dokumen Ijazah <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_ijazah_pt" accept="application/pdf" id="file-ijazah-pt" type="file" class="form-control-file @error('file_ijazah_pt') is-invalid @enderror">
        @error('file_ijazah_pt')
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
                                src="{{ route('pendidikan.file-ijazah-pt', ['file' => $data->file_ijazah_pt]) }}"
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
        <label for="file-nilai-pt" class="font-weight-bold">Dokumen Transkrip Nilai <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_nilai_pt" accept="application/pdf" id="file-nilai-pt" type="file" class="form-control-file @error('file_nilai_pt') is-invalid @enderror">
        @error('file_nilai_pt')
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
                                src="{{ route('pendidikan.file-transkrip-pt', ['file' => $data->file_nilai_pt]) }}"
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
        href="{{ route('fasilitator.pendidikan-sma', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@endif