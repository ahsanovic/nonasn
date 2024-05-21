<x-app-layout>
    <x-page-header>
        <div>
            Dashboard
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-5">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-license icon-gradient bg-love-kiss"> </i>Leaderboard Simulasi CPNS
                </div>
                <div class="card-body">
                    <div class="widget-title opacity-5 text-muted text-uppercase mb-3">TOP TEN LIST</div>
                    <ul class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">
                        @php
                            $position = 1;
                        @endphp
                        @foreach ($hasil_simulasi_cpns as $item)
                            <li class="list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <img width="38" class="rounded-circle" src="{{ route('pegawai.image', ['image' => rtrim($item->pegawai->foto)]) }}" alt="">
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $item->pegawai->nama }}</div>
                                            <div class="widget-subheading">
                                                @php
                                                    $skpd = substr($item->pegawai->id_skpd, 0, 3);
                                                @endphp
                                                {{ Str::title(App\Models\Skpd::whereId($skpd)->first()->name) }}
                                            </div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="font-size-xlg text-muted">
                                                @if ($position <= 3)
                                                <small class="opacity-5 pr-1"><i class="pe-7s-cup"></i></small>
                                                @endif
                                                <span>{{ $item->nilai_total }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @php
                                $position = $position + 1;
                            @endphp
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-5">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-license icon-gradient bg-happy-itmeo"> </i> Leaderboard Simulasi PPPK
                    <div class="btn-actions-pane-right actions-icon-btn">
                        <div role="group" class="btn-group-sm nav btn-group">
                            <a data-toggle="tab" href="#tab-eg3-0" class="btn-shadow btn btn-success active show">Teknis</a>
                            <a data-toggle="tab" href="#tab-eg3-1" class="btn-shadow btn btn-success show">Manajerial</a>
                            <a data-toggle="tab" href="#tab-eg3-2" class="btn-shadow btn btn-success show">Wawancara</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="widget-title opacity-5 text-muted text-uppercase mb-3">TOP TEN LIST</div>
                        <div class="tab-pane show active" id="tab-eg3-0" role="tabpanel">
                            <ul class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">
                            @php
                                $position = 1;
                            @endphp
                            @foreach ($hasil_simulasi_teknis as $item)
                                <li class="list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-3">
                                                <img width="38" class="rounded" src="{{ route('nonasn.image', ['image' => rtrim($item->pegawai->foto)]) }}" alt="">
                                            </div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">{{ $item->pegawai->nama }}</div>
                                                <div class="widget-subheading">
                                                    @php
                                                        $skpd = substr($item->pegawai->id_skpd, 0, 3);
                                                    @endphp
                                                    {{ Str::title(App\Models\Skpd::whereId($skpd)->first()->name) }}
                                                </div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="font-size-xlg text-muted">
                                                    @if ($position <= 3)
                                                    <small class="opacity-5 pr-1"><i class="pe-7s-cup"></i></small>
                                                    @endif
                                                    <span>{{ $item->nilai_total }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $position = $position + 1;
                                @endphp
                            @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane show" id="tab-eg3-1" role="tabpanel">
                            <ul class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">
                            @php
                                $position = 1;
                            @endphp
                            @foreach ($hasil_simulasi_manajerial as $item)
                                <li class="list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-3">
                                                <img width="38" class="rounded" src="{{ route('nonasn.image', ['image' => rtrim($item->pegawai->foto)]) }}" alt="">
                                            </div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">{{ $item->pegawai->nama }}</div>
                                                <div class="widget-subheading">
                                                    @php
                                                        $skpd = substr($item->pegawai->id_skpd, 0, 3);
                                                    @endphp
                                                    {{ Str::title(App\Models\Skpd::whereId($skpd)->first()->name) }}
                                                </div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="font-size-xlg text-muted">
                                                    @if ($position <= 3)
                                                    <small class="opacity-5 pr-1"><i class="pe-7s-cup"></i></small>
                                                    @endif
                                                    <span>{{ $item->nilai_total }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $position = $position + 1;
                                @endphp
                            @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane show" id="tab-eg3-2" role="tabpanel">
                            <ul class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">
                            @php
                                $position = 1;
                            @endphp
                            @foreach ($hasil_simulasi_wawancara as $item)
                                <li class="list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-3">
                                                <img width="38" class="rounded" src="{{ route('nonasn.image', ['image' => rtrim($item->pegawai->foto)]) }}" alt="">
                                            </div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">{{ $item->pegawai->nama }}</div>
                                                <div class="widget-subheading">
                                                    @php
                                                        $skpd = substr($item->pegawai->id_skpd, 0, 3);
                                                    @endphp
                                                    {{ Str::title(App\Models\Skpd::whereId($skpd)->first()->name) }}
                                                </div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="font-size-xlg text-muted">
                                                    @if ($position <= 3)
                                                    <small class="opacity-5 pr-1"><i class="pe-7s-cup"></i></small>
                                                    @endif
                                                    <span>{{ $item->nilai_total }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $position = $position + 1;
                                @endphp
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="main-card mb-3 card">
                <div class="card-body"><h5 class="card-title">Berita Terbaru Web BKD Jatim</h5>
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
    </div>
</x-app-layout>
















