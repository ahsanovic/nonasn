@push('scripts')
<script>
    $('#form').submit(function() {
        $('#btn-submit').hide();
        $('.loader').show();
    })

    function deleteRow(idSkpd) {
        Swal.fire({
            title: 'Yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete-' + idSkpd).submit();
            }
        })
    }
</script>
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            Unit Kerja
            <div class="page-title-subheading">Edit Unit Kerja</div>
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form id="form" method="post" action="{{ route('fasilitator.unit-kerja.update', ['idSkpd' => $skpd->id]) }}">
                        @csrf
                        @method('put')
                        <div class="position-relative form-group">
                            <label for="id-parent" class="font-weight-bold">ID parent</label>
                            <input
                                id="id-parent"
                                type="text"
                                class="form-control form-control-sm"
                                readonly
                                value="<?php
                                    if ($skpd->pId != 0) {
                                        echo $skpd->pId . ' - ' . $skpd_parent->name;
                                    } else {
                                        echo $skpd->pId;
                                    }
                                ?>"
                            >
                        </div>
                        <div class="position-relative form-group">
                            <label for="id" class="font-weight-bold">ID</label>
                            <input
                                id="id"
                                type="text"
                                class="form-control form-control-sm"
                                readonly
                                value="{{ $skpd->id }}"
                            >
                        </div>
                        <div class="position-relative form-group">
                            <label for="name" class="font-weight-bold">Nama Unit Kerja</label>
                            <input id="name" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" type="text" value="{{ $skpd->name }}" />
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <x-button-loader />
                        <button
                            class="mt-1 btn btn-success btn-sm btn-square btn-hover-shine"
                            type="submit"
                            id="btn-submit"
                            >
                            Update
                        </button>
                    </form>
                    <div class="float-right">
                        <form id="form-delete-{{ $skpd->id }}" method="post" action="{{ route('fasilitator.unit-kerja.destroy', ['idSkpd' => $skpd->id]) }}">
                            @csrf
                            @method('delete')
                        </form>
                        <button
                            onclick="deleteRow('{{ $skpd->id }}')"
                            class="btn btn-sm btn-square btn-hover-shine btn-danger delete"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="delete"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>