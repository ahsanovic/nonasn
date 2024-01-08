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
            url: "{{ route('unor') }}",
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
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            Unit Kerja
            <div class="page-title-subheading">Tambah Unit Kerja</div>
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form id="form" method="post" action="{{ route('fasilitator.unit-kerja.store') }}">
                        @csrf
                        <div class="position-relative form-group">
                            <label for="id-parent" class="font-weight-bold">ID parent</label>
                            <input id="citySel" name="pId" class="form-control form-control-sm @error('pId') is-invalid @enderror" type="text" readonly  value="{{ old('pId') }}"/>
                            <a id="menuBtn" href="#" onclick="showMenu(); return false;">select</a>
                            <div id="menuContent" class="menuContent" style="display:none;">
                                <ul id="treeUnit" class="ztree" style="margin-top:0;"></ul>
                            </div>
                            @error('pId')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="position-relative form-group">
                            <label for="id" class="font-weight-bold">ID Unit Kerja</label>
                            <input
                                id="id"
                                type="text"
                                name="id"
                                class="form-control form-control-sm @error('id') is-invalid @enderror"
                                value="{{ old('id') }}"
                            >
                            @error('id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="position-relative form-group">
                            <label for="name" class="font-weight-bold">Nama Unit Kerja</label>
                            <input id="name" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" type="text" />
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <x-button-loader />
                        <button
                            class="mt-1 btn btn-success btn-sm btn-square btn-hover-shine"
                            type="submit"
                            id="btn-submit"
                            >
                            Tambah
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>