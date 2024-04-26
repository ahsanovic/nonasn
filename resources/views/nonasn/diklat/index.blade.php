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
            Diklat/Webinar/Workshop/Bimtek
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Diklat</h5>
                    <a href="{{ route('nonasn.diklat.create') }}" class="btn-square btn btn-sm btn-hover-shine btn-primary mt-3">Tambah</a>
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
                                                    <a href="{{ route('nonasn.diklat.file', ['file' => $item->file]) }}" target="_blank">
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
                                                        href="{{ route('nonasn.diklat.edit', ['id' => $hashId->encode($item->id)]) }}"
                                                        type="button"
                                                        tabindex="0"
                                                        class="dropdown-item"
                                                    >
                                                        Edit
                                                    </a>
                                                    <form
                                                        id="form-delete-{{ $hashId->encode($item->id) }}"
                                                        method="post"
                                                        action="{{ route('nonasn.diklat.destroy', ['id' => $hashId->encode($item->id)]) }}"
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