@push('scripts')
<!--Datepickers-->
<script src="{{ asset('assets/js/vendors/form-components/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/scripts-init/form-components/datepicker.js') }}"></script>
<script>
    $('#form').submit(function() {
        $('#btn-submit').hide();
        $('.loader').show();
    })
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Biodata
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Biodata</h5>
                    <div class="mt-4">
                        <form id="form" method="post" action="{{ route('nonasn.biodata.update', ['id' => $hashId->encode($biodata->id_ptt)]) }}" enctype="multipart/form-data">
                            @csrf
                            @method("put")
                            <div class="position-relative form-group">
                                <img src="{{ route('nonasn.image', [rtrim($biodata->foto)]) }}" class="rounded d-block" alt="foto" width="150" height="200">
                            </div>
                            <div class="position-relative form-group">
                                <label for="file" class="font-weight-bold">Ubah Foto <small class="text-primary">*) format file jpg/png</small></label>
                                <input name="foto" id="file" type="file" class="form-control-file @error('foto') is-invalid @enderror" accept="image/png, image/jpeg" />
                                @error('foto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="nama" class="font-weight-bold">Nama Lengkap</label>
                                <input name="nama" id="nama" type="text" class="form-control form-control-sm" value="{{ $biodata->nama }}">
                            </div>
                            <div class="position-relative form-group">
                                <label for="niptt" class="font-weight-bold">NIPTT-PK (tanpa tanda "." dan "-")</label>
                                <input name="niptt" id="niptt" type="text" class="form-control form-control-sm" disabled value="{{ $biodata->niptt }}">
                            </div>
                            <div class="position-relative form-group">
                                <label for="skpd" class="font-weight-bold">Unit Kerja</label>
                                <input id="citySel" name="skpd" class="form-control form-control-sm" type="text" readonly value="{{ $biodata->id_skpd . ' - ' . $skpd->name }}"/>
                            </div>
                            <div class="position-relative form-group">
                                <label for="jenis-ptt" class="font-weight-bold">Jenis Pegawai</label>
                                <select class="form-control form-control-sm" name="jenis_ptt" id="jenis-ptt" disabled>
                                    <option value="{{ $biodata->jenis_ptt_id }}" selected>{{ $biodata->jenisPtt->jenis_ptt }}</option>
                                </select>
                                @error('jenis_ptt')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="nik" class="font-weight-bold">NIK</label>
                                <input name="nik" id="nik" type="text" class="form-control form-control-sm @error('nik') is-invalid @enderror" value="{{ $biodata->nik }}">
                                @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="kk" class="font-weight-bold">Nomor KK</label>
                                <input name="kk" id="kk" type="text" class="form-control form-control-sm @error('kk') is-invalid @enderror" value="{{ $biodata->kk }}">
                                @error('kk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="no-bpjs" class="font-weight-bold">Nomor BPJS / KIS</label>
                                <input name="no_bpjs" id="no-bpjs" type="text" class="form-control form-control-sm @error('no_bpjs') is-invalid @enderror" value="{{ $biodata->no_bpjs }}">
                                @error('no_bpjs')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="kelas" class="font-weight-bold">Kelas BPJS</label>
                                <select class="form-control form-control-sm @error('kelas') is-invalid @enderror" name="kelas">
                                    @if ($biodata->kelas_id == null)
                                        <option value="" selected disabled>- pilih kelas bpjs -</option>
                                    @endif
                                    @foreach ($kelas as $id => $item)
                                        <option value="{{ $id }}" {{ ($id == $biodata->kelas_id) ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                                @error('kelas')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="no-bpjs-naker" class="font-weight-bold">Nomor BPJS Ketenagakerjaan</label>
                                <input name="no_bpjs_naker" id="no_bpjs_naker" type="text" class="form-control form-control-sm" value="{{ $biodata->no_bpjs_naker }}">
                            </div>
                            <div class="position-relative form-group">
                                <label for="tempat-lahir" class="font-weight-bold">Tempat Lahir</label>
                                <input
                                    name="tempat_lahir"
                                    id="tempat-lahir"
                                    type="text"
                                    class="form-control form-control-sm @error('tempat_lahir') is-invalid @enderror"
                                    value="{{ $biodata->tempat_lahir }}"
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
                                    name="thn_lahir"
                                    id="tgl-lahir"
                                    data-toggle="datepicker"
                                    type="text"
                                    class="form-control form-control-sm @error('thn_lahir') is-invalid @enderror"
                                    value="{{ $biodata->thn_lahir }}"
                                >
                                @error('thn_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label class="font-weight-bold">Jenis Kelamin</label>
                                <div class="d-flex inline">
                                    <div class="custom-radio custom-control mr-3">
                                        <input type="radio" id="exampleCustomRadio" name="jk" value="L" class="custom-control-input" {{ $biodata->jk == 'L' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="exampleCustomRadio">Laki-Laki</label>
                                    </div>
                                    <div class="custom-radio custom-control">
                                        <input type="radio" id="exampleCustomRadio2" name="jk" value="P" class="custom-control-input" {{ $biodata->jk == 'P' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="exampleCustomRadio2">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="agama" class="font-weight-bold">Agama</label>
                                <select class="form-control form-control-sm" name="agama">
                                    @foreach ($ref_agama as $id => $item)
                                        <option value="{{ $id }}" <?= ($id == $biodata->id_agama) ? 'selected' : '' ?>>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="kawin" class="font-weight-bold">Status Pernikahan</label>
                                <select class="form-control form-control-sm" name="kawin">
                                    @foreach ($ref_kawin as $id => $item)
                                        <option value="{{ $id }}" <?= ($id == $biodata->id_kawin) ? 'selected' : '' ?>>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="no_hp" class="font-weight-bold">No. HP</label>
                                <input name="no_hp" id="no_hp" type="text" class="form-control form-control-sm @error('no_hp') is-invalid @enderror" value="{{ $biodata->no_hp }}">
                                @error('no_hp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="email" class="font-weight-bold">Email</label>
                                <input name="email" id="email" type="text" class="form-control form-control-sm" value="{{ $biodata->email }}">
                            </div>
                            @php
                                list($alamat,$rt,$rw,$desa,$kec,$kab,$prov) = explode("|", $biodata->alamat);
                            @endphp
                            <div class="position-relative form-group">
                                <label for="alamat" class="font-weight-bold">Alamat</label>
                                <input name="alamat" id="alamat" type="text" class="form-control form-control-sm" value="{{ $alamat }}">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="rt" class="font-weight-bold">RT</label>
                                        <input name="rt" id="rt" type="text" class="form-control form-control-sm" value="{{ $rt }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="rw" class="font-weight-bold">RW</label>
                                        <input name="rw" id="rw" type="text" class="form-control form-control-sm" value="{{ $rw }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="desa" class="font-weight-bold">Desa/Kelurahan</label>
                                        <input name="desa" id="desa" type="text" class="form-control form-control-sm" value="{{ $desa }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="kec" class="font-weight-bold">Kecamatan</label>
                                        <input name="kec" id="kec" type="text" class="form-control form-control-sm" value="{{ $kec }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="kab" class="font-weight-bold">Kabupaten/Kota</label>
                                        <input name="kab" id="kab" type="text" class="form-control form-control-sm" value="{{ $kab }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="prov" class="font-weight-bold">Provinsi</label>
                                        <input name="prov" id="prov" type="text" class="form-control form-control-sm" value="{{ $prov }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="kode_pos" class="font-weight-bold">Kode Pos</label>
                                        <input name="kode_pos" id="kode_pos" type="text" class="form-control form-control-sm" value="{{ $biodata->kode_pos }}">
                                    </div>
                                </div>
                            </div>

                            <x-button-loader />
                            <button
                                class="mt-3 btn btn-success btn-sm btn-square btn-hover-shine"
                                id="btn-submit"
                                type="submit"
                            >
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>