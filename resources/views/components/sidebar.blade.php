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
                    <li class="app-sidebar__heading">Statistik</li>
                    <li>
                        <a href="{{ route('stats.jml-pegawai') }}" class="{{ Request::is('fasilitator/jml-pegawai') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph">
                            </i>Jumlah Pegawai
                        </a>
                        <a href="{{ route('stats.agama') }}" class="{{ Request::is('fasilitator/stats-agama') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph">
                            </i>Pegawai by Agama
                        </a>
                        <a href="{{ route('stats.pendidikan') }}" class="{{ Request::is('fasilitator/stats-pendidikan') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph">
                            </i>Pegawai by Pendidikan
                        </a>
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
                    <li class="app-sidebar__heading">Download</li>
                    <li>
                        <a href="{{ route('fasilitator.download-data-anak') }}" class="{{ Request::is('fasilitator/data-anak') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-cloud-download">
                            </i>Data Anak
                        </a>
                        <a href="{{ route('fasilitator.download-data-pasangan') }}" class="{{ Request::is('fasilitator/data-pasangan') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-cloud-download">
                            </i>Data Pasangan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>   
</div>