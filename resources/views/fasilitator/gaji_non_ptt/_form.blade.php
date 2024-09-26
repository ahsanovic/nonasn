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
        <label for="tmt-awal" class="font-weight-bold">Tanggal Mulai Kontrak</label>
        <input
            name="tmt_awal"
            id="tmt-awal"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tmt_awal') is-invalid @enderror"
            value="{{ old('tmt_awal') }}"
        >
        @error('tmt_awal')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tmt-akhir" class="font-weight-bold">Tanggal Akhir Kontrak</label>
        <input
            name="tmt_akhir"
            id="tmt-akhir"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tmt_akhir') is-invalid @enderror"
            value="{{ old('tmt_akhir') }}"
        >
        @error('tmt_akhir')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nominal-gaji" class="font-weight-bold">Nominal Gaji <small class="text-primary">*) masukkan hanya angka saja, cth: 2000000</small></label>
        <input
            name="nominal_gaji"
            id="nominal-gaji"
            type="text"
            class="form-control form-control-sm @error('nominal_gaji') is-invalid @enderror"
            value="{{ old('nominal_gaji') }}"
        >
        @error('nominal_gaji')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    {{-- <div class="position-relative form-group">
        <label for="file-gaji" class="font-weight-bold">File Gaji <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_gaji" id="file-gaji" accept="application/pdf" type="file" class="form-control-file @error('file_gaji') is-invalid @enderror">
        @error('file_gaji')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div> --}}
    <div class="position-relative form-group">
        <label for="link-gdrive" class="font-weight-bold">Link Google Drive <small class="text-danger">*) masukkan link google drive dokumen gaji anda</small></label>
        <input
            name="link_gdrive"
            id="link-gdrive"
            type="text"
            class="form-control form-control-sm @error('link_gdrive') is-invalid @enderror"
            value="{{ old('link_gdrive') }}"
        >
        @error('link_gdrive')
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
        href="{{ route('fasilitator.gajinonptt', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
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
        <label for="tmt-awal" class="font-weight-bold">Tanggal Mulai Kontrak</label>
        <input
            name="tmt_awal"
            id="tmt-awal"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tmt_awal') is-invalid @enderror"
            value="{{ $data->tmt_awal }}"
        >
        @error('tmt_awal')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tmt-akhir" class="font-weight-bold">Tanggal Akhir Kontrak</label>
        <input
            name="tmt_akhir"
            id="tmt-akhir"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tmt_akhir') is-invalid @enderror"
            value="{{ $data->tmt_akhir }}"
        >
        @error('tmt_akhir')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nominal-gaji" class="font-weight-bold">Nominal Gaji <small class="text-primary">*) masukkan hanya angka saja, cth: 2000000</small></label>
        <input
            name="nominal_gaji"
            id="nominal-gaji"
            type="text"
            class="form-control form-control-sm @error('nominal_gaji') is-invalid @enderror"
            value="{{ $data->nominal_gaji }}"
        >
        @error('nominal_gaji')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    @if ($data->link_gdrive)
    <div class="position-relative form-group">
        <label for="link-gdrive" class="font-weight-bold">Link Google Drive <small class="text-danger">*) masukkan link google drive dokumen gaji anda</small></label>
        <input
            name="link_gdrive"
            id="link-gdrive"
            type="text"
            class="form-control form-control-sm @error('link_gdrive') is-invalid @enderror"
            value="{{ $data->link_gdrive }}"
        >
        @error('link_gdrive')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    @else
    <div class="position-relative form-group">
        <label for="file-gaji" class="font-weight-bold">File Gaji <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_gaji" id="file-gaji" accept="application/pdf" type="file" class="form-control-file @error('file_gaji') is-invalid @enderror">
        @error('file_gaji')
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
                                src="{{ route('fasilitator.gajinonptt.file', ['file' => $data->file_gaji]) }}"
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
    @endif

    <x-button-loader />
    <button
        class="mt-3 btn btn-success btn-sm btn-square btn-hover-shine"
        id="btn-submit"
        type="submit"
    >
        {{ $submit }}
    </button>
    <a
        href="{{ route('fasilitator.gajinonptt', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>

</div>
@endif