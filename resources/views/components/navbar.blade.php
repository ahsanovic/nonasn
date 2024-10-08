<div>
    <!--Header START-->
    <div class="app-header header-shadow">
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
        <div class="app-header__content">
            <div class="app-header-left">
                @if (auth()->guard('fasilitator')->check())
                <div class="search-wrapper">
                    <div class="input-holder">
                        <form method="get" action="{{ route('fasilitator.search-pegawai') }}">
                            <input type="text" name="nama" class="search-input" placeholder="cari pegawai" value="{{ request('nama', '') }}">
                        </form>
                        <button class="search-icon"><span></span></button>
                    </div>
                    <button class="close"></button>
                </div>
                @endif
            </div>
            <div class="app-header-right">                    
                <div class="header-btn-lg pr-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="btn-group">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                        @if (auth()->guard('fasilitator')->check())
                                        <img width="42" class="rounded-circle" src="{{ asset('assets/images/avatars/default.png') }}" alt="">
                                        @else
                                        <img width="42" class="rounded" src="{{ route('nonasn.image', ['image' => rtrim(auth()->user()->foto)]) }}" alt="">
                                        @endif
                                        <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner bg-info">
                                                <div class="menu-header-image opacity-2" style="background-image: url('../assets/images/dropdown-header/city3.jpg');"></div>
                                                <div class="menu-header-content text-left">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                @if (auth()->guard('fasilitator')->check())
                                                                    <img width="42" class="rounded-circle"
                                                                        src="{{ asset('assets/images/avatars/default.png') }}"
                                                                        alt="">
                                                                @else
                                                                    <img width="42" class="rounded"
                                                                        src="{{ rtrim(auth()->user()->foto) }}"
                                                                        alt="">
                                                                @endif
                                                            </div>
                                                            <div class="widget-content-left">
                                                                @if (auth()->guard('fasilitator')->check())
                                                                    <div class="widget-heading">{{ auth()->user()->nama_lengkap }}</div>
                                                                    <div class="widget-subheading opacity-8">{{ auth()->user()->skpd->name }}</div>
                                                                @else
                                                                    <div class="widget-heading">{{ auth()->user()->nama }}</div>
                                                                @endif
                                                            </div>
                                                            <div class="widget-content-right mr-2">
                                                                @if (auth()->guard('fasilitator')->check())
                                                                    <form method="post" action="{{ route('fasilitator.logout') }}">
                                                                        @csrf
                                                                        <button class="btn-pill btn-shadow btn-shine btn btn-focus" type="submit">logout</button>
                                                                    </form>
                                                                @else
                                                                    <form method="post" action="{{ route('nonasn.logout') }}">
                                                                        @csrf
                                                                        <button class="btn-pill btn-shadow btn-shine btn btn-focus" type="submit">logout</button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="scroll-area-xs" style="height: 150px;">
                                            <div class="scrollbar-container ps">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">Activity
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Chat
                                                            <div class="ml-auto badge badge-pill badge-info">8
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        @if (auth()->guard('fasilitator')->check())
                                                            <a href="{{ route('fasilitator.password') }}" class="nav-link">Ubah Password</a>
                                                        @else
                                                            <a href="{{ route('nonasn.password') }}" class="nav-link">Ubah Password</a>
                                                        @endif
                                                    </li>
                                                    <li class="nav-item-header nav-item">My Account
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Settings
                                                            <div class="ml-auto badge badge-success">New
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Messages
                                                            <div class="ml-auto badge badge-warning">512
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Logs
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider mb-0 nav-item"></li>
                                        </ul>
                                        <div class="grid-menu grid-menu-2col">
                                            <div class="no-gutters row">
                                                <div class="col-sm-6">
                                                    <button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning">
                                                        <i class="pe-7s-chat icon-gradient bg-amy-crisp btn-icon-wrapper mb-2"></i>
                                                        Message Inbox
                                                    </button>
                                                </div>
                                                <div class="col-sm-6">
                                                    <button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                        <i class="pe-7s-ticket icon-gradient bg-love-kiss btn-icon-wrapper mb-2"></i>
                                                        <b>Support Tickets</b>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider nav-item">
                                            </li>
                                            <li class="nav-item-btn text-center nav-item">
                                                <button class="btn-wide btn btn-primary btn-sm">
                                                    Open Messages
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content-left  ml-3 header-user-info">
                                @if (auth()->guard('fasilitator')->check())
                                    <div class="widget-heading">
                                        {{ auth()->user()->username }}
                                    </div>
                                    <div class="widget-subheading">
                                        {{ auth()->user()->level }}
                                    </div>
                                @else
                                    <div class="widget-heading">
                                        {{ auth()->user()->nama }}
                                    </div>
                                    <div class="widget-subheading">
                                        {{ auth()->user()->niptt }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Header END-->
</div>