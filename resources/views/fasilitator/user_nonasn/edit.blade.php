@push('scripts')
<script>
    $('#form').submit(function() {
        $('#btn-submit, #btn-cancel').hide();
        $('.loader').show();
    })
</script>
@endpush

<x-app-layout>
    <x-page-header>
        <div>
            User Non ASN
            <div class="page-title-subheading">User Non ASN</div>
        </div>
    </x-page-header>
    <x-card>
        <h5 class="card-title">Edit User Non ASN</h5>
        <form id="form" method="post" action="{{ route('fasilitator.user-nonasn.update', $user->niptt) }}">
            @csrf
            @method('put')
            @include('fasilitator.user_nonasn._form')
        </form>
    </x-card>
</x-app-layout>