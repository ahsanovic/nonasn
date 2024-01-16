<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Ubah Password
        </div>
    </x-page-header-nonasn>
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="alert alert-danger fade show" role="alert">
                password harus berupa kombinasi dari huruf kecil, huruf besar, dan angka
            </div>    
        </div>    
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form id="form" method="post" action="{{ route('fasilitator.password.update') }}">
                        @csrf
                        @method('put')
                        <div class="position-relative form-group">
                            <label for="password-baru" class="font-weight-bold">Password Baru</label>
                            <input
                                name="password"
                                id="password-baru"
                                type="password"
                                class="form-control form-control-sm @error('password') is-invalid @enderror"
                                value="{{ old('password') }}"
                            >
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="position-relative form-group">
                            <label for="password-conf" class="font-weight-bold">Konfirmasi Password Baru</label>
                            <input
                                name="password_confirmation"
                                id="password-conf"
                                type="password"
                                class="form-control form-control-sm"
                            >
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <x-button-loader />
                        <button
                            class="mt-1 btn btn-success btn-sm btn-square btn-hover-shine"
                            type="submit"
                            id="btn-submit"
                        >
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>