@if ($submit == 'Simpan')
<div>
    <div class="position-relative form-group">
        <label for="citySel" class="font-weight-bold">Nama Jabatan</label>
        <input id="citySel" name="jabatan" class="form-control form-control-sm @error('jabatan') is-invalid @enderror" type="text" readonly  value="{{ old('jabatan') }}"/>
        <a id="menuBtn" href="#" onclick="showMenu(); return false;">select</a>
        <div id="menuContent" class="menuContent" style="display:none;">
            <ul id="treeUnit" class="ztree" style="margin-top:0;"></ul>
        </div>
        @error('jabatan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no_surat" class="font-weight-bold">Nomor Surat</label>
        <input name="no_surat" id="no_surat" type="text" class="form-control form-control-sm @error('no_surat') is-invalid @enderror" value="{{ old('no_surat') }}">
        @error('no_surat')
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
        <label for="pejabat-penetap" class="font-weight-bold">Pejabat Penetap</label>
        <input
            name="pejabat_penetap"
            id="pejabat-penetap"
            type="text"
            class="form-control form-control-sm @error('pejabat_penetap') is-invalid @enderror"
            value="{{ old('pejabat_penetap') }}"
        >
        @error('pejabat_penetap')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-mulai" class="font-weight-bold">Tanggal Mulai Kontrak</label>
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
        <label for="tgl-akhir" class="font-weight-bold">Tanggal Akhir Kontrak</label>
        <input
            name="tgl_akhir"
            id="tgl-akhir"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_akhir') is-invalid @enderror"
            value="{{ old('tgl_akhir') }}"
        >
        @error('tgl_akhir')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="gaji" class="font-weight-bold">Gaji</label>
        <input
            name="gaji"
            id="gaji"
            type="text"
            class="form-control form-control-sm @error('gaji') is-invalid @enderror"
            value="{{ old('gaji') }}"
        >
        @error('gaji')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="ket" class="font-weight-bold">Keterangan</label>
        <input
            name="ket"
            id="ket"
            type="text"
            class="form-control form-control-sm"
            value="{{ old('ket') }}"
        >
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen SK Jabatan <small class="text-primary">*) format file pdf</small></label>
        <input name="file" id="file" type="file" accept="application/pdf" class="form-control-file @error('file') is-invalid @enderror">
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
        href="{{ route('nonasn.jabatan') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>
@else
<div>
    <div class="position-relative form-group">
        <label for="citySel" class="font-weight-bold">Nama Jabatan</label>
        <input 
            id="citySel"
            name="jabatan"
            class="form-control form-control-sm @error('jabatan') is-invalid @enderror"
            type="text"
            readonly
            value="{{ $jab->id_jabatan . ' - ' . $jab->refJabatan->name }}"
        />
        <a id="menuBtn" href="#" onclick="showMenu(); return false;">select</a>
        <div id="menuContent" class="menuContent" style="display:none;">
            <ul id="treeUnit" class="ztree" style="margin-top:0;"></ul>
        </div>
        @error('jabatan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="no_surat" class="font-weight-bold">Nomor Surat</label>
        <input name="no_surat" id="no_surat" type="text" class="form-control form-control-sm @error('no_surat') is-invalid @enderror" value="{{ $jab->no_surat }}">
        @error('no_surat')
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
            value="{{ $jab->tgl_surat }}"
        >
        @error('tgl_surat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="pejabat-penetap" class="font-weight-bold">Pejabat Penetap</label>
        <input
            name="pejabat_penetap"
            id="pejabat-penetap"
            type="text"
            class="form-control form-control-sm @error('pejabat_penetap') is-invalid @enderror"
            value="{{ $jab->pejabat_penetap }}"
        >
        @error('pejabat_penetap')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-mulai" class="font-weight-bold">Tanggal Mulai Kontrak</label>
        <input
            name="tgl_mulai"
            id="tgl-mulai"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_mulai') is-invalid @enderror"
            value="{{ $jab->tgl_mulai }}"
        >
        @error('tgl_mulai')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="tgl-akhir" class="font-weight-bold">Tanggal Akhir Kontrak</label>
        <input
            name="tgl_akhir"
            id="tgl-akhir"
            data-toggle="datepicker"
            type="text"
            class="form-control form-control-sm @error('tgl_akhir') is-invalid @enderror"
            value="{{ $jab->tgl_akhir }}"
        >
        @error('tgl_akhir')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="gaji" class="font-weight-bold">Gaji</label>
        <input
            name="gaji"
            id="gaji"
            type="text"
            class="form-control form-control-sm @error('gaji') is-invalid @enderror"
            value="{{ $jab->gaji }}"
        >
        @error('gaji')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="ket" class="font-weight-bold">Keterangan</label>
        <input
            name="ket"
            id="ket"
            type="text"
            class="form-control form-control-sm"
            value="{{ $jab->ket }}"
        >
    </div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Dokumen SK Jabatan <small class="text-primary">*) format file pdf</small></label>
        <input name="file" id="file" type="file" accept="application/pdf" class="form-control-file @error('file') is-invalid @enderror">
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
                                src="{{ route('nonasn.jabatan.file', ['file' => $jab->file]) }}"
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
        href="{{ route('nonasn.jabatan') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>

</div>
@endif