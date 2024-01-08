<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Non-ASN Pemprov Jatim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    
    <link rel="stylesheet" href="{{ asset('assets/css/base.min.css') }}">
    <link rel="stylesheet" href="{{ asset('zTree/css/zTreeStyle/zTreeStyle.css') }}">
    @stack('styles')
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        <x-navbar></x-navbar>
        
        <div class="app-main">
            @if (auth()->guard('fasilitator')->check())
                <x-sidebar></x-sidebar>
            @else
                <x-sidebar-nonasn></x-sidebar-nonasn>
            @endif
            
            <div class="app-main__outer">
                <div class="app-main__inner">                    
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!--CORE-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
    <script src="{{ asset('assets/js/scripts-init/app.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/demo.js') }}"></script>

    <!--Perfect Scrollbar-->
    <script src="{{ asset('assets/js/vendors/scrollbar.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/scrollbar.js') }}"></script>

    <!--SweetAlert2-->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--Toastr-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        @if(Session::has('message'))
            var type = "{{ Session::get('type', 'info') }}";
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;

                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;

                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;

                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif
    </script>

    @stack('scripts')

</body>

</html>
