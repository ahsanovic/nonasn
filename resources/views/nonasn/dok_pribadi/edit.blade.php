@push('scripts')
<script>
    $('#form').submit(function() {
        $('#btn-submit, #btn-cancel').hide();
        $('.loader').show();
    })
</script>
@endpush

<x-app-layout>
    <x-page-header-nonasn>
        <div>
            Dokumen
            <div class="page-title-subheading">{{ auth()->user()->nama }}</div>
        </div>
    </x-page-header-nonasn>
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card card-hover-shadow-2x">
                <div class="card-body">
                    <h5 class="card-title">Edit Dokumen</h5>
                    <div class="mt-4">
                        <form
                            id="form"
                            method="post"
                            action="{{
                                route('nonasn.dok-pribadi.update', [
                                    'id' => $hashId->encode($data->id),
                                    'field' => Request::segment(4)
                                ])
                            }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @method('put')
                            @include('nonasn.dok_pribadi._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>