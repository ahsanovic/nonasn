@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    var url = "{{ route('autocomplete') }}";
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
               id_skpd: {{ $hashidSkpd->decode(request()->segment(3))[0] }}
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
                    Pegawai Non ASN
                    <div class="page-title-subheading">Pegawai Non ASN - {{ $skpd->name }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <form method="get" action="{{ url()->current() }}" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <label class="font-weight-bold">Nama / NIPTT</label>
                        <div class="input-group">
                            <input type="text" name="nama" class="form-control form-control-sm" id="search" value="{{ request('nama', '') }}">
                        </div>
                    </div>
		            <div class="col-md-2">
                        <label class="font-weight-bold">Jenis PTT</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm" name="jenis_ptt">
                                <option value="" selected>- pilih jenis ptt -</option>
                                @foreach ($jenis_ptt as $key => $item)
                                    <option value="{{ $key }}" {{ old('jenis_ptt', $selected_jenis_ptt) == $key ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
		            <div class="col-md-4">
                        <label class="font-weight-bold"></label>
                        <div class="input-group">
                            <button class="btn btn-success btn-sm btn-square btn-hover-shine mr-2 mt-2" type="submit">Search</button>
                            <a type="button" class="btn btn-danger btn-sm btn-square btn-hover-shine mt-2" href="{{ route('fasilitator.pegawai', request()->segment(3)) }}">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-end mb-2">
                <span class="font-weight-bold">Jumlah Pegawai: {{ $pegawai->total() }}</span>
            </div>
            <div class="d-flex justify-content-end">
                <form method="post" action="{{ route('fasilitator.download-pegawai', ['idSkpd' => request()->segment(3), 'nama' => request()->nama, 'jenis_ptt' => request()->jenis_ptt]) }}">
                    @csrf
                    <button class="btn btn-dark btn-sm btn-square btn-hover-shine mr-2"><i class="pe-7s-cloud-download"></i> Download Excel</button>
                </form>
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
                                    <div class="avatar-icon rounded btn-hover-shine">
                                        @if ($item->foto)
                                        <img src="{{ route('pegawai.image', ['image' => rtrim($item->foto)]) }}" alt="foto">
                                        @endif
                                    </div>
                                </div>
                                <div><h5 class="menu-header-title">{{ $item->nama }}</h5></div>
                                <div>
                                     <h6 class="menu-header-subtitle">
                                        @php
                                            $panjang_nip = strlen($item->niptt);
                                            if ($item->jenis_ptt_id == 1) {
                                                if ($panjang_nip > 21) {
                                                    if (strpos($item->id_skpd, '145') !== false  OR strpos($item->id_skpd, '146') !== false OR strpos($item->id_skpd, '149') !== false OR strpos($item->id_skpd, '147') !== false){
                                                    $niptt = substr($item->niptt, 0,3).".".substr($item->niptt, 3,1)."-".substr($item->niptt, 4,8)."-".substr($item->niptt, 12,6)."-".substr($item->niptt, 18,5);
                                                    } else if (strpos($item->id_skpd, '148') !== false OR strpos($item->id_skpd, '10316') !== false OR strpos($item->id_skpd, '10310') !== false OR strpos($item->id_skpd, '10309') !== false OR strpos($item->id_skpd, '10313') !== false OR strpos($item->id_skpd, '10308') !== false OR strpos($item->id_skpd, '10314') !== false OR strpos($item->id_skpd, '10312') !== false OR strpos($item->id_skpd, '10311') !== false OR strpos($item->id_skpd, '10320') !== false OR strpos($item->id_skpd, '10315') !== false OR strpos($item->id_skpd, '10318') !== false OR strpos($item->id_skpd, '10317') !== false OR strpos($item->id_skpd, '11513') !== false) {
                                                        $niptt = substr($item->niptt, 0,3).".".substr($item->niptt, 3,2)."-".substr($item->niptt, 5,8)."-".substr($item->niptt, 13,6)."-".substr($item->niptt, 19,5);
                                                    } else {
                                                        $niptt = substr($item->niptt, 0,3)."-".substr($item->niptt, 3,8)."-".substr($item->niptt, 11,6)."-".substr($item->niptt, 17,5);
                                                    }
                                                } else {
                                                    $niptt = substr($item->niptt, 0,3)."-".substr($item->niptt, 3,8)."-".substr($item->niptt, 11,6)."-".substr($item->niptt, 17,5);
                                                }
                                            } else if ($item->jenis_ptt_id == 2) {
                                                $niptt = substr($item->niptt,0,2) . "-" . substr($item->niptt,2,6) . "-" . substr($item->niptt,8,4) . "-" . substr($item->niptt,12,3);
                                            } else if ($item->jenis_ptt_id == 3) {
                                                $niptt = substr($item->niptt,0,8) . "-" . substr($item->niptt,8,6) . "-" . substr($item->niptt,14,4) . "-" . substr($item->niptt,18,5);
                                            } else if ($item->jenis_ptt_id == 5) {
                                                if ($panjang_nip == 18) {
                                                    $niptt = substr($item->niptt,0,1) . "." . substr($item->niptt,1,3) . "-" . substr($item->niptt,4,6) . "-" . substr($item->niptt,10,4) . "-" . substr($item->niptt,14,4);
                                                } else if ($panjang_nip == 19) {
                                                    $niptt = substr($item->niptt,0,1) . "." . substr($item->niptt,1,3) . "." . substr($item->niptt,4,1) . "-" . substr($item->niptt,5,6) . "-" . substr($item->niptt,11,4) . "-" . substr($item->niptt,15,4);
                                                } else if ($panjang_nip == 20) {
                                                    $niptt = substr($item->niptt,0,1) . "." . substr($item->niptt,1,3) . "." . substr($item->niptt,4,2) . "-" . substr($item->niptt,6,6) . "-" . substr($item->niptt,12,4) . "-" . substr($item->niptt,16,4);
                                                }
                                            } else {
                                                $niptt = substr($item->niptt,0,8) . "-" . substr($item->niptt,8,6) . "-" . substr($item->niptt,14,4) . "-" . substr($item->niptt,18,5);
                                            } 
                                        @endphp
                                        {{ $niptt ?? ''}}
                                     </h6>
                                </div>
                                <div class="mt-1">
                                    <small class="opacity-7">
                                        {{ $item->tempat_lahir . ', ' . $item->thn_lahir }}
                                    </small>
                                </div>
                                <div>
                                    <small class="opacity-7">
                                        {{ $item->jenisPtt->jenis_ptt ?? '' }}
                                    </small>
				</div>
				<div>
                                    <small class="opacity-7">
                                        {{ $item->getAge() }}
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
                        <a
                            class="btn btn-hover-shine btn-square btn-sm btn-secondary"
                            href="{{ route('fasilitator.pegawai.show', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($item->id_ptt)]) }}"
                            target="_blank"
                        >
                            Detail
                        </a>
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
