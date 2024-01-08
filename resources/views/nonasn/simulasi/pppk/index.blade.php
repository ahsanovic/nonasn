@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('assets/js/vendors/blockui.js') }}"></script>
<script>
    $.blockUI.defaults = {
        // timeout: 2000,
        fadeIn: 200,
        fadeOut: 400,
    }

    $('#form-teknis, #form-mansoskul, #form-wawancara').submit(function() {
        $.blockUI({message: $('.page-block')});
    })

    $('.select2').select2()

    $('#jabatan').change(function() {
        let csrf_token = $('meta[name="csrf-token"]').attr('content');
        let jabatan_id = $('#jabatan').val();
        let jabatan = $('#jabatan option:selected').text();

        $.ajax({
            url: "{{ route('nonasn.simulasi.pppk.update-jabatan') }}",
            type: 'post',
            dataType: 'json',
            data: {
                '_method': 'PATCH',
                '_token': csrf_token,
                'jabatan_id': jabatan_id,
                'jabatan': jabatan,
            },
            success: function(data) {
                if (data.status == 'success') {
                    $('#jabatan-id').val(data.data.jabatan_simulasi_id)
                    $('#jab-pilihan').html(`Jabatan Pilihan Kamu: <span class="text-info">${data.data.jabatan}</span>`)
                    $('#btn-start-teknis').prop('disabled', false)
                    $('#btn-start-mansoskul').prop('disabled', false)
                    $('#btn-start-wawancara').prop('disabled', false)
                    toastr.success(data.msg)
                } else {
                    toastr.error(data.msg)
                }
            },
        })

        $('#btn-start').prop('disabled', false)
    })
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Simulasi Tes PPPK
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="alert alert-success fade show" role="alert" id="alert">
                Fitur ini berguna banget buat kalian yang pengen melakukan simulasi Tes PPPK atau bisa juga buat bahan belajar. 
                Pepatah Thailand mengatakan "Practices make perfect!" <br />
                Soo.. manfaatkan fitur ini dengan sebaik-baiknya yaaa :)
            </div>
            <div class="alert alert-danger fade show" role="alert" id="alert">
                Pastikan memilih jabatan dulu yaa jika sebelumnya tidak pernah melakukan simulasi
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-6">
            <label class="font-weight-bold" id="jab-pilihan">Jabatan Pilihan Kamu: <span class="text-info">{{ $jabatan->jabatan ?? '-' }}</span></label>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="jabatan">Ganti Jabatan</label>
                <form id="form-jabatan">
                    <select name="jabatan" id="jabatan" class="form-control select2 select2-hidden-accessible" style="width: 100%;">
                        <option disabled selected>- pilih jabatan -</option>
                        @foreach ($response->data as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12 col-lg-12 d-flex">
            <form id="form-teknis" method="post" action="{{ route('nonasn.simulasi.pppk.store-teknis') }}" class="mr-3">
                @csrf
                <input type="hidden" name="jabatan_id" id="jabatan-id" value="{{ $jabatan->jabatan_simulasi_id ?? 0 }}">
                <button id="btn-start-teknis" class="btn btn-square btn-danger btn-hover-shine" {{ $jabatan->jabatan ? '' : 'disabled' }}>Simulasi Teknis</button>
            </form>
            <form id="form-mansoskul" method="post" action="{{ route('nonasn.simulasi.pppk.store-mansoskul') }}" class="mr-3">
                @csrf
                <button id="btn-start-mansoskul" class="btn btn-square btn-danger btn-hover-shine" {{ $jabatan->jabatan ? '' : 'disabled' }}>Simulasi Manajerial/Sosio Kultural</button>
            </form>
            <form id="form-wawancara" method="post" action="{{ route('nonasn.simulasi.pppk.store-wawancara') }}">
                @csrf
                <button id="btn-start-wawancara" class="btn btn-square btn-danger btn-hover-shine" {{ $jabatan->jabatan ? '' : 'disabled' }}>Simulasi Wawancara</button>
            </form>
        </div>
    </div>

    {{-- page block UI --}}
    <div class="page-block d-none">
        <div class="loader bg-transparent no-shadow p-0">
            <div class="ball-grid-pulse">
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
                <div class="bg-white"></div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="mb-3 card">
                <div class="card-header">
                    Riwayat Simulasi Tes PPPK
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="table-responsive">
                            <table class="mb-0 table mt-2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Tes</th>
                                        <th>Nilai</th>
                                        <th>Waktu Simulasi</th>
                                        <th>Kunci Jawaban</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history as $key => $item)
                                        <tr>
                                            <td>{{ $history->firstItem() + $key }}</td>
                                            <td>{{ $item->jenis_tes }}</td>
                                            <td>{{ $item->nilai }}</td>
                                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @switch($item->jenis_tes)
                                                    @case('Manajerial/Sosio Kultural')
                                                        <a
                                                            href={{ route('nonasn.simulasi.pppk.kunci-mansoskul', ['no' => 1, 'ujian' => $item->id]) }}
                                                            target="_blank"
                                                            class="btn btn-sm btn-square btn-hover-shine btn-success"
                                                        >
                                                            Lihat
                                                        </a>
                                                        @break
                                                    @case('Kompetensi Teknis')
                                                        <a
                                                            href={{ route('nonasn.simulasi.pppk.kunci-teknis', ['no' => 1, 'ujian' => $item->id]) }}
                                                            target="_blank"
                                                            class="btn btn-sm btn-square btn-hover-shine btn-success"
                                                        >
                                                            Lihat
                                                        </a>
                                                        @break
                                                    @case('Wawancara')
                                                        <a
                                                            href={{ route('nonasn.simulasi.pppk.kunci-wawancara', ['no' => 1, 'ujian' => $item->id]) }}
                                                            target="_blank"
                                                            class="btn btn-sm btn-square btn-hover-shine btn-success"
                                                        >
                                                            Lihat
                                                        </a>
                                                        @break
                                                    @default
                                                        
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            {{ $history-> links() }}
        </div>
    </div>
</x-app-layout>