<div>
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    @php
                        $uri = Request::segment(2);
                        switch ($uri) {
                            case 'dashboard':
                                echo "<i class='pe-7s-graph2 icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'pegawai':
                                echo "<i class='pe-7s-id icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'pegawai-baru':
                                echo "<i class='pe-7s-id icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'user-fasilitator':
                                echo "<i class='pe-7s-users icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'user-nonasn':
                                echo "<i class='pe-7s-users icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'unit-kerja':
                                echo "<i class='pe-7s-share icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'jml-pegawai':
                                echo "<i class='pe-7s-graph2 icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'stats-agama':
                                echo "<i class='pe-7s-graph2 icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'stats-pendidikan':
                                echo "<i class='pe-7s-graph2 icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'stats-gurumapel':
                                echo "<i class='pe-7s-graph2 icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'stats-usia':
                                echo "<i class='pe-7s-graph2 icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'rekap-log-fasilitator':
                                echo "<i class='pe-7s-monitor icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'rekap-log-nonasn':
                                echo "<i class='pe-7s-monitor icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'rekap-simulasi-cpns':
                                echo "<i class='pe-7s-monitor icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'rekap-simulasi-pppk-mansoskul':
                                echo "<i class='pe-7s-monitor icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'rekap-simulasi-pppk-wawancara':
                                echo "<i class='pe-7s-monitor icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'rekap-simulasi-pppk-teknis':
                                echo "<i class='pe-7s-monitor icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'download-data-keluarga':
                                echo "<i class='pe-7s-cloud-download icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'dpa':
                                echo "<i class='pe-7s-wallet icon-gradient bg-premium-dark'></i>";
                                break;
                        }
                    @endphp
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>