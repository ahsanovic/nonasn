@push('scripts')
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
<script>
    $('#form').submit(function() {
        $('#btn-submit').hide();
        $('.loader').show();
    })
</script>
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            Pegawai Baru
            <div class="page-title-subheading">Input Pegawai Non ASN Baru</div>
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form id="form" method="post" action="{{ route('pegawaibaru.store') }}">
                        @csrf
                        <div class="position-relative form-group">
                            <label for="niptt" class="font-weight-bold">NIPTT-PK (tanpa tanda "." dan "-")</label>
                            <input name="niptt" id="niptt" type="text" class="form-control form-control-sm @error('niptt') is-invalid @enderror" value="{{ old('niptt') }}">
                            @error('niptt')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="position-relative form-group">
                            <label for="nama" class="font-weight-bold">Nama Lengkap</label>
                            <input name="nama" id="nama" type="text" class="form-control form-control-sm @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
                            @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="position-relative form-group">
                            <label for="skpd" class="font-weight-bold">Unit Kerja</label>
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
                        <x-button-loader />
                        <button
                            class="mt-1 btn btn-success btn-sm btn-square btn-hover-shine"
                            type="submit"
                            id="btn-submit"
                        >
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@include('_include.unor');