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
            Data Pendidikan
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Tambah Pendidikan</h5>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a
                                href="{{ route('nonasn.pendidikan.create-sma') }}"
                                class="nav-link show {{ Request::segment(3) == 'sma' ? 'active' : '' }}"
                            >
                                SD - SMA
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                href="{{ route('nonasn.pendidikan.create-pt') }}"
                                class="nav-link show {{ Request::segment(3) == 'pt' ? 'active' : '' }}"
                            >
                                Perguruan Tinggi
                            </a>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <form
                            id="form"
                            method="post"
                            action="{{ route('nonasn.pendidikan.store-pt') }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @include('nonasn.pendidikan._form_pt')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>