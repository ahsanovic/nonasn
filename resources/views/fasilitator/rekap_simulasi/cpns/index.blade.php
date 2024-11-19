@push('scripts')
<!--Datepickers-->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('assets/js/vendors/form-components/daterangepicker.js') }}"></script>
<!--Tree View-->
<script src="{{ asset('zTree/js/jquery.ztree.core-3.5.js') }}"></script>
<script>
    $('#perPage').change(function() {
        let page = $('#perPage').val()
        $('#per_page_value').val(page)
        $('#form').submit();
    });

    $('input[name="daterange"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $(document).ready(function() {
        $.ajax({
            url: "{{ route('treeview.skpd.nolink') }}",
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
            Rekap Simulasi Tes
            <div class="page-title-subheading">CPNS</div>
        </div>
    </x-page-header>
    <x-card>
        <h5 class="card-title">Rekap Simulasi Tes CPNS</h5>
        <div class="row mb-3 justify-content-between">
            <div class="col-md-2 mb-2">
                <div class="form-inline">
                    <label class="mr-2">Show</label>
                    <select name="perPage" id="perPage" class="form-control-sm form-control">
                        <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $per_page == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-2">                
                <form method="post" action="{{
                    route('fasilitator.download-rekap-simulasi-cpns', ['daterange' => request()->daterange, 'niptt' => request()->niptt, 'skpd' => request()->skpd])
                }}">
                    @csrf
                    <button class="btn btn-dark btn-sm btn-square btn-hover-shine mr-2"><i class="pe-7s-cloud-download"></i> Download Excel</button>
                </form>
            </div>
        </div>
        <form id="form" method="get" action="{{ url()->current() }}">
            <input type="hidden" id="per_page_value" name="perPage" value="">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="font-weight-bold">Range Tanggal</label>
                    <input type="text" class="form-control form-control-sm" name="daterange" value="{{ request()->input('daterange') }}" />
                </div>
                <div class="col-md-2">
                    <label class="font-weight-bold">NIPTT / Nama</label>
                    <input type="text" class="form-control form-control-sm" name="niptt" value="{{ request()->input('niptt') }}" />
                </div>
                <div class="col-md-4">
                    <label class="font-weight-bold">Unit Kerja</label>
                    <input id="citySel" name="skpd" class="form-control form-control-sm @error('skpd') is-invalid @enderror" type="text" readonly  value="{{ request()->input('skpd') }}"/>
                    <a id="menuBtn" href="#" onclick="showMenu(); return false;">select</a>
                    <div id="menuContent" class="menuContent" style="display:none;">
                        <ul id="treeUnit" class="ztree" style="margin-top:0;"></ul>
                    </div>
                    @error('skpd')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="col-md-2 mt-2">
                    <br />
                    <a href="{{ route('fasilitator.rekap-simulasi-cpns') }}" class="btn btn-sm btn-hover-shine btn-square btn-danger">Reset</a>
                    <button type="submit" class="btn btn-sm btn-hover-shine btn-square btn-success">Cari</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="mb-0 table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIPTT</th>
                        <th>Unit Kerja</th>
                        <th>TWK</th>
                        <th>TIU</th>
                        <th>TKP</th>
                        <th>Total</th>
                        <th>Tgl/Jam Simulasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $item)
                        <tr>
                            <td>{{ $data->firstItem() + $key }}</td>
                            <td>{{ $item->biodata->nama ?? '' }}</td>
                            <td>{{ $item->biodata->niptt ?? '' }}</td>
                            <td>{{ $item->biodata->skpd->name ?? '' }}</td>
                            <td>{{ $item->nilai_twk }}</td>
                            <td>{{ $item->nilai_tiu }}</td>
                            <td>{{ $item->nilai_tkp }}</td>
                            <td>{{ $item->nilai_total }}</td>
                            <td>{{ $item->created_at->format('d M Y') . ' / ' . $item->created_at->format('H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
    <div class="row">
        <div class="col">
            {{ $data->withQueryString()->links() }}
        </div>
        <div class="col text-right text-muted">
            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} out of {{ $data->total() }} results
        </div>
    </div>
</x-app-layout>