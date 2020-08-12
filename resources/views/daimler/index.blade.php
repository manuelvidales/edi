@extends('layouts.app')
@section('content')

<div class="jumbotron p-2">

<div class="card-deck p-4">
  <div class="card text-white text-center bg-dark shadow rounded">
    <div class="row justify-content-md-center">
      <div class="col-3"><i class="fas fa-inbox fa-5x"></i></div>
      <div class="col-3"><h1 class="font-weight-bold"><span class="badge badge-primary">
        @php
             echo count($ships);
        @endphp
      </span></h1></div>
    </div>
    <div class="card-body">
      <h5 class="card-title"><button type="button" class="btn btn-outline-light">Documentos totales</button></h5>
      <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
    </div>
  </div>

  <div class="card text-white text-center bg-dark">
    <div class="row justify-content-md-center">
      <div class="col-2"><i class="fas fa-file-download fa-5x"></i></div>
      <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
        @php
        echo count($filesnew);
        @endphp
      </span></h2></div>
    </div>
    <div class="card-body">
      <h5 class="card-title"><button type="button" class="btn btn-outline-light">Recepcion archivos</button></h5>
      <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
    </div>
  </div>

  <div class="card text-white text-center bg-dark">
    <div class="row justify-content-md-center">
      <div class="col-2"><i class="fas fa-file-upload fa-5x"></i></div>
      <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
        @php
        echo count($filessend);
        @endphp  
      </span></h2></div>
    </div>
    <div class="card-body">
      <h5 class="card-title"><button type="button" class="btn btn-outline-light">Recepcion confirmadas</button></h5>
      <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
    </div>
  </div>

  <div class="card text-white text-center bg-dark">
    <div class="row justify-content-md-center">
      <div class="col-2"><i class="fas fa-file-alt fa-5x"></i></div>
      <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">X
        
      </span></h2></div>
    </div>
    <div class="card-body">
      <h5 class="card-title"><button type="button" class="btn btn-outline-light">Ordenes confirmadas</button> </h5>
      <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
    </div>
  </div>
  
</div>
</div>



<div class="jumbotron p-4">
    <div class="card shadow p-3 mb-5 bg-white rounded">
        <div class="card-header text-center">
        
        </div>
        <div class="card-body">
        <div class="row justify-content-md-center">

          <div class="table-responsive">
            <table class="table table-sm table-striped table-hover" id="pedidos">
              <thead class="thead-light">
                  <tr>
                      <th></th>
                      <th>archivo</th>
                      <th>Id orden</th>
                      <th>Respuesta</th>
                      <th>Fecha recepcion</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($ships as $data)
                  <?php $file = substr($data->filename, 10); ?>
                  <tr>
                    <td><a href="{{url ('getfile/'.$file)}}" ><i class="fas fa-download"></i></a></td>
                    <td>
                      <?php $files = "$data->filename"; echo substr($files, 10); ?>
                    </td>
                    <td>{{$data->shipment_id}}</td>
                    @if ( $data->response == 'A')
                    <td><span class="badge badge-pill badge-success">aceptada</span></td>
                    @elseif( $data->response == 'D')
                    <td><span class="badge badge-pill badge-danger">rechazada</span></td>
                    @else
                    <td><span class="badge badge-pill badge-secondary">sin respuesta</span></td>
                    @endif
                    <td>{{$data->created_at}}</tr>
                    @endforeach
              </tbody>
            </table>
          </div>
        </div> 
<br>
    <div class="card-footer text-muted">
      Autofletes Internacionales Halcon S.C.
    </div>
</div>
<!-- Datatable -->
<script type="text/javascript">
  $(document).ready(function() {
  $('#pedidos').DataTable({
    ordering: false,
      "language": {
          "sProcessing":     "Procesando...",
              "sLengthMenu":     "Mostrar _MENU_ registros",
              "sZeroRecords":    "No se encontraron coincicendias",
              "sEmptyTable":     "Ningún dato disponible en esta consulta",
              "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":    "",
              "sSearch":         "Buscar:",
              "sUrl":            "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                  "sFirst":    "Primero",
                  "sLast":     "Último",
                  "sNext":     "Siguiente",
                  "sPrevious": "Anterior"
              },
              "oAria": {
                  "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                  "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              },
              "buttons": {
                  "copy": "Copiar",
                  "colvis": "Visibilidad"
              }
          }
  });
} );
</script>
</div>
@endsection