@push('scripts')
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
<script>
    $('#form').submit(function() {
        $('#btn-submit').hide();
        $('.loader').show();
    });

    $('#jenis-ptt').change(function() {
        $('#load-numbers').attr('disabled', false);
        $('#reset').attr('disabled', false);
        $('#jenis-ptt').attr('disabled', true);
    });

    $('#reset').on('click', function() {
        $('#load-numbers').attr('disabled', true);
        $('#jenis-ptt').attr('disabled', false);
        $("#jenis-ptt").prop("selectedIndex", 0);
        $('#reset').attr('disabled', true);
        $('#number-list').empty();
    });

    document.getElementById('load-numbers').addEventListener('click', function(e) {
        e.preventDefault();
        let jenisPtt = document.getElementById('jenis-ptt').value;
        const url = `{{ route('available-nip') }}?jenis_ptt=${jenisPtt}`;
        let numberList = document.getElementById('number-list');
        numberList.innerHtml = "";
        fetch(url)
            .then(res => res.json())
            .then(data => {
                data.forEach(function(number) {
                    let listItem = document.createElement('li');
                    listItem.classList.add('list-group-item');
                    if (jenisPtt == 2) {
                        listItem.textContent = number.toString().padStart(3, '0');
                    } else {
                        listItem.textContent = number.toString().padStart(4, '0');
                    }
                    numberList.appendChild(listItem);
                })
            })
            .catch(error => console.log(error));
    });
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
                            <label for="nama" class="font-weight-bold">Jenis PTT</label>
                            <select class="form-control form-control-sm @error('jenis_ptt') is-invalid @enderror" name="jenis_ptt">
                                <option value="0" selected disabled>- pilih jenis ptt -</option>
                                @foreach ($jenis_ptt as $id => $item)
                                    <option value="{{ $id }}" {{ old('jenis_ptt') == $id ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('jenis_ptt')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
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
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Cari Nomor Induk yang Masih Avail</h5>
                    <div class="position-relative form-group">
                        <label for="jenis-ptt" class="font-weight-bold">Jenis PTT</label>
                        <select class="form-control form-control-sm" name="jenis_ptt" id="jenis-ptt">
                            <option value="0" selected>- pilih jenis ptt -</option>
                            @foreach ($jenis_ptt as $id => $item)
                                <option value="{{ $id }}" {{ old('jenis_ptt') == $id ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button id="load-numbers" class="mb-4 mt-2 btn btn-shadow btn-outline-warning" disabled>Hunting yuk!</button>
                    <button id="reset" class="mb-4 mt-2 ml-2 btn btn-shadow btn-outline-danger" disabled>Reset</button>
                    <div class="scroll-area-md">
                        <div class="scrollbar-container">
                            <ul class="list-group list-group-flush" id="number-list"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@include('_include.unor');