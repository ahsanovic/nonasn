@push('scripts')
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
@endpush
<x-app-layout>
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-share icon-gradient bg-premium-dark"></i>
                </div>
                <div>
                    Treeview SKPD
                    <div class="page-title-subheading">Peta SKPD Pemerintah Provinsi Jawa Timur</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div id="menuContent" class="menuContent">
                        <ul id="treeUnit" class="ztree"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@include('_include.unor');