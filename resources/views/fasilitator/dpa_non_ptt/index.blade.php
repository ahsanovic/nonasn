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
                                        <form id="form-delete-{{ $fetch_data_2022->id }}" method="POST" action="{{ route('fasilitator.dpanonptt.destroy', $fetch_data_2022->id) }}">
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
                        <a href="" class="btn-shadow btn btn-sm btn-success active show">Edit</a>
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
                                        <form id="form-delete-{{ $fetch_data_2023->id }}" method="POST" action="{{ route('fasilitator.dpanonptt.destroy', $fetch_data_2023->id) }}">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="index" value="{{ $key }}" />
                                        </form>
                                        <button onclick="deleteRow('{{ $fetch_data_2023->id }}')" class="btn btn-sm btn-danger">Hapus</button>                                            
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
















