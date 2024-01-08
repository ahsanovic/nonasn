@push('scripts')
<!--Datepickers-->
<script src="{{ asset('assets/js/vendors/form-components/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/scripts-init/form-components/datepicker.js') }}"></script>
<script>
    $('#form').submit(function() {
        $('#btn-submit, #btn-cancel').hide();
        $('.loader').show();
    })
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Data Anak
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Tambah Anak</h5>
                    <div class="mt-4">
                        <form
                            id="form"
                            method="post"
                            action="{{ route('nonasn.anak.store') }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @include('nonasn.anak._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>