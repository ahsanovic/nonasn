@push('scripts')
<script>
    function deleteRow(field) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + field).submit();
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
                    <h5 class="card-title">Dokumen Pribadi</h5>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                        <th>Nama Dokumen</th>
                                        <th>Tgl/Jam Edit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($refDokumen as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td>
                                                <?php
                                                    switch ($item->field) {
                                                        case 'file_ktp':
                                                            if ($data->file_ktp) : ?>
                                                                <a href="{{ route('dok-pribadi.file', ['file' => $data->file_ktp]) }}" target="_blank">
                                                                    <i class="lnr-picture icon-gradient bg-grow-early"></i>
                                                                </a>
                                                        <?php endif;
                                                            break;
                                                        case 'file_bpjs':
                                                            if ($data->file_bpjs) : ?>
                                                                <a href="{{ route('dok-pribadi.file', ['file' => $data->file_bpjs]) }}" target="_blank">
                                                                    <i class="lnr-picture icon-gradient bg-grow-early"></i>
                                                                </a>
                                                        <?php endif;
                                                            break;   
                                                        case 'file_bpjs_naker':
                                                            if ($data->file_bpjs_naker) : ?>
                                                                <a href="{{ route('dok-pribadi.file', ['file' => $data->file_bpjs_naker]) }}" target="_blank">
                                                                    <i class="lnr-picture icon-gradient bg-grow-early"></i>
                                                                </a>
                                                        <?php endif;
                                                            break;
                                                    }
                                                ?>
                                            </td>
                                            <td>{{ $item->nama_dokumen }}</td>
                                            <td>
                                                @switch($item->field)
                                                    @case('file_ktp')
                                                        {{ $data->updated_at_file_ktp ?? '' }}
                                                        @break
                                                    @case('file_bpjs')
                                                        {{ $data->updated_at_file_bpjs ?? '' }}
                                                        @break
                                                    @case('file_bpjs_naker')
                                                        {{ $data->updated_at_file_bpjs_naker ?? '' }}
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <a
                                                        href="{{
                                                            route('fasilitator.dok-pribadi.edit', [
                                                                'idSkpd' => $hashidSkpd->encode($skpd->id),
                                                                'id' => $hashidPegawai->encode($pegawai->id_ptt),
                                                                'idDokumen' => $hashid->encode($data->id),
                                                                'field' => $item->field
                                                            ])
                                                        }}"
                                                        class="btn btn-sm btn-outline-success mr-1"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="edit"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form
                                                        id="form-delete-{{ $item->field }}"
                                                        method="post"
                                                        action="{{ route('fasilitator.dok-pribadi.destroy', ['idPtt' => $hashid->encode($pegawai->id_ptt), 'field' => $item->field]) }}"
                                                    >
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                    <button
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteRow('{{ $item->field }}')"
                                                        tabindex="0"
                                                        class="dropdown-item"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
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