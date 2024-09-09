@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="tahun" class="font-weight-bold">Tahun</label>
        <input
            name="tahun"
            id="tahun"
            data-toggle="datepicker-year-selection"
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
        <label for="file" class="font-weight-bold">Dokumen DPA <small class="text-primary">*) format file pdf</small></label>
        <input name="file_dpa" id="file" accept="application/pdf" type="file" class="form-control-file @error('file_dpa') is-invalid @enderror">
        @error('file_dpa')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div id="wrapper">
        <div class="dynamic-group">
            <div class="row">
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="kode-rekening" class="font-weight-bold">Kode Rekening</label>
                        <input
                            name="dpa[0][kode_rekening]"
                            id="kode-rekening"
                            type="text"
                            class="form-control form-control-sm"
                            value="{{ old('dpa[0][kode_rekening]') }}"
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="jml-pegawai" class="font-weight-bold">Jumlah Pegawai</label>
                        <input
                            name="dpa[0][jml_pegawai]"
                            id="jml-pegawai"
                            type="number"
                            class="form-control form-control-sm"
                            value="{{ old('jml_pegawai') }}"
                        >
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-center mt-2">
                    <button type="button" class="btn btn-sm btn-warning" id="add-another">Tambah Form</button>
                    <button type="button" class="btn btn-sm btn-danger remove-form" style="display: none;">Hapus Form</button>
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
        href="{{ route('fasilitator.dpanonptt') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@else
<div>
    <div class="position-relative form-group">
        <label for="tahun" class="font-weight-bold">Tahun</label>
        <input
            name="tahun"
            id="tahun"
            type="text"
            class="form-control form-control-sm @error('tahun') is-invalid @enderror"
            value="{{ $data->tahun }}"
            readonly
        >
        @error('tahun')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen DPA <small class="text-primary">*) format file pdf</small></label>
        <input name="file_dpa" id="file" accept="application/pdf" type="file" class="form-control-file @error('file_dpa') is-invalid @enderror">
        @error('file_dpa')
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
                                src="{{ route('fasilitator.dpanonptt.file', ['file' => $data->file_dpa]) }}"
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

    <div id="wrapper">
        @foreach ($dpa as $key => $item)
        <div class="dynamic-group">
            <div class="row">
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="kode-rekening" class="font-weight-bold">Kode Rekening</label>
                        <input
                            name="dpa[{{ $key }}][kode_rekening]"
                            id="kode-rekening"
                            type="text"
                            class="form-control form-control-sm"
                            value="{{ $item['kode_rekening'] }}"
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="jml-pegawai" class="font-weight-bold">Jumlah Pegawai</label>
                        <input
                            name="dpa[{{ $key }}][jml_pegawai]"
                            id="jml-pegawai"
                            type="number"
                            class="form-control form-control-sm"
                            value="{{ $item['jml_pegawai'] }}"
                        >
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-center mt-2">
                    @if ($key == (count($dpa) - 1))
                    <button type="button" class="btn btn-sm btn-warning" id="add-another">Tambah Form</button>
                    @endif
                    <button type="button" class="btn btn-sm btn-danger remove-form" style="display: none;">Hapus Form</button>
                </div>
            </div>
        </div>
        @endforeach
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
        href="{{ route('fasilitator.dpanonptt') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>

</div>
@endif