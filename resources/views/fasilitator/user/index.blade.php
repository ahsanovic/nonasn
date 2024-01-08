@push('scripts')
<script>
    $('#perPage').change(function() {
        $('#form').submit();
    });

    function deleteRow(username) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + username).submit();
            }
        })
    }
</script>
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            User Fasilitator
            <div class="page-title-subheading">User Fasilitator OPD</div>
        </div>
    </x-page-header>
    <x-card>
        <h5 class="card-title">List User Fasilitator OPD</h5>
        <div class="row justify-content-start">
            <div class="col-md-2 mb-2">
                <form id="form" class="form-inline" method="get" action="{{ url()->current() }}">
                    <label class="mr-2">Show</label>
                    <select name="perPage" id="perPage" class="form-control-sm form-control">
                        <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $per_page == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
            <div class="col">
                <div id="filter" data-children=".item">
                    <div class="item">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="mb-2 btn btn-icon btn-hover-shine btn-square btn-sm btn-dark" aria-expanded="false" aria-controls="filter" data-toggle="collapse" href="#filter-data"><i class="fa fa-filter"></i> Filter</button>
                                <a class="mb-2 btn btn-square btn-hover-shine btn-sm btn-danger" href="{{ route('fasilitator.user') }}">Reset</a>
                                <a class="mb-2 btn-sm btn-square btn-hover-shine btn btn-primary" href="{{ route('fasilitator.user.create') }}">Tambah</a>
                                <div data-parent="#filter" id="filter-data" class="collapse">
                                    <form method="get" action="{{ url()->current() }}" class="mb-3">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label>username / nama lengkap</label>
                                                <div class="input-group">
                                                    <input type="text" name="user" id="user" class="form-control form-control-sm">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success btn-sm btn-square btn-hover-shine" type="submit">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="mb-0 table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>No. HP</th>
                        <th>OPD</th>
                        <th>Level</th>
                        <th>Blokir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $key }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->nama_lengkap }}</td>
                            <td>{{ $user->no_telp }}</td>
                            <td>{{ $user->skpd->name ?? '' }}</td>
                            <td>{{ $user->level }}</td>
                            <td>{{ $user->blokir }}</td>
                            <td>
                                <div class="row">
                                    <a
                                        href="{{ route('fasilitator.user.edit', ['username' => $user->username]) }}"
                                        class="btn btn-sm btn-outline-success mr-1"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="edit"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form id="form-delete-{{ $user->username }}" method="post" action="{{ route('fasilitator.user.destroy', $user->username) }}">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="current_page" value="{{ $users->currentPage() }}" />
                                    </form>
                                    <button
                                        onclick="deleteRow('{{ $user->username }}')"
                                        class="btn btn-sm btn-outline-danger delete"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="delete"
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
    </x-card>
    <div class="row">
        <div class="col">
            {{ $users->withQueryString()->links() }}
        </div>
        <div class="col text-right text-muted">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{ $users->total() }} results
        </div>
    </div>
</x-app-layout>