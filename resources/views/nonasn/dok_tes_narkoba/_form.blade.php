@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="tahun" class="font-weight-bold">Tahun Tes</label>
        <input
            name="tahun"
            id="tahun"
            data-toggle="datepicker-year"
            type="text"
            class="form-control form-control-sm @error('tahun') is-invalid @enderror"
            value="{{ old('tahun') }}"
        >
        @error('tahun')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nomor-surat" class="font-weight-bold">Nomor Surat</label>
        <input
            name="nomor_surat"
            id="nomor-surat"
            type="text"
            class="form-control form-control-sm @error('nomor_surat') is-invalid @enderror"
            value="{{ old('nomor_surat') }}"
        >
        @error('nomor_surat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-surat" class="font-weight-bold">Tanggal Surat</label>
        <input
            name="tgl_surat"
            id="tgl-surat"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_surat') is-invalid @enderror"
            value="{{ old('tgl_surat') }}"
        >
        @error('tgl_surat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="dokter-pemeriksa" class="font-weight-bold">Dokter Pemeriksa</label>
        <input
            name="dokter_pemeriksa"
            id="dokter-pemeriksa"
            type="text"
            class="form-control form-control-sm @error('dokter_pemeriksa') is-invalid @enderror"
            value="{{ old('dokter_pemeriksa') }}"
        >
        @error('dokter_pemeriksa')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="instansi" class="font-weight-bold">Instansi/Lembaga Pemeriksa</label>
        <input
            name="instansi"
            id="instansi"
            type="text"
            class="form-control form-control-sm @error('instansi') is-invalid @enderror"
            value="{{ old('instansi') }}"
        >
        @error('instansi')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen Tes Narkoba <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
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
        href="{{ route('nonasn.dok-narkoba') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@else
<div>
    <div class="position-relative form-group">
        <label for="tahun" class="font-weight-bold">Tahun Tes</label>
        <input
            name="tahun"
            id="tahun"
            data-toggle="datepicker-year"
            type="text"
            class="form-control form-control-sm @error('tahun') is-invalid @enderror"
            value="{{ $data->tahun }}"
        >
        @error('tahun')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nomor-surat" class="font-weight-bold">Nomor Surat</label>
        <input
            name="nomor_surat"
            id="nomor-surat"
            type="text"
            class="form-control form-control-sm @error('nomor_surat') is-invalid @enderror"
            value="{{ $data->nomor_surat }}"
        >
        @error('nomor_surat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-surat" class="font-weight-bold">Tanggal Surat</label>
        <input
            name="tgl_surat"
            id="tgl-surat"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_surat') is-invalid @enderror"
            value="{{ $data->tgl_surat }}"
        >
        @error('tgl_surat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="dokter-pemeriksa" class="font-weight-bold">Dokter Pemeriksa</label>
        <input
            name="dokter_pemeriksa"
            id="dokter-pemeriksa"
            type="text"
            class="form-control form-control-sm @error('dokter_pemeriksa') is-invalid @enderror"
            value="{{ $data->dokter_pemeriksa }}"
        >
        @error('dokter_pemeriksa')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="instansi" class="font-weight-bold">Instansi/Lembaga Pemeriksa</label>
        <input
            name="instansi"
            id="instansi"
            type="text"
            class="form-control form-control-sm @error('instansi') is-invalid @enderror"
            value="{{ $data->instansi }}"
        >
        @error('instansi')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen Tes Narkoba <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
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
                                src="{{ route('nonasn.dok-narkoba.file', ['file' => $data->file]) }}"
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
        href="{{ route('nonasn.dok-narkoba') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>

</div>
@endif