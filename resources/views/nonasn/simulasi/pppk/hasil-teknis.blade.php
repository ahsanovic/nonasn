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
            Simulasi Tes PPPK
        </div>
    </x-page-header-nonasn>
    <div class="row mt-4 d-flex justify-content-center">
        <div class="col-lg-4 col-md-4">
            <div class="card main-card mb-3">
                <div class="card-header">
                    Nilai Simulasi Tes PPPK Teknis
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled text-left" style="font-weight: 500;">
                                <li>Tes Kompetensi Teknis</li>
                            </ul>
                        </div>
                        <div class="col-1">
                            <ul class="list-unstyled text-left">
                                <li>:</li>
                            </ul>
                        </div>
                        <div class="col-2">
                            <ul class="list-unstyled text-right">
                                <li>{{ $hasil->nilai_total }}</li>
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
                                <li>{{ $hasil->nilai_total }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-2">
        <x-button-loader />
        <form id="form" method="post" action="{{ route('nonasn.simulasi.pppk.store-teknis') }}">
            @csrf
            <input type="hidden" name="jabatan_id" value="{{ $jabatan->jabatan_simulasi_id }}">
            <button id="btn-start" class="btn btn-square btn-danger btn-hover-shine">Coba Lagi</button>
        </form>
        <a href="{{ route('nonasn.simulasi.pppk') }}" id="btn-history" class="btn btn-square btn-hover-shine btn-success ml-2">Riwayat Nilai</a>
    </div>
</x-app-layout>