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
                    <h5 class="card-title">Edit Pendidikan</h5>
                    <div class="mt-4">
                        <form
                            id="form"
                            method="post"
                            action="{{
                                route('fasilitator.pendidikan.update-pt', [
                                    'idSkpd' => $hashidSkpd->encode($skpd->id),
                                    'id' => $hashidPegawai->encode($pegawai->id_ptt),
                                    'idPendidikan' => $hashid->encode($data->id_ptt_pendidikan)
                                ])
                            }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @method('put')
                            @include('fasilitator.pendidikan._form_pt')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>