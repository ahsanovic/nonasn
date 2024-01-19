@push('scripts')
<script>
    function deleteRow(suami_istri_id) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + suami_istri_id).submit();
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
                    <h5 class="card-title">Riwayat Suami/Istri</h5>
                    <a
                        href="{{ route('fasilitator.suami-istri.create', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
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
                                        <th>File BPJS</th>
                                        <th>Nama Suami/Istri</th>
                                        <th>Status</th>
                                        <th>Tempat/Tgl Lahir</th>
                                        <th>Pekerjaan</th>
                                        <th>Instansi Tempat Bekerja</th>
                                        <th>Aktif</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td align="center">
                                                @if ($item->file_bpjs)
                                                <a href="{{ route('suami-istri.file', ['file' => $item->file_bpjs]) }}" target="_blank">
                                                    <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                </a>
                                                @endif
                                            </td>
                                            <td>{{ $item->nama_suami_istri }}</td>
                                            <td>{{ $item->refSuamiIstri->status_suami_istri ?? '' }}</td>
                                            <td>{{ $item->tempat_lahir . ', ' . $item->tgl_lahir->format('d M Y') }}</td>
                                            <td>{{ $item->pekerjaan->pekerjaan ?? '' }}</td>
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
                                                        href="{{ 
                                                        route('fasilitator.suami-istri.edit', [
                                                                'idSkpd' => $hashidSkpd->encode($skpd->id), 
                                                                'id' => $hashidPegawai->encode($pegawai->id_ptt), 
                                                                'idSuamiIstri' => $hashid->encode($item->suami_istri_id)
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
                                                        action="{{ route('fasilitator.suami-istri.activate', ['id' => $hashid->encode($item->suami_istri_id)]) }}"
                                                    >
                                                        @csrf
                                                        @method('put')
                                                        <input type="hidden" name="id_pegawai" value="{{ $hashidPegawai->encode($pegawai->id_ptt) }}">
                                                        @if ($item->aktif == 'N')
                                                            <button type="submit" tabindex="0" class="dropdown-item">Aktifkan</button>
                                                        @endif
                                                    </form>
                                                    <form
                                                        id="form-delete-{{ $hashid->encode($item->suami_istri_id) }}"
                                                        method="post"
                                                        action="{{ route('fasilitator.suami-istri.destroy', ['id' => $hashid->encode($item->suami_istri_id)]) }}"
                                                    >
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                    <button onclick="deleteRow('{{ $hashid->encode($item->suami_istri_id) }}')" tabindex="0" class="dropdown-item">Hapus</button>
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