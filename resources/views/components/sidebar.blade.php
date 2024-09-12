<div>
    <div class="app-sidebar sidebar-shadow">
        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
            <span>
                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
        </div>    
        <div class="scrollbar-sidebar">
            <div class="app-sidebar__inner">
                <ul class="vertical-nav-menu">
                    <li class="app-sidebar__heading">Menu</li>
                    <li>
                        <a href="{{ route('fasilitator.dashboard') }}" class="{{ Request::is('fasilitator/dashboard') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph2">
                            </i>Dashboard
                        </a>
                    </li>

                    <li class="app-sidebar__heading">Pegawai</li>
                    @if (auth()->user()->level == 'admin')
                    <li>
                        <a href="{{ route('pegawaibaru') }}" class="{{ Request::is('fasilitator/pegawai-baru') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-add-user">
                            </i>Pegawai Baru
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('treeview') }}" class="{{ Request::is('fasilitator/treeview') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-share">
                            </i>Treeview
                        </a>
                    </li>

                    {{-- menu DPA hanya ada di dinas induk dan biro --}}
                    @if ((auth()->user()->id_skpd == 1 && auth()->user()->level == 'admin') ||
                        strlen(auth()->user()->id_skpd) == 3 ||
                        auth()->user()->id_skpd == 1010101 ||
                        auth()->user()->id_skpd == 1010102 ||
                        auth()->user()->id_skpd == 1010103 ||
                        auth()->user()->id_skpd == 1010201 ||
                        auth()->user()->id_skpd == 1010202 ||
                        auth()->user()->id_skpd == 1010204 ||
                        auth()->user()->id_skpd == 1010401 ||
                        auth()->user()->id_skpd == 1010402 ||
                        auth()->user()->id_skpd == 1010404
                    )
                    <li class="app-sidebar__heading">Pendataan Non PTT</li>
                    <li>
                        <a href="{{ route('fasilitator.dpanonptt') }}" class="{{ Request::is('fasilitator/dpa') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-wallet">
                            </i>DPA
                        </a>
                    </li>
                    @endif

                    <li class="app-sidebar__heading">Statistik</li>
                    <li>
                        <a href="{{ route('stats.jml-pegawai') }}" class="{{ Request::is('fasilitator/jml-pegawai') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph">
                            </i>Jumlah Pegawai
                        </a>
                        <a href="{{ route('stats.agama') }}" class="{{ Request::is('fasilitator/stats-agama') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph">
                            </i>Agama
                        </a>
                        <a href="{{ route('stats.pendidikan') }}" class="{{ Request::is('fasilitator/stats-pendidikan') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph">
                            </i>Pendidikan
                        </a>
                        <a href="{{ route('stats.usia') }}" class="{{ Request::is('fasilitator/stats-usia') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph">
                            </i>Usia
                        </a>
                        @if (auth()->user()->level == 'admin')
                        <a href="{{ route('stats.gurumapel') }}" class="{{ Request::is('fasilitator/stats-gurumapel') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph">
                            </i>Guru Mapel
                        </a>
                        @endif
                    </li>

                    @if (auth()->user()->level == 'admin')
                    <li class="app-sidebar__heading">Manajemen</li>
                    <li class="
                        {{ 
                            (Request::is('fasilitator/user-fasilitator') or
                            Request::is('fasilitator/user-nonasn'))
                             ? 'mm-active' : ''
                        }}
                    ">
                        <a href="#">
                            <i class="metismenu-icon pe-7s-users"></i>
                            User
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="mm-collapse 
                            {{ 
                                (Request::is('fasilitator/user-fasilitator') or 
                                Request::is('fasilitator/user-nonasn'))
                                ? 'mm-show' : ''
                            }}"
                        >
                            <li>
                                <a href="{{ route('fasilitator.user') }}" class="{{ Request::is('fasilitator/user-fasilitator') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>User Fasilitator
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('fasilitator.user-nonasn') }}" class="{{ Request::is('fasilitator/user-nonasn') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>User Non ASN
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('fasilitator.unit-kerja') }}" class="{{ Request::is('fasilitator/unit-kerja') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-share">
                            </i>Unit Kerja
                        </a>
                    </li>
                    <li class="
                        {{ 
                            (Request::is('fasilitator/pegawai-aktif') or
                            Request::is('fasilitator/pegawai-nonaktif'))
                             ? 'mm-active' : ''
                        }}
                    ">
                        <a href="#">
                            <i class="metismenu-icon pe-7s-users"></i>
                            Aktivasi/Deaktivasi
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="mm-collapse 
                            {{ 
                                (Request::is('fasilitator/pegawai-aktif') or 
                                Request::is('fasilitator/pegawai-nonaktif'))
                                ? 'mm-show' : ''
                            }}"
                        >
                            <li>
                                <a href="{{ route('fasilitator.pegawai-aktif') }}" class="{{ Request::is('fasilitator/pegawai-aktif') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>Deaktivasi Pegawai
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('fasilitator.pegawai-nonaktif') }}" class="{{ Request::is('fasilitator/pegawai-nonaktif') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>Aktivasi Pegawai
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="
                        {{ 
                            (Request::is('fasilitator/rekap-log-fasilitator') or
                            Request::is('fasilitator/rekap-log-nonasn'))
                             ? 'mm-active' : ''
                        }}
                    ">
                        <a href="#">
                            <i class="metismenu-icon pe-7s-monitor"></i>
                            Rekap Log
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="mm-collapse 
                            {{ 
                                (Request::is('fasilitator/rekap-log-fasilitator') or 
                                Request::is('fasilitator/rekap-log-nonasn'))
                                ? 'mm-show' : ''
                            }}"
                        >
                            <li>
                                <a href="{{ route('fasilitator.rekap-log-fasilitator') }}" class="{{ Request::is('fasilitator/rekap-log-fasilitator') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>Log Fasilitator
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('fasilitator.rekap-log-nonasn') }}" class="{{ Request::is('fasilitator/rekap-log-nonasn') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>Log Non ASN
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if (auth()->user()->level == 'user')
                    <li class="app-sidebar__heading">Manajemen</li>
                    <li class="
                        {{ 
                            (Request::is('fasilitator/user-fasilitator') or
                            Request::is('fasilitator/user-nonasn'))
                             ? 'mm-active' : ''
                        }}
                    ">
                        <a href="#">
                            <i class="metismenu-icon pe-7s-users"></i>
                            User
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="mm-collapse 
                            {{ 
                                Request::is('fasilitator/user-nonasn')
                                ? 'mm-show' : ''
                            }}"
                        >
                            <li>
                                <a href="{{ route('fasilitator.user-nonasn') }}" class="{{ Request::is('fasilitator/user-nonasn') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>User Non ASN
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li class="app-sidebar__heading">Download</li>
                    <li>
                        <a href="{{ route('fasilitator.download-data-keluarga') }}" class="{{ Request::is('fasilitator/data-keluarga') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-cloud-download">
                            </i>Data Keluarga
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>   
</div>