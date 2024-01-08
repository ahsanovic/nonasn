@push('scripts')
<script>
    $('#form').submit(function() {
        $('#btn-start').hide();
        $('#btn-history').hide();
        $('.loader').show();
    })
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Simulasi Tes CPNS
        </div>
    </x-page-header-nonasn>
    <div class="row mt-4 d-flex justify-content-center">
        <div class="col-lg-4 col-md-4">
            <div class="card main-card mb-3">
                <div class="card-header">
                    Nilai Simulasi Tes CPNS
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled text-left" style="font-weight: 500;">
                                <li>Tes Wawasan Kebangsaan</li>
                                <li>Tes Intelegensia Umum</li>
                                <li>Tes Karakteristik Pribadi</li>
                            </ul>
                        </div>
                        <div class="col-1">
                            <ul class="list-unstyled text-left">
                                <li>:</li>
                                <li>:</li>
                                <li>:</li>
                            </ul>
                        </div>
                        <div class="col-2">
                            <ul class="list-unstyled text-right">
                                <li>{{ $hasil->nilai_twk }}</li>
                                <li>{{ $hasil->nilai_tiu }}</li>
                                <li>{{ $hasil->nilai_tkp }}</li>
                            </ul>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled text-left" style="font-weight: 500;">
                                <li>Total Nilai</li>
                            </ul>
                        </div>
                        <div class="col-1">
                            <ul class="list-unstyled text-left" style="font-weight: 500;">
                                <li>:</li>
                            </ul>
                        </div>
                        <div class="col-2">
                            <ul class="list-unstyled text-right">
                                <li>{{ $hasil->nilai_twk + $hasil->nilai_tiu + $hasil->nilai_tkp }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-2">
        <x-button-loader />
        <form id="form" method="post" action="{{ route('nonasn.simulasi.cpns.store') }}">
            @csrf
            <button id="btn-start" class="btn btn-square btn-danger btn-hover-shine">Coba Lagi</button>
        </form>
        <a href="{{ route('nonasn.simulasi.cpns') }}" id="btn-history" class="btn btn-square btn-hover-shine btn-success ml-2">Riwayat Nilai</a>
    </div>
</x-app-layout>