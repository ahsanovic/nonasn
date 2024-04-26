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
        <div class="col-md-4">
            @include('_include.profile-card')
        </div>
        <div class="col-md-8">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Diklat</h5>
                    <a href="{{
                        route('fasilitator.diklat.create', [
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
                                        <th>Jenis Diklat</th>
                                        <th>Nama Diklat</th>
                                        <th>Nomor/Tgl Sertifikat</th>
                                        <th>Tgl Pelaksanaan</th>
                                        <th>Penyelenggara</th>
                                        <th>Jumlah Jam</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->file)
                                                    <a href="{{ route('fasilitator.diklat.file', ['file' => $item->file]) }}" target="_blank">
                                                        <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $item->jenisDiklat->jenis_diklat }}</td>
                                            <td>{{ $item->nama_diklat }}</td>
                                            <td>{{ $item->no_sertifikat }} <br/> {{ $item->tgl_sertifikat }}</td>
                                            <td>
                                                @if ($item->tgl_mulai == $item->tgl_selesai)
                                                    {{ $item->tgl_mulai }}
                                                @else
                                                    {{ $item->tgl_mulai }} s.d. {{ $item->tgl_selesai }}
                                                @endif
                                            </td>
                                            <td>{{ $item->penyelenggara }}</td>
                                            <td>{{ $item->jml_jam ?? '' }}</td>
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
                                                            route('fasilitator.diklat.edit', [
                                                                'idSkpd' => $hashidSkpd->encode($skpd->id),
                                                                'id' => $hashidPegawai->encode($pegawai->id_ptt),
                                                                'idDiklat' => $hashid->encode($item->id)
                                                            ])
                                                        }}"
                                                        type="button"
                                                        tabindex="0"
                                                        class="dropdown-item"
                                                    >
                                                        Edit
                                                    </a>
                                                    <form
                                                        id="form-delete-{{ $hashid->encode($item->id) }}"
                                                        method="post"
                                                        action="{{ route('fasilitator.diklat.destroy', ['id' => $hashid->encode($item->id)]) }}"
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