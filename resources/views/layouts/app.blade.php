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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @if (session('msg'))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-success" role="alert">
                    <strong>Aviso:</strong> {{ session('msg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@elseif (session('err'))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-danger" role="alert">
                    <strong>Aviso:</strong> {{ session('err') }}
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
</html>