@push('scripts')
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
<!--Apex Charts-->
<script src="{{ asset('assets/js/vendors/charts/apex-charts.js') }}"></script>
<script>
    var options = {
        chart: {
            height: 480,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                horizontal: true,
            },
        },
        series: [{
            data: [{{ $s3 }}, {{ $s2 }}, {{ $s1 }}, {{ $d4 }}, {{ $d3 }}, {{ $d2 }}, {{ $d1 }}, {{ $slta }}, {{ $sltp }}, {{ $sd }}]
        }],
        xaxis: {
          categories: ['S3', 'S2', 'S1', 'DIV', 'DIII', 'DII', 'DI', 'SLTA', 'SLTP', 'SD'],
        },
        dataLabels: {
            enabled: true,
        },
        fill: {
            opacity: .8
        },
    };
</script>
<script>
    $('#form').submit(function() {
        $('#btn-submit, .btn-clear').hide();
        $('.loader').show();
    })

    $(document).ready(function() {
        new ApexCharts(document.querySelector("#pie-chart"), options).render();
        
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
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            Pegawai Berdasarkan Pendidikan
            <div class="page-title-subheading">Statistik Jumlah Pegawai Berdasarkan Pendidikan</div>
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
                    <a href={{ route('stats.pendidikan') }} class="mt-1 btn btn-danger btn-sm btn-square btn-hover-shine btn-clear">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6 col-lg-6">
            <div class="mb-3 card">
                <div id="pie-chart"></div>
            </div>
        </div>
    </div>
    
    {{-- tabel --}}
    {{-- <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Detail Statistik</h5>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            @php
                                [$idSkpd] = explode(" - ", request()->input('skpd')) ;
                            @endphp
                            <form method="post" action="{{ route('fasilitator.download-by-agama', ['idSkpd' => $idSkpd]) }}">
                                @csrf
                                <button class="btn-icon mb-3 btn-sm btn-square btn btn-dark"><i class="pe-7s-cloud-download btn-icon-wrapper"></i> Download</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="mb-0 table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pendidikan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jenjang as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_jenjang }}</td>
                                        <td>
                                            @if (!request()->get('skpd'))
                                                {{ \App\Models\Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                                                        ->whereId_agama($item->id_agama)
                                                        ->whereAktif('Y')
                                                        ->count()
                                                }}
                                            @else
                                                {{ \App\Models\Biodata::where('id_skpd', 'like', explode(' - ', request()->get('skpd'))[0] . '%')
                                                        ->whereId_agama($item->id_agama)
                                                        ->whereAktif('Y')
                                                        ->count()
                                                }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div> --}}
</x-app-layout>