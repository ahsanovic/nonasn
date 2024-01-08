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
                    <h5 class="card-title">Tambah Pendidikan</h5>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a
                                href="{{
                                    route('fasilitator.pendidikan.create-sma', [
                                        'idSkpd' => $hashidSkpd->encode($skpd->id),
                                        'id' => $hashidPegawai->encode($pegawai->id_ptt)
                                    ])
                                }}"
                                class="nav-link show {{ Request::segment(7) == 'sma' ? 'active' : '' }}"
                            >
                                SD - SMA
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                href="{{
                                    route('fasilitator.pendidikan.create-pt', [
                                        'idSkpd' => $hashidSkpd->encode($skpd->id),
                                        'id' => $hashidPegawai->encode($pegawai->id_ptt)
                                    ])
                                }}"
                                class="nav-link show {{ Request::segment(7) == 'pt' ? 'active' : '' }}"
                            >
                                Perguruan Tinggi
                            </a>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <form
                            id="form"
                            method="post"
                            action="{{
                                route('fasilitator.pendidikan.store-pt', [
                                    'idSkpd' => $hashidSkpd->encode($skpd->id),
                                    'id' => $hashidPegawai->encode($pegawai->id_ptt)
                                ])
                            }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @include('fasilitator.pendidikan._form_pt')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>