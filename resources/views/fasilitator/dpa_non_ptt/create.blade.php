@push('scripts')
<!--Datepickers-->
<script src="{{ asset('assets/js/vendors/form-components/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/scripts-init/form-components/datepicker.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#form').submit(function() {
            $('#btn-submit, #btn-cancel').hide();
            $('.loader').show();
        });

        var productIndex = 1;

        $('#add-another').click(function() {
            var newProduct = `
                <div class="dynamic-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="kode-rekening" class="font-weight-bold">Kode Rekening</label>
                                <input
                                    name="dpa[${productIndex}][kode_rekening]"
                                    id="kode-rekening"
                                    type="text"
                                    class="form-control form-control-sm @error('dpa[${productIndex}][kode_rekening]') is-invalid @enderror"
                                    value="{{ old('dpa[${productIndex}][kode_rekening]') }}"
                                >
                                @error('dpa[${productIndex}][kode_rekening]')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="jml-pegawai" class="font-weight-bold">Jumlah Pegawai</label>
                                <input
                                    name="dpa[${productIndex}][jml_pegawai]"
                                    id="jml-pegawai"
                                    type="text"
                                    class="form-control form-control-sm @error('dpa[${productIndex}][jml_pegawai]') is-invalid @enderror"
                                    value="{{ old('dpa[${productIndex}][jml_pegawai]') }}"
                                >
                                @error('dpa[${productIndex}][jml_pegawai]')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center mt-2">
                            <button type="button" class="btn btn-sm btn-danger remove-form">Hapus Form</button>
                        </div>
                    </div>
                </div> 
            `;

            $('#wrapper').append(newProduct);
            productIndex++;
        });

        $(document).on('click', '.remove-form', function() {
            $(this).closest('.dynamic-group').remove();
        });
    });
</script>
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            Data DPA
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Tambah DPA</h5>
                    <div class="mt-4">
                        <form
                            id="form"
                            method="post"
                            action="{{ route('fasilitator.dpanonptt.store') }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @include('fasilitator.dpa_non_ptt._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>