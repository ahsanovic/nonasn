@extends('layouts.login')
@section('content')
<div class="h-100">
    <div class="h-100 no-gutters row">
        <div class="d-none d-lg-block col-lg-4">
            <div class="slider-light">
                <div class="slick-slider">
                    <div>
                        <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-plum-plate" tabindex="-1">
                            <div class="slide-img-bg" style="background-image: url('../assets/images/bkd.jpeg');"></div>
                            <div class="slider-content"><h3>Perfect Balance</h3>
                                <p>"Keberhasilan pemerintahan tak lepas dari keunggulan kepegawaian, kedisiplinan, komitmen, dan integritas menjadi pondasi kuat dalam membangun masa depan yang adil dan berkualitas."</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
            <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                <div class="app-logo"></div>
                <h4 class="mb-0">
                    <span class="d-block">selamat datang kembali,</span>
                    <span>silahkan login ke akun anda.</span></h4>
                <div class="divider row"></div>
                <div>
                    <form method="post" action="{{ route('nonasn.login') }}">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="niptt" class="">Nomor Induk</label>
                                    <input name="niptt" id="niptt" type="text" class="form-control @error('niptt') is-invalid @enderror" value="{{ old('niptt') }}">
                                    @error('niptt')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="password" class="">Password</label>
                                    <input name="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="divider row"></div>
                        <div class="d-flex align-items-center">
                            <div class="ml-auto">
                                <button class="btn btn-primary btn-lg" type="submit">Login to Dashboard</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection