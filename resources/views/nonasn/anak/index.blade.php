@push('scripts')
<script>
    function deleteRow(anak_id) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + anak_id).submit();
            }
        })
    }
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Data Anak
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Anak</h5>
                    <a href="{{ route('nonasn.anak.create') }}" class="btn-square btn btn-sm btn-hover-shine btn-primary mt-3">Tambah</a>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File BPJS</th>
                                        <th>Nama Orang Tua (Ayah/Ibu)</th>
                                        <th>Nama Anak</th>
                                        <th>Tempat/Tgl Lahir</th>
                                        <th>Status Anak</th>
                                        <th>Pekerjaan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td align="center">
                                                @if ($item->file_bpjs)
                                                <a href="{{ route('nonasn.anak.file', ['file' => $item->file_bpjs]) }}" target="_blank">
                                                    <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                </a>
                                                @endif
                                            </td>
                                            <td>{{ $item->orangTua->nama_suami_istri ?? '' }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->tempat_lahir . ', ' . $item->tgl_lahir->format('d M Y') }}</td>
                                            <td>{{ $item->statusAnak->status_anak }}</td>
                                            <td>{{ $item->pekerjaanAnak->pekerjaan ?? '' }}</td>
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
                                                        href="{{ route('nonasn.anak.edit', ['id' => $hashId->encode($item->anak_id)]) }}"
                                                        type="button"
                                                        tabindex="0"
                                                        class="dropdown-item"
                                                    >
                                                        Edit
                                                    </a>
                                                    <form
                                                        id="form-delete-{{ $hashId->encode($item->anak_id) }}"
                                                        method="post"
                                                        action="{{ route('nonasn.anak.destroy', ['id' => $hashId->encode($item->anak_id)]) }}"
                                                    >
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                    <button onclick="deleteRow('{{ $hashId->encode($item->anak_id) }}')" tabindex="0" class="dropdown-item">Hapus</button>
                                                </div>
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
</x-app-layout>