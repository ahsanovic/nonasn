@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    var url = "{{ route('autocomplete-aktif') }}";
    $("#search").autocomplete({
        minLength: 3,
        source: function(request, response) {
          $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
               search: request.term,
            },
            success: function(data) {
               response(data);
            }
          });
        },
        select: function (event, ui) {
           $('#search').val(ui.item.label);
           return false;
        },
    });

    function deaktivasi(niptt) {
        Swal.fire({
            title: 'Yakin akan menonaktifkan pegawai?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Non Aktifkan'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-deaktivasi-' + niptt).submit();
            }
        })
    }
</script> 
@endpush

<x-app-layout>
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-id icon-gradient bg-premium-dark"></i>
                </div>
                <div>
                    Deaktivasi Pegawai
                    <div class="page-title-subheading">Pegawai Non ASN Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <form method="get" action="{{ url()->current() }}" class="mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <label class="font-weight-bold">Nama / NIPTT</label>
                        <div class="input-group">
                            <input type="text" name="nama" class="form-control form-control-sm" id="search">
                            <div class="input-group-append">
                                <button class="btn btn-success btn-sm btn-square btn-hover-shine mr-2" type="submit">Search</button>
                                <a type="button" class="btn btn-danger btn-sm btn-square btn-hover-shine" href="{{ route('fasilitator.pegawai-nonaktif') }}">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <span class="font-weight-bold">Jumlah Pegawai: {{ $pegawai->total() }}</span>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col d-flex justify-content-end">
            {{ $pegawai->withQueryString()->links() }}
        </div>
    </div>
    <div class="row">
        @foreach ($pegawai as $item)
            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="card-shadow-primary card-border mb-3 card">
                    <div class="dropdown-menu-header">
                        <div class="dropdown-menu-header-inner bg-primary">
                            {{-- <div class="menu-header-image" style="background-image: url({{ asset('assets/images/dropdown-header/abstract2.jpg'); }})"></div> --}}
                            <div class="menu-header-content">
                                <div class="avatar-icon-wrapper avatar-icon-xl">
                                    <div class="avatar-icon rounded btn-hover-shine"><img src="{{ asset('upload_foto/' . $item->foto) }}" alt="foto"></div>
                                </div>
                                <div><h5 class="menu-header-title">{{ $item->nama }}</h5></div>
                                <div><h6 class="menu-header-subtitle">{{ $item->niptt }}</h6></div>
                                <div class="mt-1">
                                    <small class="opacity-7">
                                        @php
                                        if ($item->thn_lahir != null) {
                                            echo $item->tempat_lahir . ', ' . $item->thn_lahir;
                                        }
                                        @endphp
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scroll-area-sm">
                        <div class="scrollbar-container ps ps--active-y">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left center-elem mr-2"><i class="fa fa-venus-mars"></i></div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">{{ $item->jk == 'L' ? 'Laki-Laki' : 'Perempuan' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left center-elem mr-2"><i class="fa fa-graduation-cap"></i></div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    @if (isset($item->pendidikan->jenjang->nama_jenjang))
                                                        {{ $item->pendidikan->jenjang->nama_jenjang }}
                                                    @endif
                                                </div>
                                                <div class="widget-subheading">
                                                    @php
                                                    if (isset($item->pendidikan->jenjang->id_jenjang)) {
                                                        if ($item->pendidikan->jenjang->id_jenjang == 1 or
                                                            $item->pendidikan->jenjang->id_jenjang == 2 or
                                                            $item->pendidikan->jenjang->id_jenjang == 3) {
                                                            echo $item->pendidikan->nama_sekolah_sma;
                                                        } else {
                                                            echo $item->pendidikan->nama_pt;
                                                        }
                                                    }
                                                    @endphp
                                                </div>
                                                <div class="widget-subheading">
                                                    @php
                                                    if (isset($item->pendidikan->jenjang->id_jenjang)) {
                                                        if ($item->pendidikan->jenjang->id_jenjang == 1 or
                                                            $item->pendidikan->jenjang->id_jenjang == 2 or
                                                            $item->pendidikan->jenjang->id_jenjang == 3) {
                                                            echo $item->pendidikan->jurusan_sma;
                                                        } else {
                                                            echo $item->pendidikan->jurusan_prodi_pt;
                                                        }
                                                    }
                                                    @endphp
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left center-elem mr-2"><i class="fa fa-id-badge"></i></div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    @if (isset($item->jabatan->refJabatan->name))
                                                        {{ Str::title($item->jabatan->refJabatan->name) }}
                                                    @endif
                                                </div>
                                                <div class="widget-subheading">
                                                    @if (isset($item->jabatan->tgl_mulai))
                                                        {{ $item->jabatan->tgl_mulai }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left center-elem mr-2"><i class="fa fa-sitemap"></i></div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    @php
                                                    switch (strlen($item->id_skpd)) {
                                                        case '3':
                                                            echo $item->skpd->eselon2($item->id_skpd)->name;
                                                            break;
                                                        case '5':
                                                            echo $item->skpd->eselon2($item->id_skpd)->name . ' - ' . $item->skpd->eselon3($item->id_skpd)->name;
                                                            break;
                                                        case '7':
                                                            echo $item->skpd->eselon2($item->id_skpd)->name . ' - ' . $item->skpd->eselon3($item->id_skpd)->name . ' - ' . $item->skpd->eselon4($item->id_skpd)->name;
                                                            break;
                                                        case '9':
                                                            echo $item->skpd->eselon2($item->id_skpd)->name . ' - ' . $item->skpd->eselon3($item->id_skpd)->name . ' - ' . $item->skpd->eselon4($item->id_skpd)->name . ' - ' . $item->skpd->bagian($item->id_skpd)->name;
                                                            break;
                                                        case '11':
                                                            echo $item->skpd->eselon2($item->id_skpd)->name . ' - ' . $item->skpd->eselon3($item->id_skpd)->name . ' - ' . $item->skpd->eselon4($item->id_skpd)->name . ' - ' . $item->skpd->bagian($item->id_skpd)->name . ' - ' . $item->skpd->subbagian($item->id_skpd)->name;
                                                            break;
                                                    }
                                                    @endphp
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 200px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 121px;"></div></div></div>
                    </div>
                    <div class="text-center d-block card-footer">
                        <form id="form-deaktivasi-{{ $item->niptt }}" method="post" action="{{ route('fasilitator.deaktivasi-pegawai', ['niptt' => $item->niptt]) }}">
                            @csrf
                            @method('put')
                        </form>
                        <button onclick="deaktivasi('{{ $item->niptt }}')" class="btn btn-sm btn-hover-shine btn-square btn-secondary"><i class="fa fa-power-off"></i> Deaktivasi</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col d-flex justify-content-end">
            {{ $pegawai->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>