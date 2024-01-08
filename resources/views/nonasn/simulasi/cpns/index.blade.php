@push('scripts')
<script src="{{ asset('assets/js/vendors/blockui.js') }}"></script>
<script>
    $.blockUI.defaults = {
        // timeout: 2000,
        fadeIn: 200,
        fadeOut: 400,
    }

    $('#form').submit(function() {
        $.blockUI({message: $('.page-block')});
    })
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Simulasi Tes CPNS
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="alert alert-success fade show" role="alert" id="alert">
                Fitur ini berguna banget buat kalian yang pengen melakukan simulasi Tes CPNS atau bisa juga buat bahan belajar. 
                Pepatah Thailand mengatakan "Practices make perfect!" <br />
                Soo.. manfaatkan fitur ini dengan sebaik-baiknya yaaa :)
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12 col-lg-12">
            <x-button-loader />
            <form id="form" method="post" action="{{ route('nonasn.simulasi.cpns.store') }}">
                @csrf
                <button id="btn-start" class="btn btn-square btn-danger btn-hover-shine">Mulai Simulasi</button>
            </form>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Simulasi Tes CPNS</h5>
                    <div class="table-responsive">
                        <table class="mb-0 table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nilai TWK</th>
                                    <th>Nilai TIU</th>
                                    <th>Nilai TKP</th>
                                    <th>Nilai Total</th>
                                    <th>Waktu Simulasi</th>
                                    <th>Kunci Jawaban</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($nilai as $item)
                                    <tr>
                                        <td>{{ $nilai->firstItem() + $loop->index }}</td>
                                        <td>{{ $item->nilai_twk }}</td>
                                        <td>{{ $item->nilai_tiu }}</td>
                                        <td>{{ $item->nilai_tkp }}</td>
                                        <td>{{ $item->nilai_total }}</td>
                                        <td>{{ $item->created_at->format('d M y / H:i') }}</td>
                                        <td>
                                            <a
                                                href={{ route('nonasn.simulasi.cpns.kunci', ['no' => 1, 'ujian' => $item->id]) }}
                                                target="_blank"
                                                class="btn btn-sm btn-square btn-hover-shine btn-success"
                                            >
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">- tidak ada data -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
    
    <div class="row">
        <div class="col-md-12 col-lg-12">
            {{ $nilai->links() }}
        </div>
    </div>
</x-app-layout>