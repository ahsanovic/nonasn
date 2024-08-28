<div>
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    @php
                        $uri = Request::segment(1);
                        switch ($uri) {
                            case 'dashboard':
                                echo "<i class='pe-7s-graph2 icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'biodata':
                                echo "<i class='pe-7s-id icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'dok-pribadi':
                                echo "<i class='pe-7s-id icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'suami-istri':
                                echo "<i class='pe-7s-id icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'anak':
                                echo "<i class='pe-7s-id icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'pendidikan':
                                echo "<i class='pe-7s-study icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'jabatan':
                                echo "<i class='pe-7s-medal icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'gaji':
                                echo "<i class='pe-7s-wallet icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'penilaian':
                                echo "<i class='pe-7s-display1 icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'dok-narkoba':
                                echo "<i class='pe-7s-check icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'diklat':
                                echo "<i class='pe-7s-portfolio icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'hukdis':
                                echo "<i class='pe-7s-culture icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'simulasi-cpns':
                                echo "<i class='pe-7s-note icon-gradient bg-premium-dark'></i>";
                                break;
                            case 'simulasi-pppk':
                                echo "<i class='pe-7s-note icon-gradient bg-premium-dark'></i>";
                                break;
                        }
                    @endphp
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>