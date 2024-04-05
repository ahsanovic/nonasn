@push('scripts')
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
<script>
    $('#form').submit(function() {
        $('#btn-submit').hide();
        $('.loader').show();
    })

    $(document).ready(function() {
        $.ajax({
            url: "{{ route('stats-agama.unor') }}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var setting = {
                    data: {
                        simpleData: {
                            enable: true
                        }
                    },
                    callback: {
                        beforeClick: beforeClick,
                        onClick: onClick
                    }
                };

                var zNodes = data;

                $.fn.zTree.init($("#treeUnit"), setting, zNodes);
            },
            error: function(err) {
                console.log(err);
            }
        })
    });

    function beforeClick(treeId, treeNode) {
        var check = (treeNode && !treeNode.isParent);
    }

    function onClick(e, treeId, treeNode) {
        var zTree = $.fn.zTree.getZTreeObj("treeUnit"),
            nodes = zTree.getSelectedNodes(),
            v = "";
        nodes.sort(function compare(a, b) {
            return a.id - b.id;
        });
        for (var i = 0, l = nodes.length; i < l; i++) {
            v += nodes[i].name + ",";
        }
        if (v.length > 0) v = v.substring(0, v.length - 1);
        var cityObj = $("#citySel");
        cityObj.attr("value", v);
    }   

    function showMenu() {
        var cityObj = $("#citySel");
        var cityOffset = $("#citySel").offset();
        $("#menuContent").css({
            left: cityOffset.left + "px",
            top: cityOffset.top + cityObj.outerHeight() + "px"
        }).slideDown("fast");

        $("body").bind("mousedown", onBodyDown);
    }

    function hideMenu() {
        $("#menuContent").fadeOut("fast");
        $("body").unbind("mousedown", onBodyDown);
    }

    function onBodyDown(event) {
        if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length > 0)) {
            hideMenu();
        }
    }
</script>
</script>
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            Download Data
            <div class="page-title-subheading">Download Data Keluarga</div>
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-12 col-lg-6">
            <form id="form" method="get" action="{{ url()->current() }}">
                <div class="position-relative form-group">
                    <label for="skpd" class="font-weight-bold">Unit Kerja</label>
                    <input
                        id="citySel"
                        name="skpd"
                        class="form-control form-control-sm"
                        type="text"
                        readonly
                        value="<?php
                            if (request()->skpd != '') {
                                echo request()->get('skpd');
                            } else {
                                echo $skpd->id . ' - ' . $skpd->name;
                            }
                        ?>"
                    />
                    <a id="menuBtn" href="#" onclick="showMenu(); return false;">select</a>
                    <div id="menuContent" class="menuContent" style="display:none;">
                        <ul id="treeUnit" class="ztree" style="margin-top:0;"></ul>
                    </div>
                    <x-button-loader />
                    <button
                        class="mt-1 btn btn-success btn-sm btn-square btn-hover-shine"
                        type="submit"
                        id="btn-submit"
                    >
                        Filter
                    </button>
                    <a href={{ route('fasilitator.download-data-keluarga') }} class="mt-1 btn btn-danger btn-sm btn-square btn-hover-shine btn-clear">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-2 mt-3">
        <div class="col-md-12">
            <div class="alert alert-danger fade show" role="alert">
                Pastikan data pasangan dalam kondisi aktif ( 'Y' ) jika status pegawai adalah menikah, agar rekap menjadi benar 
            </div>    
        </div>    
    </div>
    <div class="row mt-3">
        <div class="col-md-12 d-flex">
            @php
                [$idSkpd] = explode(" - ", request()->input('skpd')) ;
            @endphp
            <form method="post" action="{{ route('fasilitator.download-data-pasangan', ['idSkpd' => $idSkpd]) }}">
                @csrf
                <button
                    class="btn-icon mb-3 btn-sm btn-square btn btn-dark mr-3">
                    <i class="pe-7s-cloud-download btn-icon-wrapper"></i>
                    Download Data Pasangan
                </button>
            </form>
            <form method="post" action="{{ route('fasilitator.download-data-keluarga', ['idSkpd' => $idSkpd]) }}">
                @csrf
                <button
                    class="btn-icon mb-3 btn-sm btn-square btn btn-primary">
                    <i class="pe-7s-cloud-download btn-icon-wrapper"></i>
                    Download Data Keluarga
                </button>
            </form>
        </div>
    </div>
</x-app-layout>