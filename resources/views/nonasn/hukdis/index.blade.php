<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Data Hukuman Disiplin
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Hukuman Disiplin</h5>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->file_hukdis)
                                                    <a href="{{ route('nonasn.hukdis.file', ['file' => $item->file_hukdis]) }}" target="_blank">
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