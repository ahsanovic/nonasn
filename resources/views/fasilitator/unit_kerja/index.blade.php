@push('scripts')
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
@endpush
<x-app-layout>
    <x-page-header>
        <div>
            Unit Kerja
            <div class="page-title-subheading">Peta Unit Kerja</div>
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <a href="{{ route('fasilitator.unit-kerja.create') }}" class="btn btn-sm btn-primary mb-3 btn-square btn-hover-shine">Tambah</a>
                    <div id="menuContent" class="menuContent">
                        <ul id="treeUnit" class="ztree"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@include('_include.unor');