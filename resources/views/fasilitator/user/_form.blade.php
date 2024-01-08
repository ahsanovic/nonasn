@if ($submit == 'Simpan')
    <div>
        <div class="position-relative form-group">
            <label for="username">Username</label>
            <input name="username" id="username" type="text" class="form-control form-control-sm @error('username') is-invalid @enderror" value="{{ old('username') }}">
            @error('username')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label for="password">Password</label>
            <input name="password" id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" value="{{ old('password') }}">
            @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input name="nama_lengkap" id="nama-lengkap" type="text" class="form-control form-control-sm @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}">
            @error('nama_lengkap')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label for="email">Email</label>
            <input name="email" id="email" type="text" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email') }}">
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label for="no_telp">No. HP</label>
            <input name="no_telp" id="no_telp" type="text" class="form-control form-control-sm @error('no_telp') is-invalid @enderror" value="{{ old('no_telp') }}">
            @error('no_telp')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label for="skpd" class="">Unit Kerja</label>
            <input id="citySel" name="skpd" class="form-control form-control-sm @error('skpd') is-invalid @enderror" type="text" readonly  value="{{ old('skpd') }}"/>
            <a id="menuBtn" href="#" onclick="showMenu(); return false;">select</a>
            <div id="menuContent" class="menuContent" style="display:none;">
                <ul id="treeUnit" class="ztree" style="margin-top:0;"></ul>
            </div>
            @error('skpd')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label>Level Pengguna</label>
            <div class="d-flex inline">
                <div class="custom-radio custom-control mr-3">
                    <input type="radio" id="exampleCustomRadio" name="level" value="admin" class="custom-control-input">
                    <label class="custom-control-label" for="exampleCustomRadio">Admin</label>
                </div>
                <div class="custom-radio custom-control">
                    <input type="radio" id="exampleCustomRadio2" name="level" value="user" class="custom-control-input" checked>
                    <label class="custom-control-label" for="exampleCustomRadio2">User</label>
                </div>
            </div>
        </div>
        
        <x-button-loader />
        <button class="mt-1 btn-square btn-sm btn-hover-shine btn btn-success" id="btn-submit" type="submit">{{ $submit }}</button>        
        <a class="mt-1 btn-square btn-sm btn-hover-shine btn btn-secondary" id="btn-cancel" href={{ route('fasilitator.user') }}>Batal</a>
    </div>
@else
    <div>
        <div class="position-relative form-group">
            <label for="username" class="font-weight-bold">Username</label>
            <input name="username" id="username" type="text" class="form-control form-control-sm" value="{{ $user->username }}" readonly>
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
            <label for="nama_lengkap" class="font-weight-bold">Nama Lengkap</label>
            <input name="nama_lengkap" id="nama-lengkap" type="text" class="form-control form-control-sm @error('nama_lengkap') is-invalid @enderror" value="{{ $user->nama_lengkap }}">
            @error('nama_lengkap')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label for="email" class="font-weight-bold">Email</label>
            <input name="email" id="email" type="text" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ $user->email }}">
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label for="no_telp" class="font-weight-bold">No. HP</label>
            <input name="no_telp" id="no_telp" type="text" class="form-control form-control-sm @error('no_telp') is-invalid @enderror" value="{{ $user->no_telp }}">
            @error('no_telp')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label for="skpd" class="font-weight-bold">Unit Kerja</label>
            <input id="citySel" name="skpd" class="form-control form-control-sm @error('skpd') is-invalid @enderror" type="text" readonly  value="{{ $skpd->id . ' - ' . $skpd->name }}"/>
            <a id="menuBtn" href="#" onclick="showMenu(); return false;">select</a>
            <div id="menuContent" class="menuContent" style="display:none;">
                <ul id="treeUnit" class="ztree" style="margin-top:0;"></ul>
            </div>
            @error('skpd')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="position-relative form-group">
            <label class="font-weight-bold">Level Pengguna</label>
            <div class="d-flex inline">
                <div class="custom-radio custom-control mr-3">
                    <input type="radio" id="exampleCustomRadio" name="level" value="admin" class="custom-control-input" {{ $user->level == 'admin' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="exampleCustomRadio">Admin</label>
                </div>
                <div class="custom-radio custom-control">
                    <input type="radio" id="exampleCustomRadio2" name="level" value="user" class="custom-control-input" {{ $user->level == 'user' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="exampleCustomRadio2">User</label>
                </div>
            </div>
        </div>
        <div class="position-relative form-group">
            <label class="font-weight-bold">Blokir</label>
            <div class="d-flex inline">
                <div class="custom-radio custom-control mr-3">
                    <input type="radio" id="exampleCustomRadio3" name="blokir" value="Y" class="custom-control-input" {{ $user->blokir == 'Y' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="exampleCustomRadio3">Y</label>
                </div>
                <div class="custom-radio custom-control">
                    <input type="radio" id="exampleCustomRadio4" name="blokir" value="N" class="custom-control-input" {{ $user->blokir == 'N' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="exampleCustomRadio4">N</label>
                </div>
            </div>
        </div>
        
        <x-button-loader />
        <button class="mt-1 btn-square btn-sm btn-hover-shine btn btn-success" id="btn-submit" type="submit">{{ $submit }}</button>  
        <a class="mt-1 btn-square btn-sm btn-hover-shine btn btn-secondary" id="btn-cancel" href={{ route('fasilitator.user') }}>Batal</a>
    </div>
@endif