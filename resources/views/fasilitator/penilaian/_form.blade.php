@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="tahun" class="font-weight-bold">Tahun</label>
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
        <label for="nilai" class="font-weight-bold">Nilai <small class="text-primary"> jika terdapat koma, isi dengan tanda baca "(.)"; Contoh: 90.3</small></label>
        <input
            name="nilai"
            id="nilai"
            type="text"
            class="form-control form-control-sm @error('nilai') is-invalid @enderror"
            value="{{ old('nilai') }}"
        >
        @error('nilai')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label class="font-weight-bold">Rekomendasi</label>
        <div class="d-flex inline">
            <div class="custom-radio custom-control mr-3">
                <input type="radio" id="exampleCustomRadio1" name="rekomendasi" value="Dilanjutkan" class="custom-control-input" checked>
                <label class="custom-control-label" for="exampleCustomRadio1">Dilanjutkan</label>
            </div>
            <div class="custom-radio custom-control">
                <input type="radio" id="exampleCustomRadio2" name="rekomendasi" value="Tidak Dilanjutkan" class="custom-control-input">
                <label class="custom-control-label" for="exampleCustomRadio2">Tidak Dilanjutkan</label>
            </div>
        </div>
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen Penilaian <small class="text-primary">*) format file pdf</small></label>
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
        href="{{ route('fasilitator.penilaian', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
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
        <label for="nilai" class="font-weight-bold">Nilai</label>
        <input
            name="nilai"
            id="nilai"
            type="text"
            class="form-control form-control-sm @error('nilai') is-invalid @enderror"
            value="{{ $data->nilai }}"
        >
        @error('nilai')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label class="font-weight-bold">Rekomendasi</label>
        <div class="d-flex inline">
            <div class="custom-radio custom-control mr-3">
                <input
                    type="radio"
                    id="exampleCustomRadio1"
                    name="rekomendasi"
                    value="Dilanjutkan"
                    class="custom-control-input"
                    {{ $data->rekomendasi == 'Dilanjutkan' ? 'checked' : '' }}
                >
                <label class="custom-control-label" for="exampleCustomRadio1">Dilanjutkan</label>
            </div>
            <div class="custom-radio custom-control">
                <input
                    type="radio"
                    id="exampleCustomRadio2"
                    name="rekomendasi"
                    value="Tidak Dilanjutkan"
                    class="custom-control-input"
                    {{ $data->rekomendasi == 'Tidak Dilanjutkan' ? 'checked' : '' }}
                >
                <label class="custom-control-label" for="exampleCustomRadio2">Tidak Dilanjutkan</label>
            </div>
        </div>
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen Penilaian <small class="text-primary">*) format file pdf</small></label>
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
                                src="{{ route('penilaian.file', ['file' => $data->file]) }}"
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
        href="{{ route('fasilitator.penilaian', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>

</div>
@endif