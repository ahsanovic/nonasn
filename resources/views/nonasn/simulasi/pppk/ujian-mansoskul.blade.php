@push('scripts')
<script>
    var waktuJS = new Date({{$waktu}} * 1000).getTime();
    // var waktuJS = <?php echo $waktu ?> * 1000;
    // var now = <?php echo time() ?> * 1000;
    var waktuJS = waktuJS + 2400000;

    // Update the count down every 1 second
    var x = setInterval(function() {
        // Get todays date and time
        // 1. JavaScript
        var now = new Date().getTime();
        // 2. PHP
        // now = now + 1000;

        // Find the distance between now an the count down date
        var distance = waktuJS - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        $('.time').html(('0' + hours).slice(-2) + " : " + ('0' + minutes).slice(-2) + " : " + ('0' + seconds).slice(-2) + "");

        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            $('.time').html('Waktu Habis');
            timeout({{ $id_ujian }})
            $('#btn-save').remove()
            $('#btn-skip').remove()
        }
    }, 1000);
</script>

<script>
    function timeout(id) {
        Swal.fire({
            title: 'Waktu habis',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Akhiri Ujian'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-finish').submit()
            }
        })
    }
    
    function finish(id) {
        Swal.fire({
            title: 'Yakin akan mengakhiri ujian?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Akhiri Ujian'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-finish').submit()
            }
        })
    }

    $('#form-save').submit(function() {
        $('#btn-save').prop('disabled', true)
    })
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Simulasi Tes PPPK - Manajerial / Sosio Kultural
        </div>
    </x-page-header-nonasn>

    @php
        $nomor_soal = 1;
    @endphp
    {{-- informasi ujian --}}
    <div class="row mb-2">
        <div class="col-md-2">
            <div class="alert alert-success fade show text-center" role="alert" id="alert">
                Soal Dijawab: {{ 45 - $jawaban_kosong }}
            </div>
        </div>
        <div class="col-md-2">
            <div class="alert alert-danger fade show text-center" role="alert" id="alert">
                Belum Dijawab: {{ $jawaban_kosong }}
            </div>
        </div>
        <div class="col-md-2">
            <div class="alert alert-dark fade show text-center" role="alert" id="alert">
                <strong><span class="time"></span></strong>
            </div>
        </div>
        <div class="col-md-2">
            <form
                id="form-finish"
                method="post"
                action="{{ route('nonasn.simulasi.pppk.destroy-mansoskul', ['idUjian' => $id_ujian]) }}"
            >
                @csrf
                @method('delete')
            </form>
            <button
                onclick="finish('{{ $id_ujian }}')"
                tabindex="0"
                class="btn-dark btn"
                style="height: 46px;"
            >
                Selesai
            </button>
        </div>
    </div>

    {{-- soal --}}
    <div class="row">`
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <h5>Soal No. {{ $nomor_sekarang }}</h5>
                        </div>
                        <div class="col-md-12 col-lg-12 mb-3">
                            @if (preg_match("/<img/i", $soal))
                                {!!$soal!!}
                            @else 
                                {{ strip_tags(html_entity_decode($soal)) }}
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-12">
                            <form id="form-save" method="post" action="{{ route('nonasn.simulasi.pppk.update-mansoskul', $nomor_sekarang) }}">
                                @method('put')
                                @csrf
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
                                        name="opsi" 
                                        value="A" 
                                        id="opsi1"
                                        @if ($jawaban[$nomor_sekarang-1] == 'A')
                                            @php
                                            echo 'checked';
                                            @endphp
                                        @endif
                                    >
                                    <label class="form-check-label" for="opsi1">
                                        @if (preg_match("/<img/i", $opsi1))
                                            A. {!!$opsi1!!}
                                        @else 
                                            A. {{ strip_tags(html_entity_decode($opsi1)) }}
                                        @endif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input"
                                        type="radio"
                                        name="opsi"
                                        value="B"
                                        id="opsi2"
                                        @if ($jawaban[$nomor_sekarang-1] == 'B')
                                            @php
                                            echo 'checked';
                                            @endphp
                                        @endif
                                    >
                                    <label class="form-check-label" for="opsi2">
                                        @if (preg_match("/<img/i", $opsi2))
                                            B. {!!$opsi2!!}
                                        @else 
                                            B. {{ strip_tags(html_entity_decode($opsi2)) }}
                                        @endif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="opsi"
                                        value="C"
                                        id="opsi3"
                                        @if ($jawaban[$nomor_sekarang-1] == 'C')
                                            @php
                                            echo 'checked';
                                            @endphp
                                        @endif
                                    >
                                    <label class="form-check-label" for="opsi3">
                                        @if (preg_match("/<img/i", $opsi3))
                                            C. {!!$opsi3!!}
                                        @else 
                                            C. {{ strip_tags(html_entity_decode($opsi3)) }}
                                        @endif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="opsi"
                                        value="D"
                                        id="opsi4"
                                        @if ($jawaban[$nomor_sekarang-1] == 'D')
                                            @php
                                            echo 'checked';
                                            @endphp
                                        @endif
                                    >
                                    <label class="form-check-label" for="opsi4">
                                        @if (preg_match("/<img/i", $opsi4))
                                            D. {!!$opsi4!!}
                                        @else 
                                            D. {{ strip_tags(html_entity_decode($opsi4)) }}
                                        @endif
                                    </label>
                                </div>
                                @if ($nomor_sekarang > 25)
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="opsi"
                                        value="E"
                                        id="opsi5"
                                        @if ($jawaban[$nomor_sekarang-1] == 'E')
                                            @php
                                            echo 'checked';
                                            @endphp
                                        @endif
                                    >
                                    <label class="form-check-label" for="opsi5">
                                        @if (preg_match("/<img/i", $opsi5))
                                            E. {!!$opsi5!!}
                                        @else 
                                            E. {{ strip_tags(html_entity_decode($opsi5)) }}
                                        @endif
                                    </label>
                                </div>
                                @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col md-6 lg-12 mt-4 d-flex">
                                <button 
                                    type="submit" 
                                    class="btn btn-square btn-sm btn-success mr-2" 
                                    id="btn-save">Simpan & Lanjutkan
                                </button>
                            </form>
                            <a 
                                class="btn btn-sm btn-square btn-danger <?php if ($nomor_sekarang == 45) echo 'disabled'; ?>"
                                id="btn-skip"
                                href="{{ route('nonasn.simulasi.pppk.show-mansoskul', ['id' => $nomor_sekarang + 1]) }}">Lewati Soal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- navigasi soal --}}
    @for ($i = 1; $i <= 3; $i++)
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        @for ($j = 1; $j <= 15; $j++)
        <div class="btn-group mr-2 mb-2" role="group" aria-label="First group">
            <a 
                href="{{ route('nonasn.simulasi.pppk.show-mansoskul', ['id' => $nomor_soal]) }}"
                class="btn btn-square btn-<?php 
                    if ($jawaban[$nomor_soal - 1] == '0') {
                        echo 'danger'; 
                        $jawaban_kosong++; 
                    } else {
                        echo 'success text-white';
                    }
                    ?> btn-block" style="width: 40px;">{{ $nomor_soal++ }}
            </a>
        </div>
        @endfor
    </div>
    @endfor
</x-app-layout>