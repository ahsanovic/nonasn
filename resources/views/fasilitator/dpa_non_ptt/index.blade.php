@push('scripts')
<script>
    function deleteRow(id_dpa) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + id_dpa).submit();
            }
        })
    }
</script>
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            DPA
        </div>
    </x-page-header>

    @if (auth()->user()->id_skpd == 1 && auth()->user()->level == 'admin')
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card card-hover-shadow-2x">
                    <div class="card-body">
                        <h5 class="card-title">Rekap DPA OPD</h5>
                        <div class="mt-4">
                            <div class="table-responsive">
                                <table class="mb-0 table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>OPD</th>
                                            <th>2022</th>
                                            <th>2023</th>
                                            <th>2024</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($opd as $item)
                                            <tr>
                                                <td scope="row">{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @foreach ($data_2022 as $data2022)
                                                        @foreach ($data2022 as $fetch)
                                                            @if ($item->id == $fetch->id_skpd)
                                                            <div class="dropdown">
                                                                <button
                                                                    class="mb-2 mr-2 btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-secondary"
                                                                    aria-haspopup="true"
                                                                    aria-expanded="false"
                                                                    data-toggle="dropdown"
                                                                >
                                                                    <i class="lnr-eye btn-icon-wrapper"> </i>
                                                                </button>
                                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(42px, 46px, 0px);">
                                                                    <div class="dropdown-menu-header mb-0">
                                                                        <div class="dropdown-menu-header-inner bg-deep-blue">
                                                                            <div class="menu-header-image opacity-1" style="background-image: url('../assets/images/dropdown-header/city3.jpg');"></div>
                                                                            <div class="menu-header-content text-dark">
                                                                                <h5 class="menu-header-title">DPA Tahun 2022</h5>
                                                                                <h6 class="menu-header-subtitle">{{ Str::title($item->name) }}</h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @foreach ($data_2022 as $dpa)
                                                                        @foreach ($dpa as $row)
                                                                            @if ($item->id == $row->id_skpd)
                                                                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                                                                <li class="nav-item">
                                                                                    <a
                                                                                        href="{{ route('fasilitator.dpanonptt.file', ['file' => $row->file_dpa])}}"
                                                                                        target="_blank"
                                                                                        type="button"
                                                                                        class="btn btn-sm btn-warning"
                                                                                    >
                                                                                        <i class="lnr-file-empty"></i> Dokumen
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active" id="tab-messages-header" role="tabpanel">
                                                                            <div class="scroll-area-sm">
                                                                                <div class="scrollbar-container ps">
                                                                                    <div class="p-3">
                                                                                        <div class="notifications-box">
                                                                                            @foreach ($data_2022 as $dpa)
                                                                                                @foreach ($dpa as $row)
                                                                                                    @if ($item->id == $row->id_skpd)
                                                                                                    <ul class="list-group list-group-flush">
                                                                                                        <div class="row">
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">Kode Rekening</p>
                                                                                                            </div>
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">Jumlah Pegawai</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        @php
                                                                                                            $data_dpa = json_decode($row->data_dpa, true);
                                                                                                            $total_pegawai = 0;
                                                                                                        @endphp
                                                                                                        <div class="row">
                                                                                                            @foreach ($data_dpa as $data)
                                                                                                            @php
                                                                                                                $total_pegawai += (int) $data['jml_pegawai'];
                                                                                                            @endphp
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item">{{ $data['kode_rekening'] ?? '' }}</p>
                                                                                                            </div>
                                                                                                            <div class="col-4">
                                                                                                                <p class="list-group-item">{{ $data['jml_pegawai'] ?? '' }}</p>
                                                                                                            </div>
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                            <div class="col-6 d-flex justify-content-end font-weight-bold">
                                                                                                                <p class="list-group-item">Total</p>
                                                                                                            </div>
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">{{ $total_pegawai }}</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </ul>
                                                                                                    @endif                                                                                        
                                                                                                @endforeach
                                                                                            @endforeach
                                                                                        </div>
                                                                                    </div>
                                                                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($data_2023 as $data2023)
                                                        @foreach ($data2023 as $fetch)
                                                            @if ($item->id == $fetch->id_skpd)
                                                            <div class="dropdown">
                                                                <button
                                                                    class="mb-2 mr-2 btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-success"
                                                                    aria-haspopup="true"
                                                                    aria-expanded="false"
                                                                    data-toggle="dropdown"
                                                                >
                                                                    <i class="lnr-eye btn-icon-wrapper"> </i>
                                                                </button>
                                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(42px, 46px, 0px);">
                                                                    <div class="dropdown-menu-header mb-0">
                                                                        <div class="dropdown-menu-header-inner bg-deep-blue">
                                                                            <div class="menu-header-image opacity-1" style="background-image: url('../assets/images/dropdown-header/city3.jpg');"></div>
                                                                            <div class="menu-header-content text-dark">
                                                                                <h5 class="menu-header-title">DPA Tahun 2023</h5>
                                                                                <h6 class="menu-header-subtitle">{{ Str::title($item->name) }}</h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @foreach ($data_2023 as $dpa)
                                                                        @foreach ($dpa as $row)
                                                                            @if ($item->id == $row->id_skpd)
                                                                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                                                                <li class="nav-item">
                                                                                    <a
                                                                                        href="{{ route('fasilitator.dpanonptt.file', ['file' => $row->file_dpa])}}"
                                                                                        target="_blank"
                                                                                        type="button"
                                                                                        class="btn btn-sm btn-warning"
                                                                                    >
                                                                                        <i class="lnr-file-empty"></i> Dokumen
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active" id="tab-messages-header" role="tabpanel">
                                                                            <div class="scroll-area-sm">
                                                                                <div class="scrollbar-container ps">
                                                                                    <div class="p-3">
                                                                                        <div class="notifications-box">
                                                                                            @foreach ($data_2023 as $dpa)
                                                                                                @foreach ($dpa as $row)
                                                                                                    @if ($item->id == $row->id_skpd)
                                                                                                    <ul class="list-group list-group-flush">
                                                                                                        <div class="row">
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">Kode Rekening</p>
                                                                                                            </div>
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">Jumlah Pegawai</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        @php
                                                                                                            $data_dpa = json_decode($row->data_dpa, true);
                                                                                                            $total_pegawai = 0;
                                                                                                        @endphp
                                                                                                        <div class="row">
                                                                                                            @foreach ($data_dpa as $data)
                                                                                                            @php
                                                                                                                $total_pegawai += (int) $data['jml_pegawai'];
                                                                                                            @endphp
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item">{{ $data['kode_rekening'] ?? '' }}</p>
                                                                                                            </div>
                                                                                                            <div class="col-4">
                                                                                                                <p class="list-group-item">{{ $data['jml_pegawai'] ?? '' }}</p>
                                                                                                            </div>
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                            <div class="col-6 d-flex justify-content-end font-weight-bold">
                                                                                                                <p class="list-group-item">Total</p>
                                                                                                            </div>
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">{{ $total_pegawai }}</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </ul>
                                                                                                    @endif                                                                                        
                                                                                                @endforeach
                                                                                            @endforeach
                                                                                        </div>
                                                                                    </div>
                                                                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($data_2024 as $data2024)
                                                        @foreach ($data2024 as $fetch)
                                                            @if ($item->id == $fetch->id_skpd)
                                                            <div class="dropdown">
                                                                <button
                                                                    class="mb-2 mr-2 btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-info"
                                                                    aria-haspopup="true"
                                                                    aria-expanded="false"
                                                                    data-toggle="dropdown"
                                                                >
                                                                    <i class="lnr-eye btn-icon-wrapper"> </i>
                                                                </button>
                                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(42px, 46px, 0px);">
                                                                    <div class="dropdown-menu-header mb-0">
                                                                        <div class="dropdown-menu-header-inner bg-deep-blue">
                                                                            <div class="menu-header-image opacity-1" style="background-image: url('../assets/images/dropdown-header/city3.jpg');"></div>
                                                                            <div class="menu-header-content text-dark">
                                                                                <h5 class="menu-header-title">DPA Tahun 2024</h5>
                                                                                <h6 class="menu-header-subtitle">{{ Str::title($item->name) }}</h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @foreach ($data_2024 as $dpa)
                                                                        @foreach ($dpa as $row)
                                                                            @if ($item->id == $row->id_skpd)
                                                                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                                                                <li class="nav-item">
                                                                                    <a
                                                                                        href="{{ route('fasilitator.dpanonptt.file', ['file' => $row->file_dpa])}}"
                                                                                        target="_blank"
                                                                                        type="button"
                                                                                        class="btn btn-sm btn-warning"
                                                                                    >
                                                                                        <i class="lnr-file-empty"></i> Dokumen
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active" id="tab-messages-header" role="tabpanel">
                                                                            <div class="scroll-area-sm">
                                                                                <div class="scrollbar-container ps">
                                                                                    <div class="p-3">
                                                                                        <div class="notifications-box">
                                                                                            @foreach ($data_2024 as $dpa)
                                                                                                @foreach ($dpa as $row)
                                                                                                    @if ($item->id == $row->id_skpd)
                                                                                                    <ul class="list-group list-group-flush">
                                                                                                        <div class="row">
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">Kode Rekening</p>
                                                                                                            </div>
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">Jumlah Pegawai</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        @php
                                                                                                            $data_dpa = json_decode($row->data_dpa, true);
                                                                                                            $total_pegawai = 0;
                                                                                                        @endphp
                                                                                                        <div class="row">
                                                                                                            @foreach ($data_dpa as $data)
                                                                                                            @php
                                                                                                                $total_pegawai += (int) $data['jml_pegawai'];
                                                                                                            @endphp
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item">{{ $data['kode_rekening'] ?? '' }}</p>
                                                                                                            </div>
                                                                                                            <div class="col-4">
                                                                                                                <p class="list-group-item">{{ $data['jml_pegawai'] ?? '' }}</p>
                                                                                                            </div>
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                            <div class="col-6 d-flex justify-content-end font-weight-bold">
                                                                                                                <p class="list-group-item">Total</p>
                                                                                                            </div>
                                                                                                            <div class="col-6">
                                                                                                                <p class="list-group-item font-weight-bold">{{ $total_pegawai }}</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </ul>
                                                                                                    @endif                                                                                        
                                                                                                @endforeach
                                                                                            @endforeach
                                                                                        </div>
                                                                                    </div>
                                                                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
    <a href="{{ route('fasilitator.dpanonptt.create') }}" class="btn-square btn btn-sm btn-hover-shine btn-primary mb-3"
    >
        Tambah
    </a>
    
    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-file-add icon-gradient bg-happy-itmeo"> </i>DPA Tahun 2022
                    @if ($fetch_data_2022)
                    <div class="btn-actions-pane-right actions-icon-btn">
                        @if ($fetch_data_2022->file_dpa)
                        <a
                            href="{{ route('fasilitator.dpanonptt.file', ['file' => $fetch_data_2022->file_dpa])}}"
                            target="_blank"
                            type="button"
                            class="btn btn-sm btn-warning"
                        >
                            <i class="lnr-file-empty"></i> Dokumen
                        </a>
                        @endif
                        <a
                            href="{{ route('fasilitator.dpanonptt.edit', ['id' => $fetch_data_2022->id]) }}"
                            class="btn-shadow btn btn-sm btn-success active show"
                        >
                            Edit
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    @if ($data_2022)
                        <ul class="list-group list-group-flush">
                            <div class="row">
                                <div class="col-6">
                                    <p class="list-group-item font-weight-bold">Kode Rekening</p>
                                </div>
                                <div class="col-6">
                                    <p class="list-group-item font-weight-bold">Jumlah Pegawai</p>
                                </div>
                            </div>
                            @foreach ($data_2022 as $key => $item)
                                <div class="row">
                                    <div class="col-6">
                                        <p class="list-group-item">{{ $item['kode_rekening'] }}</p>
                                    </div>
                                    <div class="col-4">
                                        <p class="list-group-item">{{ $item['jml_pegawai'] }}</p>
                                    </div>
                                    <div class="col-2 mt-2">
                                        <form
                                            id="form-delete-{{ $fetch_data_2022->id }}"
                                            method="POST"
                                            action="{{ route('fasilitator.dpanonptt.destroy', $fetch_data_2022->id) }}"
                                        >
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="index" value="{{ $key }}" />
                                        </form>
                                        <button onclick="deleteRow('{{ $fetch_data_2022->id }}')" class="btn btn-sm btn-danger">Hapus</button>                                            
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-6 d-flex justify-content-end font-weight-bold">
                                    <p class="list-group-item">Total</p>
                                </div>
                                <div class="col-6">
                                    <p class="list-group-item">{{ $count_jml_pegawai_2022 }}</p>
                                </div>
                            </div>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-file-add icon-gradient bg-happy-itmeo"> </i>DPA Tahun 2023
                    @if ($data_2023)
                    <div class="btn-actions-pane-right actions-icon-btn">
                        @if ($fetch_data_2023->file_dpa)
                        <a
                            href="{{ route('fasilitator.dpanonptt.file', ['file' => $fetch_data_2023->file_dpa])}}"
                            target="_blank"
                            type="button"
                            class="btn btn-sm btn-warning"
                        >
                            <i class="lnr-file-empty"></i> Dokumen
                        </a>
                        @endif
                        <a
                            href="{{ route('fasilitator.dpanonptt.edit', ['id' => $fetch_data_2023->id]) }}"
                            class="btn-shadow btn btn-sm btn-success active show"
                        >
                            Edit
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    @if ($data_2023)
                        <ul class="list-group list-group-flush">
                            <div class="row">
                                <div class="col-6">
                                    <p class="list-group-item font-weight-bold">Kode Rekening</p>
                                </div>
                                <div class="col-6">
                                    <p class="list-group-item font-weight-bold">Jumlah Pegawai</p>
                                </div>
                            </div>
                            @foreach ($data_2023 as $item)
                                <div class="row">
                                    <div class="col-6">
                                        <p class="list-group-item">{{ $item['kode_rekening'] }}</p>
                                    </div>
                                    <div class="col-4">
                                        <p class="list-group-item">{{ $item['jml_pegawai'] }}</p>
                                    </div>
                                    <div class="col-2 mt-2">
                                        <form
                                            id="form-delete-{{ $fetch_data_2023->id }}"
                                            method="POST"
                                            action="{{ route('fasilitator.dpanonptt.destroy', $fetch_data_2023->id) }}"
                                        >
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="index" value="{{ $key }}" />
                                        </form>
                                        <button onclick="deleteRow('{{ $fetch_data_2023->id }}')" class="btn btn-sm btn-danger">Hapus</button>                                            
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-6 d-flex justify-content-end font-weight-bold">
                                    <p class="list-group-item">Total</p>
                                </div>
                                <div class="col-6">
                                    <p class="list-group-item">{{ $count_jml_pegawai_2023 }}</p>
                                </div>
                            </div>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-file-add icon-gradient bg-happy-itmeo"> </i>DPA Tahun 2024
                    @if ($fetch_data_2024)
                    <div class="btn-actions-pane-right actions-icon-btn">
                        @if ($fetch_data_2024->file_dpa)
                        <a
                            href="{{ route('fasilitator.dpanonptt.file', ['file' => $fetch_data_2024->file_dpa])}}"
                            target="_blank"
                            type="button"
                            class="btn btn-sm btn-warning"
                        >
                            <i class="lnr-file-empty"></i> Dokumen
                        </a>
                        @endif
                        <a
                            href="{{ route('fasilitator.dpanonptt.edit', ['id' => $fetch_data_2024->id]) }}"
                            class="btn-shadow btn btn-sm btn-success active show"
                        >
                            Edit
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    @if ($data_2024)
                        <ul class="list-group list-group-flush">
                            <div class="row">
                                <div class="col-6">
                                    <p class="list-group-item font-weight-bold">Kode Rekening</p>
                                </div>
                                <div class="col-6">
                                    <p class="list-group-item font-weight-bold">Jumlah Pegawai</p>
                                </div>
                            </div>
                            @foreach ($data_2024 as $key => $item)
                                <div class="row">
                                    <div class="col-6">
                                        <p class="list-group-item">{{ $item['kode_rekening'] }}</p>
                                    </div>
                                    <div class="col-4">
                                        <p class="list-group-item">{{ $item['jml_pegawai'] }}</p>
                                    </div>
                                    <div class="col-2 mt-2">
                                        <form
                                            id="form-delete-{{ $fetch_data_2024->id }}"
                                            method="POST"
                                            action="{{ route('fasilitator.dpanonptt.destroy', $fetch_data_2024->id) }}"
                                        >
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="index" value="{{ $key }}" />
                                        </form>
                                        <button onclick="deleteRow('{{ $fetch_data_2024->id }}')" class="btn btn-sm btn-danger">Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-6 d-flex justify-content-end font-weight-bold">
                                    <p class="list-group-item">Total</p>
                                </div>
                                <div class="col-6">
                                    <p class="list-group-item">{{ $count_jml_pegawai_2024 }}</p>
                                </div>
                            </div>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
















