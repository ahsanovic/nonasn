<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Dokumen
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-12">
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
                                    @if (auth()->user()->jenis_ptt_id == 5)
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>
                                                <?php if ($data->file_ktp) : ?>
                                                    <a href="{{ route('nonasn.dok-pribadi.file', ['file' => $data->file_ktp]) }}" target="_blank">
                                                        <i class="lnr-picture icon-gradient bg-grow-early"></i>
                                                    </a>
                                            <?php endif; ?>
                                            </td>
                                            <td>KTP</td>
                                            <td>{{ $data->updated_at_file_ktp ?? '' }}</td>
                                            <td>
                                                <div class="row">
                                                    <a
                                                        href="{{
                                                            route('nonasn.dok-pribadi.edit', [
                                                                'id' => $hashId->encode($data->id),
                                                                'field' => 'file_ktp'
                                                            ])
                                                        }}"
                                                        class="btn btn-sm btn-outline-success mr-1"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="edit"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($refDokumen as $item)
                                            <tr>
                                                <td scope="row">{{ $loop->iteration }}</td>
                                                <td>
                                                    <?php
                                                        switch ($item->field) {
                                                            case 'file_ktp':
                                                                if ($data->file_ktp) : ?>
                                                                    <a href="{{ route('nonasn.dok-pribadi.file', ['file' => $data->file_ktp]) }}" target="_blank">
                                                                        <i class="lnr-picture icon-gradient bg-grow-early"></i>
                                                                    </a>
                                                            <?php endif;
                                                                break;
                                                            case 'file_bpjs':
                                                                if ($data->file_bpjs) : ?>
                                                                    <a href="{{ route('nonasn.dok-pribadi.file', ['file' => $data->file_bpjs]) }}" target="_blank">
                                                                        <i class="lnr-picture icon-gradient bg-grow-early"></i>
                                                                    </a>
                                                            <?php endif;
                                                                break;   
                                                            case 'file_bpjs_naker':
                                                                if ($data->file_bpjs_naker) : ?>
                                                                    <a href="{{ route('nonasn.dok-pribadi.file', ['file' => $data->file_bpjs_naker]) }}" target="_blank">
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
                                                                route('nonasn.dok-pribadi.edit', [
                                                                    'id' => $hashId->encode($data->id),
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
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>