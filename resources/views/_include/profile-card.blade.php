<div class="main-card mb-3 card card-hover-shadow-2x">
    <div class="card-header">
        <i class="header-icon lnr-user icon-gradient bg-happy-itmeo"> </i>{{ $pegawai->nama }}
        <div class="btn-actions-pane-right actions-icon-btn">
            <form method="post" action="{{ route('fasilitator.download-person', request()->segment(5)) }}" class="d-inline">
                @csrf
                <button class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-cloud-download btn-icon-wrapper"></i></button>
            </form>
            <div class="btn-group dropdown">
                <button type="button" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-shadow dropdown-menu-right dropdown-menu-hover-link dropdown-menu" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);">
                    <h6 tabindex="-1" class="dropdown-header">Header</h6>
                    <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-inbox"> </i><span>Menus</span></button>
                    <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty"> </i><span>Settings</span></button>
                    <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-book"> </i><span>Actions</span></button>
                    <div tabindex="-1" class="dropdown-divider"></div>
                    <div class="p-3 text-right">
                        <button class="mr-2 btn-shadow btn-sm btn btn-link">View Details</button>
                        <button class="mr-2 btn-shadow btn-sm btn btn-primary">Action</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <img src="{{ asset('upload_foto/' . $pegawai->foto) }}" class="rounded mx-auto d-block" alt="foto" width="150" height="200">
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <a
                    href="{{ route('fasilitator.pegawai.show', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-address-card"></i> Biodata
                </a>
            </div>
            <div class="col-md-6">
                <a 
                    href="{{ route('fasilitator.pendidikan-sma', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-graduation-cap"></i> Pendidikan
                </a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <a
                    href="{{ route('fasilitator.suami-istri', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-venus-mars"></i> Suami/Istri
                </a>
            </div>
            <div class="col-md-6">
                <a
                    href="{{ route('fasilitator.jabatan', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-id-badge"></i> Jabatan
                </a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <a
                    href="{{ route('fasilitator.anak', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-users"></i> Anak
                </a>
            </div>
            <div class="col-md-6">
                <a
                    href="{{ route('fasilitator.penilaian', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-check"></i> Penilaian
                </a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <a
                    href="#"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-user"></i> Orang Tua
                </a>
            </div>            
            <div class="col-md-6">
                <a
                    href="{{ route('fasilitator.hukdis', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-balance-scale"></i> Hukuman Disiplin
                </a>
            </div>
        </div>        
        <div class="row mt-3">
            <div class="col-md-6">
                <a
                    href="{{ route('fasilitator.dok-pribadi', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-file"></i> Dokumen Pribadi
                </a>
            </div>
            <div class="col-md-6">
                <a
                    href="{{ route('fasilitator.diklat', ['idSkpd' => $hashidSkpd->encode($skpd->id), 'id' => $hashidPegawai->encode($pegawai->id_ptt)]) }}"
                    class="btn-square border-0 btn-transition btn btn-outline-primary"
                >
                    <i class="fa fa-briefcase"></i> Diklat
                </a>
            </div>
        </div>
    </div>
</div>