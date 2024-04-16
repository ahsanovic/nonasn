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
    <x-page-header>
        <div>
            Profil Pegawai
            <div class="page-title-subheading">{{ $pegawai->nama . ' - ' . $skpd->name }}</div>
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-3">
            @include('_include.profile-card')
        </div>
        <div class="col-md-9">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Hukuman Disiplin</h5>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-danger fade show" role="alert">
                                Pastikan data hukuman disiplin diaktifkan dengan menekan tombol aksi kemudian "Aktifkan"
                            </div>    
                        </div>    
                    </div>
                    <a href="{{
                        route('fasilitator.hukdis.create', [
                            'idSkpd' => $hashidSkpd->encode($skpd->id),
                            'id' => $hashidPegawai->encode($pegawai->id_ptt)
                        ])
                    }}"
                    class="btn-square btn btn-sm btn-hover-shine btn-primary mt-3"
                    >
                        Tambah
                    </a>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                        <th>Jenis Hukuman Disiplin</th>
                                        <th>No. SK</th>
                                        <th>Tgl SK</th>
                                        <th>TMT</th>
                                        <th>Keterangan</th>
                                        <th>Aktif</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->file_hukdis)
                                                    <a href="{{ route('fasilitator.hukdis.file', ['file' => $item->file_hukdis]) }}" target="_blank">
                                                        <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $item->jenisHukdis->jenis_hukdis }}</td>
                                            <td>{{ $item->no_sk }}</td>
                                            <td>{{ $item->tgl_sk }}</td>
                                            <td>{{ $item->tmt_awal }}</td>
                                            <td>{{ $item->keterangan }}</td>
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
                                                        href="{{
                                                            route('fasilitator.hukdis.edit', [
                                                                'idSkpd' => $hashidSkpd->encode($skpd->id),
                                                                'id' => $hashidPegawai->encode($pegawai->id_ptt),
                                                                'idHukdis' => $hashid->encode($item->id)
                                                            ])
                                                        }}"
                                                        type="button"
                                                        tabindex="0"
                                                        class="dropdown-item"
                                                    >
                                                        Edit
                                                    </a>
                                                    <form
                                                        method="post"
                                                        action="{{
                                                            route('fasilitator.hukdis.activate', [
                                                                'id' => $hashid->encode($item->id),
                                                            ])
                                                        }}"
                                                    >
                                                        @csrf
                                                        @method('put')
                                                        <input type="hidden" name="id_pegawai" value="{{ $hashidPegawai->encode($pegawai->id_ptt) }}">
                                                        @if ($item->aktif == 'N')
                                                            <button type="submit" tabindex="0" class="dropdown-item">Aktifkan</button>
                                                        @endif
                                                    </form>
                                                    <form
                                                        id="form-delete-{{ $hashid->encode($item->id) }}"
                                                        method="post"
                                                        action="{{ route('fasilitator.hukdis.destroy', ['id' => $hashid->encode($item->id)]) }}"
                                                    >
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                    <button onclick="deleteRow('{{ $hashid->encode($item->id) }}')" tabindex="0" class="dropdown-item">Hapus</button>
                                                </div>
                                            </td>                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">- tidak ada data -</td>
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