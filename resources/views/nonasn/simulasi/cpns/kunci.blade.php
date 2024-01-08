<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Simulasi Tes CPNS
        </div>
    </x-page-header-nonasn>

    @php
        $nomor_soal = 1;
    @endphp
    {{-- informasi ujian --}}
    {{-- <div class="row mb-3">
        <div class="col-md-2">
            <div class="alert alert-success fade show text-center" role="alert" id="alert">
                Soal Dijawab: {{ 100 - $jawaban_kosong }}
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
    </div> --}}

    {{-- soal --}}
    <div class="row">
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
                            <form>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
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
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col md-6 lg-12 mt-4 d-flex">
                            <a class="btn btn-sm btn-dark btn-square mr-2 @if ($nomor_sekarang == 1) @php echo 'disabled'; @endphp @endif"
                                href="{{ route('nonasn.simulasi.cpns.kunci', ['ujian' => $ujianId, 'no' => $nomor_sekarang - 1]) }}"><i class="bi bi-chevron-left"></i> Soal Sebelumnya
                            </a>
                            <a class="btn btn-sm btn-danger btn-square @if ($nomor_sekarang == 90) @php echo 'disabled'; @endphp @endif"
                                href="{{ route('nonasn.simulasi.cpns.kunci', ['ujian' => $ujianId, 'no' => $nomor_sekarang + 1]) }}">Soal Berikutnya <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col md-6 lg-12 d-flex">
                            <h5 class="text-danger">
                                Jawaban Anda: 
                                @php
                                    if ($jawaban[$nomor_sekarang-1] == '0') {
                                        echo '-';
                                    } else {
                                        echo $jawaban[$nomor_sekarang-1];
                                    }
                                @endphp
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col md-6 lg-12 d-flex">
                            <h5 class="text-success">Jawaban Benar: {{ $kunci[$nomor_sekarang-1] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- navigasi soal --}}
    @for ($i = 1; $i <= 4; $i++)
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        @for ($j = 1; $j <= 25; $j++)
        <div class="btn-group mr-2 mb-2" role="group" aria-label="First group">
            <a 
                href="{{ route('nonasn.simulasi.cpns.kunci', ['no' => $nomor_soal, 'ujian' => $ujianId]) }}"
                class="btn btn-square btn-<?php 
                    if ($jawaban[$nomor_soal - 1] == '0') {
                        echo 'danger';
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