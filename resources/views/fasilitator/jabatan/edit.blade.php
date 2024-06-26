@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush

@push('scripts')
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
<!--Datepickers-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('assets/js/vendors/form-components/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/scripts-init/form-components/datepicker.js') }}"></script>
<script>
    var url = "{{ route('jabatan.autocomplete') }}";
    $("#guru-mapel").autocomplete({
        minLength: 3,
        source: function(request, response) {
          $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
               search: request.term
            },
            success: function(data) {
               response(data);
            }
          });
        },
        select: function (event, ui) {
           $('#guru-mapel').val(ui.item.label);
           $('#id-guru-mapel').val(ui.item.value);
           return false;
        },
    });
</script>
<script>
    $('#form').submit(function() {
        $('#btn-submit, #btn-cancel').hide();
        $('.loader').show();
    })
</script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('jabatan.treeview') }}",
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
            Profil Pegawai
            <div class="page-title-subheading">{{ $pegawai->nama . ' - ' . $skpd->name }}</div>
        </div>
    </x-page-header>
    <div class="row">
        <div class="col-md-3">
            @include('_include.profile-card')
        </div>
        <div class="col-md-9">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Edit Jabatan</h5>
                    <div class="mt-4">
                        <form
                            id="form"
                            method="post"
                            action="{{
                                route('fasilitator.jabatan.update', [
                                        'idSkpd' => $hashidSkpd->encode($skpd->id),
                                        'id' => $hashidPegawai->encode($pegawai->id_ptt),
                                        'idJabatan' => $hashid->encode($jab->id_ptt_jab)
                                    ])
                                }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @method('put')
                            @include('fasilitator.jabatan._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>