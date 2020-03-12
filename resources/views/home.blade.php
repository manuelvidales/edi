@extends('layouts.app')
@section('content')
<div class="container p-4">
        
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">Separar Archivo TxT por ~ </div>
                <div class="card-body">
                    
                        <form action="{{route('import.filetxt')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupFileAddon01">Cargar</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="file">
                                    <label class="custom-file-label" for="inputGroupFile01">Elija su archivo</label></div>.<button type="submit" class="btn btn-dark">Separar ~</button>
                            </div>
                        </form>
                        <a type="button" class="btn btn-light" href="{{ route('import') }}"> <i class="fas fa-folder-open"></i> :// ver </a>
                    </div>
            </div>
        </div>
    </div>
<hr>
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header">Emerson Codigo: 204</div>
                    <div class="card-body">
                        <a type="button" class="btn btn-primary" href="{{ url('ediemerson/path') }}"> <i class="fas fa-folder-open"></i> :// Separar por * </a>
                        |<a type="button" class="btn btn-secondary" href="{{ url('ediemerson/insertar') }}"><i class="fas fa-database"></i> Insertar</a>
                        |<a type="button" class="btn btn-success" href="{{ route('ediemerson.creartxt') }}"><i class="fas fa-file-export"></i> TxT 990</a>
                    </div>
                </div>
            </div>
        </div>
<hr>
<div class="row justify-content-center">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header">Daimler Codigo: 204</div>
            <div class="card-body">
                <a type="button" class="btn btn-primary" href="{{ url('edidaimler/path') }}"> <i class="fas fa-folder-open"></i> :// Separar por * </a>
                |<a type="button" class="btn btn-secondary" href="{{ url('edidaimler/insertar') }}"><i class="fas fa-database"></i> Insertar</a>
                |<a type="button" class="btn btn-success" href="{{ route('edidaimler.creartxt') }}"><i class="fas fa-file-export"></i> TxT 990</a>
            </div>
        </div>
    </div>
</div>



    </div>
</div>
@endsection