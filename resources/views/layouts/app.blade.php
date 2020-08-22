<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    
        <!-- JQuery para JS & Ajax-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>        
        <!-- Modals -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <!-- DataTable -->
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/loadingscreen.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Loading screen -->
    <div id="loading-screen" style="display:none">
        <div id="spin" class="spinner-border text-success" style="width: 6rem; height: 6rem;" role="status">
            <span class="sr-only"></span>
        </div>
    </div>
<!-- Alertas -->
@if (session('info'))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-2">
                <div class="alert alert-success" role="alert">
                    <strong>Aviso:</strong> {{ session('info') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@elseif (session('error'))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-2">
                <div class="alert alert-danger" role="alert">
                    <strong>Aviso:</strong> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
    <div class="container">
        @yield('content')
    </div>
</body>
<script src="{{ asset('js/loader.js') }}" defer></script>
</html>