@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="jenis-hukdis" class="font-weight-bold">Jenis Hukuman Disiplin</label>
        <select class="form-control form-control-sm @error('jenis_hukdis_id') is-invalid @enderror"" name="jenis_hukdis_id">
            <option value="0" selected disabled>- pilih jenis hukuman disiplin -</option>
            @foreach ($jenis_hukdis as $id => $item)
                <option value="{{ $id }}" {{ old('jenis_hukdis_id') == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('jenis_hukdis_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no-sk" class="font-weight-bold">Nomor SK</label>
        <input name="no_sk" id="no-sk" type="text" class="form-control form-control-sm @error('no_sk') is-invalid @enderror" value="{{ old('no_sk') }}">
        @error('no_sk')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-sk" class="font-weight-bold">Tanggal SK</label>
        <input
            name="tgl_sk"
            id="tgl-sk"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_sk') is-invalid @enderror"
            value="{{ old('tgl_sk') }}"
        >
        @error('tgl_sk')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tmt-awal" class="font-weight-bold">TMT</label>
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
        <label for="keterangan" class="font-weight-bold">Keterangan</label>
        <input
            name="keterangan"
            id="keterangan"
            type="text"
            class="form-control form-control-sm @error('keterangan') is-invalid @enderror"
            value="{{ old('keterangan') }}"
        >
        @error('keterangan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file-hukdis" class="font-weight-bold">Dokumen <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_hukdis" id="file-hukdis" accept="application/pdf" type="file" class="form-control-file @error('file_hukdis') is-invalid @enderror">
        @error('file_hukdis')
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
        href="{{ route('fasilitator.hukdis', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@else
<div>
    <div class="position-relative form-group">
        <label for="jenis-hukdis" class="font-weight-bold">Jenis Hukuman Disiplin</label>
        <select class="form-control form-control-sm @error('jenis_hukdis_id') is-invalid @enderror"" name="jenis_hukdis_id">
            @foreach ($jenis_hukdis as $id => $item)
                <option value="{{ $id }}" {{ $data->jenis_hukdis_id == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('jenis_hukdis_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no-sk" class="font-weight-bold">Nomor SK</label>
        <input name="no_sk" id="no-sk" type="text" class="form-control form-control-sm @error('no_sk') is-invalid @enderror" value="{{ $data->no_sk }}">
        @error('no_sk')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-sk" class="font-weight-bold">Tanggal SK</label>
        <input
            name="tgl_sk"
            id="tgl-sk"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_sk') is-invalid @enderror"
            value="{{ $data->tgl_sk }}"
        >
        @error('tgl_sk')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tmt-awal" class="font-weight-bold">TMT</label>
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
        <label for="keterangan" class="font-weight-bold">Keterangan</label>
        <input
            name="keterangan"
            id="keterangan"
            type="text"
            class="form-control form-control-sm @error('keterangan') is-invalid @enderror"
            value="{{ $data->keterangan }}"
        >
        @error('keterangan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file-hukdis" class="font-weight-bold">Dokumen <small class="text-primary">*) format file pdf, maksimal 1 MB</small></label>
        <input name="file_hukdis" id="file-hukdis" accept="application/pdf" type="file" class="form-control-file @error('file_hukdis') is-invalid @enderror">
        @error('file_hukdis')
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
                            <h5 class="m-0 p-0">Lihat Dokumen</h5>
                        </button>
                    </div>
                    <div data-parent="#accordion" id="collapseOne" class="collapse">
                        <div class="card-body">
                            <iframe
                                src="{{ route('fasilitator.hukdis.file', ['file' => $data->file_hukdis]) }}"
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
        href="{{ route('fasilitator.hukdis', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@endif