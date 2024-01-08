<div>
    <div class="position-relative form-group">
        <label for="file" class="font-weight-bold">Upload Dokumen
            <?php
                switch (Request::segment(4)) {
                    case 'file_ktp':
                        echo 'KTP';
                        break;
                    case 'file_bpjs':
                        echo 'BPJS Kesehatan/KIS';
                        break;
                    case 'file_bpjs_naker':
                        echo 'BPJS Ketenagakerjaan';
                        break;
                }
            ?>
            <small class="text-primary">*) format file jpg/png</small></label>
        <input name="{{ Request::segment(4) }}" accept="image/png,image,jpg,image,jpeg" id="file" type="file" class="form-control-file
            @if ($errors->has('file_ktp') || $errors->has('file_bpjs') || $errors->has('file_bpjs_naker'))
                is-invalid            
            @endif"
        >
        @error('file_ktp')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        @error('file_bpjs')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        @error('file_bpjs_naker')
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
        Update
    </button>
    <a
        href="{{ route('nonasn.dok-pribadi') }}"
        class="mt-3 btn btn-secondary btn-sm btn-square btn-hover-shine"
        id="btn-cancel"
    >
        Batal
    </a>
</div>