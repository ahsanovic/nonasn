<div>
    <div class="position-relative form-group">
        <label for="username" class="font-weight-bold">Username</label>
        <input name="niptt" id="username" type="text" class="form-control form-control-sm" value="{{ $user->niptt }}" readonly>
     </div>
    <div class="position-relative form-group">
        <label for="password" class="font-weight-bold">Password</label>
        <input name="password" id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror">
        <span class="text-danger"><small><em>biarkan kosong jika password tidak diganti</em></small></span>
        @error('password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="position-relative form-group">
        <label for="nama" class="font-weight-bold">Nama Lengkap</label>
        <input name="nama" id="nama-lengkap" type="text" class="form-control form-control-sm" value="{{ $user->nama }}" readonly>
    </div>
    <div class="position-relative form-group">
        <label for="skpd" class="font-weight-bold">Unit Kerja</label>
        @php
            $idSkpd = $skpd->id ?? '';
            $skpdName = $skpd->name ?? '';
        @endphp
        <input id="citySel" name="skpd" class="form-control form-control-sm" type="text" readonly  value="{{ $idSkpd . ' - ' . $skpdName }}"/>
    </div>
    <div class="position-relative form-group">
        <label class="font-weight-bold">Blokir</label>
        <div class="d-flex inline">
            <div class="custom-radio custom-control mr-3">
                <input type="radio" id="exampleCustomRadio1" name="blokir" value="Y" class="custom-control-input" {{ $user->blokir == 'Y' ? 'checked' : '' }}>
                <label class="custom-control-label" for="exampleCustomRadio1">Y</label>
            </div>
            <div class="custom-radio custom-control">
                <input type="radio" id="exampleCustomRadio2" name="blokir" value="N" class="custom-control-input" {{ $user->blokir == 'N' ? 'checked' : '' }}>
                <label class="custom-control-label" for="exampleCustomRadio2">N</label>
            </div>
        </div>
    </div>
    
    <x-button-loader />
    <button class="mt-1 btn-square btn-sm btn-hover-shine btn btn-success" id="btn-submit" type="submit">Update</button>        
    <a class="mt-1 mr-2 btn-square btn-sm btn-hover-shine btn btn-secondary" id="btn-cancel" href={{ route('fasilitator.user-nonasn') }}>Batal</a>
</div>