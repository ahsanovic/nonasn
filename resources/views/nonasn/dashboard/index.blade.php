<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Dashboard
        </div>
    </x-page-header-nonasn>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-success fade show" role="alert">
                Kamu punya unek-unek, keluhan, curhatan, ataupun pengen diskusi seputar kepegawaian? coba aja sampaikan ke Kami melalui aplikasi
                <a href="https://siasn.bkd.jatimprov.go.id/helpdesk" target="_blank" class="alert-link">RUMAH ASN</a> (Ruang Menjawab Keluhan ASN).
                Banyak fitur yang seru dan menarik lhoo di dalamnya, mulai dari forum diskusi, podcast, kuis kepegawaian, dan masih banyak lagi...
                so, jangan ragu buat mencobanya!
            </div>    
        </div>    
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="main-card mb-3 card">
                <div class="card-body"><h5 class="card-title">Berita Terbaru BKD Jatim</h5>
                    @if ($response->code != 500)
                    <div class="scroll-area">
                        <div class="scrollbar-container ps ps--active-y">
                            <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                @foreach ($response->results as $item)
                                    @php
                                        $tgl_publish = date('d-m-Y', strtotime($item->tanggal));
                                        $tgl_slug = date('Y/m/d', strtotime($item->tanggal));
                                    @endphp
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div>
                                            <span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-danger"> </i></span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <a href="{{ $web_url . 'berita/detail/' . $tgl_slug . '/' . $item->judul_seo }}" target="_blank">
                                                    <h4 class="timeline-title">{{ $item->judul }}</h4>
                                                </a>
                                                <p>{{ $item->hari . ', ' . $tgl_publish }}</p><span class="vertical-timeline-element-date">{{ $item->jam }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach                                    
                            </div>                                
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px; height: 400px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 176px;"></div></div></div>
                    </div>
                    @else
                    <div>
                        <span class="text text-danger">something wrong with api request</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="main-card mb-3 card">
                <div class="card-body"><h5 class="card-title">Notifikasi</h5>
                    @if ($notif->no_bpjs == null || $notif->kelas == null)
                        <div class="alert alert-danger fade show" role="alert">
                            Data BPJS Anda belum lengkap! segera lengkapi data <a href="{{ route('nonasn.biodata') }}" class="alert-link">disini</a>
                        </div>
                    @endif
                    @if ($notif->jenjang == null)
                        <div class="alert alert-danger fade show" role="alert">
                            Data Pendidikan Anda belum lengkap/belum diaktifkan! segera lengkapi data <a href="{{ route('nonasn.pendidikan-sma') }}" class="alert-link">disini</a>
                        </div>
                    @endif
                    @if ($notif->jabatan == null)
                        <div class="alert alert-danger fade show" role="alert">
                            Data Jabatan Anda belum lengkap! segera lengkapi data <a href="{{ route('nonasn.jabatan') }}" class="alert-link">disini</a>
                        </div>
                    @endif
                    @if ($notif_doc->file_ktp == null || $notif_doc->file_bpjs == null)
                        <div class="alert alert-danger fade show" role="alert">
                            Dokumen Pribadi Anda belum lengkap! segera lengkapi <a href="{{ route('nonasn.dok-pribadi') }}" class="alert-link">disini</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
















