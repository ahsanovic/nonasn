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
                        <a href="{{ route('nonasn.dashboard') }}" class="{{ Request::is('dashboard') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-graph2">
                            </i>Dashboard
                        </a>
                    </li>
                    <li class="app-sidebar__heading">Profil</li>
                    <li class="{{ 
                        (Request::segment(1) == 'biodata' or
                        Request::segment(1) == 'dok-pribadi' or
                        Request::segment(1) == 'suami-istri' or
                        Request::segment(1) == 'anak'
                        )
                         ? 'mm-active' : ''
                    }}                    
                    ">
                        <a href="#">
                            <i class="metismenu-icon pe-7s-user"></i>
                            Data Diri
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="mm-collapse 
                            
                        ">
                            <li>
                                <a href="{{ route('nonasn.biodata') }}" class="{{ Request::segment(1) == 'biodata' ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>Biodata
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('nonasn.dok-pribadi') }}" class="{{ Request::segment(1) == 'dok-pribadi' ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>Dokumen
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('nonasn.suami-istri') }}" class="{{ Request::segment(1) == 'suami-istri' ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>Suami/Istri
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('nonasn.anak') }}" class="{{ Request::segment(1) == 'anak' ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon">
                                    </i>Anak
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('nonasn.pendidikan-sma') }}" class="{{ Request::segment(1) == 'pendidikan' ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-study">
                            </i>Pendidikan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('nonasn.jabatan') }}" class="{{ Request::segment(1) == 'jabatan' ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-medal">
                            </i>Jabatan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('nonasn.penilaian') }}" class="{{ Request::segment(1) == 'penilaian' ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-check">
                            </i>Penilaian
                        </a>
                    </li>
                    <li class="app-sidebar__heading">Simulasi</li>
                    <li>
                        <a href="{{ route('nonasn.simulasi.cpns') }}" class="{{ Request::is('simulasi-cpns') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-note">
                            </i>Simulasi Tes CPNS
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('nonasn.simulasi.pppk') }}" class="{{ Request::is('simulasi-pppk') ? 'mm-active' : '' }}">
                            <i class="metismenu-icon pe-7s-note">
                            </i>Simulasi Tes PPPK
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>   
</div>