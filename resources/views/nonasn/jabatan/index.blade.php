@push('scripts')
<script>
    function deleteRow(id_ptt_jab) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + id_ptt_jab).submit();
            }
        })
    }
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Data Jabatan
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Jabatan</h5>
                    <a href="{{ route('nonasn.jabatan.create') }}" class="btn-square btn btn-sm btn-hover-shine btn-primary mt-3">Tambah</a>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                        <th>Nama Jabatan</th>
                                        <th>Tgl Mulai Kontrak</th>
                                        <th>Tgl Akhir Kontrak</th>
                                        <th>Gaji</th>
                                        <th>Keterangan</th>
                                        <th>Aktif</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->file)
                                                    <a href="{{ route('nonasn.jabatan.file', ['file' => $item->file]) }}" target="_blank">
                                                        <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $item->refJabatan->name ?? '' }}</td>
                                            <td>{{ $item->tgl_mulai }}</td>
                                            <td>{{ $item->tgl_akhir }}</td>
                                            <td>{{ rupiah($item->gaji) }}</td>
                                            <td>{{ $item->ket }}</td>
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
                                                        href="{{ route('nonasn.jabatan.edit', ['id' => $hashId->encode($item->id_ptt_jab)]) }}"
                                                        type="button"
                                                        tabindex="0"
                                                        class="dropdown-item"
                                                    >
                                                        Edit
                                                    </a>
                                                    <form method="post" action="{{ route('nonasn.jabatan.activate', ['id' => $hashId->encode($item->id_ptt_jab)]) }}">
                                                        @csrf
                                                        @method('put')
                                                        <input type="hidden" name="jabatan" value="{{ $item->refJabatan->name }}">
                                                        <input type="hidden" name="no_sk" value="{{ $item->no_surat }}">
                                                        <input type="hidden" name="tgl_sk" value="{{ $item->tgl_surat }}">
                                                        <input type="hidden" name="tgl_mulai" value="{{ $item->tgl_mulai }}">
                                                        <input type="hidden" name="tgl_akhir" value="{{ $item->tgl_akhir }}">
                                                        @if ($item->aktif == 'N')
                                                            <button type="submit" tabindex="0" class="dropdown-item">Aktifkan</button>
                                                        @endif
                                                    </form>
                                                    <form
                                                        id="form-delete-{{ $hashId->encode($item->id_ptt_jab) }}"
                                                        method="post"
                                                        action="{{ route('nonasn.jabatan.destroy', ['id' => $hashId->encode($item->id_ptt_jab)]) }}"
                                                    >
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                    <button onclick="deleteRow('{{ $hashId->encode($item->id_ptt_jab) }}')" tabindex="0" class="dropdown-item">Hapus</button>
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