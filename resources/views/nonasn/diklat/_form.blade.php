@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="jenis-diklat" class="font-weight-bold">Jenjang Pendidikan</label>
        <select class="form-control form-control-sm @error('jenis_diklat') is-invalid @enderror" name="jenis_diklat">
            <option value="0" selected disabled>- pilih jenis diklat -</option>
            @foreach ($jenis_diklat as $id => $item)
                <option value="{{ $id }}" {{ old('jenis_diklat') == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('jenis_diklat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama-diklat" class="font-weight-bold">Nama Diklat</label>
        <input
            name="nama_diklat"
            id="nama-diklat"
            type="text"
            class="form-control form-control-sm @error('nama_diklat') is-invalid @enderror"
            value="{{ old('nama_diklat') }}"
        >
        @error('nama_diklat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nomor-sertifikat" class="font-weight-bold">Nomor Sertifikat</label>
        <input
            name="no_sertifikat"
            id="nomor-sertifikat"
            type="text"
            class="form-control form-control-sm @error('no_sertifikat') is-invalid @enderror"
            value="{{ old('no_sertifikat') }}"
        >
        @error('no_sertifikat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-sertifikat" class="font-weight-bold">Tanggal Sertifikat</label>
        <input
            name="tgl_sertifikat"
            id="tgl-sertifikat"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_sertifikat') is-invalid @enderror"
            value="{{ old('tgl_sertifikat') }}"
        >
        @error('tgl_sertifikat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-mulai" class="font-weight-bold">Tanggal Mulai</label>
        <input
            name="tgl_mulai"
            id="tgl-mulai"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_mulai') is-invalid @enderror"
            value="{{ old('tgl_mulai') }}"
        >
        @error('tgl_mulai')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-selesai" class="font-weight-bold">Tanggal Selesai</label>
        <input
            name="tgl_selesai"
            id="tgl-selesai"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_selesai') is-invalid @enderror"
            value="{{ old('tgl_selesai') }}"
        >
        @error('tgl_selesai')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="penyelenggara" class="font-weight-bold">Penyelenggara</label>
        <input
            name="penyelenggara"
            id="penyelenggara"
            type="text"
            class="form-control form-control-sm @error('penyelenggara') is-invalid @enderror"
            value="{{ old('penyelenggara') }}"
        >
        @error('penyelenggara')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="jml-jam" class="font-weight-bold">Jumlah Jam</label>
        <input
            name="jml_jam"
            id="jml-jam"
            type="text"
            class="form-control form-control-sm @error('jml_jam') is-invalid @enderror"
            value="{{ old('jml_jam') }}"
        >
        @error('jml_jam')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file" id="file" accept="application/pdf" type="file" class="form-control-file @error('file') is-invalid @enderror">
        @error('file')
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
        href="{{ route('nonasn.diklat') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@else
<div>
    <div class="position-relative form-group">
        <label for="jenis-diklat" class="font-weight-bold">Jenis Diklat</label>
        <select class="form-control form-control-sm @error('jenis_diklat') is-invalid @enderror" name="jenis_diklat">
            <option value="0" selected disabled>- pilih jenis diklat -</option>
            @foreach ($jenis_diklat as $id => $item)
                <option value="{{ $id }}" {{ $data->jenis_diklat_id == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('jenis_diklat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama-diklat" class="font-weight-bold">Nama Diklat</label>
        <input
            name="nama_diklat"
            id="nama-diklat"
            type="text"
            class="form-control form-control-sm @error('nama_diklat') is-invalid @enderror"
            value="{{ $data->nama_diklat }}"
        >
        @error('nama_diklat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nomor-sertifikat" class="font-weight-bold">Nomor Sertifikat</label>
        <input
            name="no_sertifikat"
            id="nomor-sertifikat"
            type="text"
            class="form-control form-control-sm @error('nomor_sertifikat') is-invalid @enderror"
            value="{{ $data->no_sertifikat }}"
        >
        @error('nomor_sertifikat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-sertifikat" class="font-weight-bold">Tanggal Sertifikat</label>
        <input
            name="tgl_sertifikat"
            id="tgl-sertifikat"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_sertifikat') is-invalid @enderror"
            value="{{ $data->tgl_sertifikat }}"
        >
        @error('tgl_sertifikat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-mulai" class="font-weight-bold">Tanggal Mulai</label>
        <input
            name="tgl_mulai"
            id="tgl-mulai"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_mulai') is-invalid @enderror"
            value="{{ $data->tgl_mulai }}"
        >
        @error('tgl_mulai')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-selesai" class="font-weight-bold">Tanggal Selesai</label>
        <input
            name="tgl_selesai"
            id="tgl-selesai"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_selesai') is-invalid @enderror"
            value="{{ $data->tgl_selesai }}"
        >
        @error('tgl_selesai')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="penyelenggara" class="font-weight-bold">Penyelenggara</label>
        <input
            name="penyelenggara"
            id="penyelenggara"
            type="text"
            class="form-control form-control-sm @error('penyelenggara') is-invalid @enderror"
            value="{{ $data->penyelenggara }}"
        >
        @error('penyelenggara')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="jml-jam" class="font-weight-bold">Jumlah Jam</label>
        <input
            name="jml_jam"
            id="jml-jam"
            type="text"
            class="form-control form-control-sm @error('jml_jam') is-invalid @enderror"
            value="{{ $data->jml_jam }}"
        >
        @error('jml_jam')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file" id="file" accept="application/pdf" type="file" class="form-control-file @error('file') is-invalid @enderror">
        @error('file')
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
                            data-target="#collapseOne2"
                            aria-expanded="false"
                            aria-controls="collapseTwo"
                            class="text-left m-0 p-0 btn btn-link btn-block"
                        >
                            <h5 class="m-0 p-0">Lihat Dokumen</h5>
                        </button>
                    </div>
                    <div data-parent="#accordion" id="collapseOne2" class="collapse">
                        <div class="card-body">
                            <iframe
                                src="{{ route('nonasn.diklat.file', ['file' => $data->file]) }}"
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
        href="{{ route('nonasn.diklat') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>

</div>
@endif