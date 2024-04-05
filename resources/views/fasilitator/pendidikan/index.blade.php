@push('scripts')
<script>
    function deleteRow(id_ptt_pendidikan) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + id_ptt_pendidikan).submit();
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
                    <h5 class="card-title">Riwayat Pendidikan</h5>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-danger fade show" role="alert">
                                Pastikan data pendidikan terakhir (sesuai di SK Kontrak) diaktifkan dengan menekan tombol aksi kemudian "Aktifkan"
                            </div>    
                        </div>    
                    </div>
                    <a href="{{
                        route('fasilitator.pendidikan.create-sma', [
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
                                        <th>File Ijazah</th>
                                        <th>File Nilai</th>
                                        <th>Jenjang</th>
                                        <th>Nama Sekolah</th>
                                        <th>Jurusan</th>
                                        <th>Tahun Lulus</th>
                                        <th>Aktif</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            @if ($item->id_jenjang == 1 || $item->id_jenjang == 2 || $item->id_jenjang == 3)
                                                <td>
                                                    @if ($item->file_ijazah_sma)
                                                        <a href="{{ route('pendidikan.file-ijazah-sma', ['file' => $item->file_ijazah_sma]) }}" target="_blank">
                                                            <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->file_nilai_sma)
                                                        <a href="{{ route('pendidikan.file-transkrip-sma', ['file' => $item->file_nilai_sma]) }}" target="_blank">
                                                            <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{ $item->jenjang->nama_jenjang }}</td>
                                                <td>{{ $item->nama_sekolah_sma }}</td>
                                                <td>{{ $item->jurusan_sma }}</td>
                                                <td>{{ $item->thn_lulus_sma }}</td>
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
                                                                route('fasilitator.pendidikan.edit-sma', [
                                                                    'idSkpd' => $hashidSkpd->encode($skpd->id),
                                                                    'id' => $hashidPegawai->encode($pegawai->id_ptt),
                                                                    'idPendidikan' => $hashid->encode($item->id_ptt_pendidikan)
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
                                                                route('fasilitator.pendidikan.activate-sma', [
                                                                    'id' => $hashid->encode($item->id_ptt_pendidikan),
                                                                ])
                                                            }}"
                                                        >
                                                            @csrf
                                                            @method('put')
                                                            <input type="hidden" name="id_pegawai" value="{{ $hashidPegawai->encode($pegawai->id_ptt) }}">
                                                            <input type="hidden" name="jenjang_sma" value="{{ $hashidJenjang->encode($item->id_jenjang) }}">
                                                            <input type="hidden" name="nama_sekolah_sma" value="{{ $item->nama_sekolah_sma }}">
                                                            <input type="hidden" name="jurusan_sma" value="{{ $item->jurusan_sma }}">
                                                            <input type="hidden" name="akreditasi_sma" value="{{ $item->akreditasi_sma }}">
                                                            <input type="hidden" name="thn_lulus_sma" value="{{ $item->thn_lulus_sma }}">
                                                            @if ($item->aktif == 'N')
                                                                <button type="submit" tabindex="0" class="dropdown-item">Aktifkan</button>
                                                            @endif
                                                        </form>
                                                        <form
                                                            id="form-delete-{{ $hashid->encode($item->id_ptt_pendidikan) }}"
                                                            method="post"
                                                            action="{{ route('fasilitator.pendidikan.destroy-sma', ['id' => $hashid->encode($item->id_ptt_pendidikan)]) }}"
                                                        >
                                                            @csrf
                                                            @method('delete')
                                                        </form>
                                                        <button onclick="deleteRow('{{ $hashid->encode($item->id_ptt_pendidikan) }}')" tabindex="0" class="dropdown-item">Hapus</button>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    @if ($item->file_ijazah_pt)
                                                        <a href="{{ route('pendidikan.file-ijazah-pt', ['file' => $item->file_ijazah_pt]) }}" target="_blank">
                                                            <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->file_nilai_pt)
                                                        <a href="{{ route('pendidikan.file-transkrip-pt', ['file' => $item->file_nilai_pt]) }}" target="_blank">
                                                            <i class="lnr-file-empty icon-gradient bg-love-kiss"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{ $item->jenjang->nama_jenjang }}</td>
                                                <td>{{ $item->nama_pt }}</td>
                                                <td>{{ $item->jurusan_prodi_pt }}</td>
                                                <td>{{ $item->thn_lulus_pt }}</td>
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
                                                                route('fasilitator.pendidikan.edit-pt', [
                                                                    'idSkpd' => $hashidSkpd->encode($skpd->id),
                                                                    'id' => $hashidPegawai->encode($pegawai->id_ptt),
                                                                    'idPendidikan' => $hashid->encode($item->id_ptt_pendidikan)
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
                                                                route('fasilitator.pendidikan.activate-pt', [
                                                                    'id' => $hashid->encode($item->id_ptt_pendidikan),
                                                                ])
                                                            }}"
                                                        >
                                                            @csrf
                                                            @method('put')
                                                            <input type="hidden" name="id_pegawai" value="{{ $hashidPegawai->encode($pegawai->id_ptt) }}">
                                                            <input type="hidden" name="jenjang_pt" value="{{ $hashidJenjang->encode($item->id_jenjang) }}">
                                                            <input type="hidden" name="nama_pt" value="{{ $item->nama_pt }}">
                                                            <input type="hidden" name="jurusan_prodi_pt" value="{{ $item->jurusan_prodi_pt }}">
                                                            <input type="hidden" name="akreditasi_pt" value="{{ $item->akreditasi_pt }}">
                                                            <input type="hidden" name="thn_lulus_pt" value="{{ $item->thn_lulus_pt }}">
                                                            @if ($item->aktif == 'N')
                                                                <button type="submit" tabindex="0" class="dropdown-item">Aktifkan</button>
                                                            @endif
                                                        </form>
                                                        <form
                                                            id="form-delete-{{ $hashid->encode($item->id_ptt_pendidikan) }}"
                                                            method="post"
                                                            action="{{ route('fasilitator.pendidikan.destroy-pt', ['id' => $hashid->encode($item->id_ptt_pendidikan)]) }}"
                                                        >
                                                            @csrf
                                                            @method('delete')
                                                        </form>
                                                        <button onclick="deleteRow('{{ $hashid->encode($item->id_ptt_pendidikan) }}')" tabindex="0" class="dropdown-item">Hapus</button>
                                                    </div>
                                                </td>
                                            @endif
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