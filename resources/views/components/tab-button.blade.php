<ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
    <li class="nav-item">
        <a class="nav-link show {{ Request::is('fasilitator/rekap-simulasi-pppk-mansoskul') ? 'active' : '' }}" href="{{ route('fasilitator.rekap-simulasi-pppk-mansoskul') }}">
            <span>Manajerial / Sosio Kultural</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link show {{ Request::is('fasilitator/rekap-simulasi-pppk-wawancara') ? 'active' : '' }}" href="{{ route('fasilitator.rekap-simulasi-pppk-wawancara') }}">
            <span>Wawancara</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link show {{ Request::is('fasilitator/rekap-simulasi-pppk-teknis') ? 'active' : '' }}" href="{{ route('fasilitator.rekap-simulasi-pppk-teknis') }}">
            <span>Teknis</span>
        </a>
    </li>
</ul>