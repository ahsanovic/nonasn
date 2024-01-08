@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="ortu" class="font-weight-bold">Orang Tua {{ $ortu->status_suami_istri_id == 1 ? 'Ayah' : 'Ibu' }}</label>
        <select class="form-control form-control-sm @error('ortu') is-invalid @enderror" name="ortu">
            <option selected disabled>- pilih -</option>
            <option value="{{ $ortu->suami_istri_id }}" {{ old('ortu') == $ortu->suami_istri_id ? 'selected' : '' }}>{{ $ortu->nama_suami_istri }}</option>
        </select>
        @error('ortu')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama" class="font-weight-bold">Nama Anak</label>
        <input name="nama" id="nama" type="text" class="form-control form-control-sm @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
        @error('nama')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="status" class="font-weight-bold">Status Anak</label>
        <select class="form-control form-control-sm @error('status') is-invalid @enderror" name="status">
            <option selected disabled>- pilih -</option>
            @foreach ($status_anak as $id => $item)
                <option value="{{ $id }}" {{ old('status') == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('status')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tempat-lahir" class="font-weight-bold">Tempat Lahir</label>
        <input
            name="tempat_lahir"
            id="tempat-lahir"
            type="text"
            class="form-control form-control-sm @error('tempat_lahir') is-invalid @enderror"
            value="{{ old('tempat_lahir') }}"
        >
        @error('tempat_lahir')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-lahir" class="font-weight-bold">Tanggal Lahir</label>
        <input
            name="tgl_lahir"
            id="tgl-lahir"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_lahir') is-invalid @enderror"
            value="{{ old('tgl_lahir') }}"
        >
        @error('tgl_lahir')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="pekerjaan" class="font-weight-bold">Pekerjaan</label>
        <select class="form-control form-control-sm @error('pekerjaan') is-invalid @enderror" name="pekerjaan">
            <option selected disabled>- pilih pekerjaan -</option>
            @foreach ($pekerjaan as $id => $item)
                <option value="{{ $id }}" {{ old('pekerjaan') == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('pekerjaan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no-bpjs" class="font-weight-bold">Nomor BPJS Anak</label>
        <input
            name="no_bpjs"
            id="no-bpjs"
            type="text"
            class="form-control form-control-sm @error('no_bpjs') is-invalid @enderror"
            value="{{ old('no_bpjs') }}"
        >
        @error('no_bpjs')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="kelas" class="font-weight-bold">Kelas BPJS</label>
        <select class="form-control form-control-sm @error('kelas') is-invalid @enderror" name="kelas">
            <option value="" selected disabled>- pilih kelas bpjs -</option>
            @foreach ($kelas as $id => $item)
                <option value="{{ $id }}" {{ old('kelas') == $id ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('kelas')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Upload Kartu BPJS Anak <small class="text-primary">*) format file jpg/png</small></label>
        <input name="file_bpjs" id="file" accept="image/png,image/jpg,image/jpeg" type="file" class="form-control-file @error('file_bpjs') is-invalid @enderror">
        @error('file_bpjs')
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
        href="{{ route('fasilitator.anak', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@else
<div>
    <div class="position-relative form-group">
        <label for="ortu" class="font-weight-bold">Orang Tua {{ $ortu->status_suami_istri_id == 1 ? 'Ayah' : 'Ibu' }}</label>
        <select class="form-control form-control-sm @error('ortu') is-invalid @enderror" name="ortu">
            <option value="{{ $ortu->suami_istri_id }}" selected>{{ $ortu->nama_suami_istri }}</option>
        </select>
        @error('ortu')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama" class="font-weight-bold">Nama Anak</label>
        <input name="nama" id="nama" type="text" class="form-control form-control-sm @error('nama') is-invalid @enderror" value="{{ $anak->nama }}">
        @error('nama')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="status" class="font-weight-bold">Status Anak</label>
        <select class="form-control form-control-sm" name="status">
            @foreach ($status_anak as $id => $item)
                <option value="{{ $id }}" {{ ($id == $anak->status_anak_id) ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
    </div>
    <div class="position-relative form-group">
        <label for="tempat-lahir" class="font-weight-bold">Tempat Lahir</label>
        <input
            name="tempat_lahir"
            id="tempat-lahir"
            type="text"
            class="form-control form-control-sm @error('tempat_lahir') is-invalid @enderror"
            value="{{ $anak->tempat_lahir }}"
        >
        @error('tempat_lahir')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-lahir" class="font-weight-bold">Tanggal Lahir</label>
        <input
            name="tgl_lahir"
            id="tgl-lahir"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_lahir') is-invalid @enderror"
            value="{{ $anak->tgl_lahir->format('d/m/Y') }}"
        >
        @error('tgl_lahir')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="pekerjaan" class="font-weight-bold">Pekerjaan</label>
        <select class="form-control form-control-sm" name="pekerjaan">
            @foreach ($pekerjaan as $id => $item)
                <option value="{{ $id }}" {{ ($id == $anak->pekerjaan_anak_id) ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
    </div>
    <div class="position-relative form-group">
        <label for="no-bpjs" class="font-weight-bold">Nomor BPJS Anak</label>
        <input
            name="no_bpjs"
            id="no-bpjs"
            type="text"
            class="form-control form-control-sm @error('no_bpjs') is-invalid @enderror"
            value="{{ $anak->no_bpjs }}"
        >
        @error('no_bpjs')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="kelas" class="font-weight-bold">Kelas BPJS</label>
        <select class="form-control form-control-sm @error('kelas') is-invalid @enderror" name="kelas">
            @if ($anak->kelas_id == null)
                <option value="" selected disabled>- pilih kelas bpjs -</option>
            @endif
            @foreach ($kelas as $id => $item)
                <option value="{{ $id }}" {{ ($id == $anak->kelas_id) ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
        @error('kelas')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Upload Kartu BPJS Anak <small class="text-primary">*) format file jpg/png</small></label>
        <input name="file_bpjs" id="file" accept="image/png,image/jpg,image/jpeg" type="file" class="form-control-file @error('file_bpjs') is-invalid @enderror">
        @error('file_bpjs')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    @if ($anak->file_bpjs != null)
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
                                    src="{{ route('anak.file', ['file' => $anak->file_bpjs]) }}"
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
        href="{{ route('fasilitator.anak', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@endif