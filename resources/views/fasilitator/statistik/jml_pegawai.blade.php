@push('scripts')
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
<!--Apex Charts-->
<script src="{{ asset('assets/js/vendors/charts/apex-charts.js') }}"></script>
{{-- <script src="{{ asset('assets/js/scripts-init/charts/apex-charts.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/scripts-init/charts/apex-series.js') }}"></script> --}}
<script>
    var options = {
        chart: {
            height: 250,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                horizontal: true,
            },
        },
        series: [{
            data: [{{ $pttpk }}, {{ $ptt_cabdin }}, {{ $ptt_sekolah }}, {{ $gtt }}]
        }],
        xaxis: {
          categories: ['PTT-PK', 'PTT Cabdin', 'PTT Sekolah', 'GTT'],
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
        new ApexCharts(document.querySelector("#dashboard-sparklines-primary"), options).render();
        
        $.ajax({
            url: "{{ route('stats-pegawai.unor') }}",
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
            Jumlah Pegawai
            <div class="page-title-subheading">Statistik Jumlah Pegawai Non ASN</div>
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
                    <a href={{ route('stats.jml-pegawai') }} class="mt-1 btn btn-danger btn-sm btn-square btn-hover-shine btn-clear">Reset</a>
                </div>
            </form>
        </div>
    </div>    
    <div class="row mt-4">
        <div class="col-md-12 col-lg-6">
            <div class="card mb-3 widget-chart text-left">
                <div class="widget-chart-actions">
                    <button class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-cloud-download btn-icon-wrapper"></i></button>
                </div>
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-primary"></div>
                    <i class="lnr-cog text-primary"></i></div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Total Pegawai</div>
                    <div class="widget-numbers">{{ $total }}</div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->id_skpd == 1)
    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card mb-3 widget-chart text-left">
                <div class="widget-chart-actions">
                    @php
                        [$idSkpd] = explode(" - ", request()->input('skpd')) ;
                    @endphp
                    <form method="post" action="{{ route('fasilitator.download-pttpk', ['idSkpd' => $idSkpd]) }}">
                        @csrf
                        <button class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-cloud-download btn-icon-wrapper"></i></button>
                    </form>
                </div>
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-primary"></div>
                    <i class="lnr-cog text-primary"></i></div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">PTT-PK</div>
                    <div class="widget-numbers">{{ $pttpk }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card mb-3 widget-chart text-left">
                <div class="widget-chart-actions">
                    @php
                        [$idSkpd] = explode(" - ", request()->input('skpd')) ;
                    @endphp
                    <form method="post" action="{{ route('fasilitator.download-pttcabdin', ['idSkpd' => $idSkpd]) }}">
                        @csrf
                        <button class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-cloud-download btn-icon-wrapper"></i></button>
                    </form>
                </div>
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-primary"></div>
                    <i class="lnr-cog text-primary"></i></div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">PTT Cabdin</div>
                    <div class="widget-numbers">{{ $ptt_cabdin }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card mb-3 widget-chart text-left">
                <div class="widget-chart-actions">
                    @php
                        [$idSkpd] = explode(" - ", request()->input('skpd')) ;
                    @endphp
                    <form method="post" action="{{ route('fasilitator.download-pttsekolah', ['idSkpd' => $idSkpd]) }}">
                        @csrf
                        <button class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-cloud-download btn-icon-wrapper"></i></button>
                    </form>
                </div>
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-primary"></div>
                    <i class="lnr-cog text-primary"></i></div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">PTT Sekolah</div>
                    <div class="widget-numbers">{{ $ptt_sekolah }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card mb-3 widget-chart text-left">
                <div class="widget-chart-actions">
                    @php
                        [$idSkpd] = explode(" - ", request()->input('skpd')) ;
                    @endphp
                    <form method="post" action="{{ route('fasilitator.download-gtt', ['idSkpd' => $idSkpd]) }}">
                        @csrf
                        <button class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-cloud-download btn-icon-wrapper"></i></button>
                    </form>
                </div>
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-primary"></div>
                    <i class="lnr-cog text-primary"></i></div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">GTT</div>
                    <div class="widget-numbers">{{ $gtt }}</div>
                </div>
            </div>
        </div>
    </div>        
    @endif

    {{-- grafik --}}
    <div class="row mt-2">
        <div class="col-md-6 col-lg-6">
            <div class="mb-3 card">
                {{-- <div class="widget-chart p-0"> --}}
                    <div id="dashboard-sparklines-primary"></div>
                {{-- </div> --}}
                {{-- <div class="divider mb-0"></div> --}}
            </div>
        </div>
    </div>

    {{-- tabel --}}
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Detail Statistik</h5>
                    <div class="table-responsive">
                        <table class="mb-0 table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKPD</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unit_kerja as $key => $item)
                                    <tr>
                                        <td>{{ $unit_kerja->firstItem() + $key }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->biodata->count() }}</td>
                                    </tr>
                                @endforeach                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6">
            {{ $unit_kerja->withQueryString()->links() }}
        </div>
        <div class="col-md-6 col-lg-6 text-right text-muted">
            Showing {{ $unit_kerja->firstItem() }} to {{ $unit_kerja->lastItem() }} out of {{ $unit_kerja->total() }} results
        </div>
    </div>
</x-app-layout>