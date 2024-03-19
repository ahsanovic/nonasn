@push('scripts')
<script>
    function deleteRow(id) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + id).submit();
            }
        })
    }
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Dokumen Tes Narkoba
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Dokumen Tes Narkoba</h5>
                    <a href="{{ route('nonasn.dok-narkoba.create') }}" class="btn-square btn btn-sm btn-hover-shine btn-primary mt-3">Tambah</a>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                        <th>Tahun Tes</th>
                                        <th>Nomor/Tgl Surat</th>
                                        <th>Dokter Pemeriksa</th>
                                        <th>Instansi</th>
                                        <th>Aktif</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->file)
                                                    <a href="{{ route('nonasn.dok-narkoba.file', ['file' => $item->file]) }}" target="_blank">
                                                        <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $item->tahun }}</td>
                                            <td>{{ $item->nomor_surat }} <br/> {{ $item->tgl_surat }}</td>
                                            <td>{{ $item->dokter_pemeriksa }}</td>
                                            <td>{{ $item->instansi }}</td>
                                            <td>{{ $item->aktif }}</td>
                                            <td>
                                                <button
                                                    type="button"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mb-2 mr-2 dropdown-toggle btn btn-focus btn-sm btn-square"
                                                >
                                                    Aksi
                                                </button>
                                                <div
                                                    tabindex="-1"
                                                    role="menu"
                                                    aria-hidden="true"
                                                    class="dropdown-menu"
                                                    x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 33px, 0px);"
                                                >
                                                    <a 
                                                        href="{{ route('nonasn.dok-narkoba.edit', ['id' => $hashId->encode($item->id)]) }}"
                                                        type="button"
                                                        tabindex="0"
                                                        class="dropdown-item"
                                                    >
                                                        Edit
                                                    </a>
                                                    <form method="post" action="{{ route('nonasn.dok-narkoba.activate', ['id' => $hashId->encode($item->id)]) }}">
                                                        @csrf
                                                        @method('put')
                                                        <input type="hidden" name="tahun" value="{{ $item->tahun }}">
                                                        @if ($item->aktif == 'N')
                                                            <button type="submit" tabindex="0" class="dropdown-item">Aktifkan</button>
                                                        @endif
                                                    </form>
                                                    <form
                                                        id="form-delete-{{ $hashId->encode($item->id) }}"
                                                        method="post"
                                                        action="{{ route('nonasn.dok-narkoba.destroy', ['id' => $hashId->encode($item->id)]) }}"
                                                    >
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                    <button onclick="deleteRow('{{ $hashId->encode($item->id) }}')" tabindex="0" class="dropdown-item">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td  colspan="8" align="center">- tidak ada data -</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>