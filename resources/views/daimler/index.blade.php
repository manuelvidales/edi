@extends('layouts.app')
@section('content')

<div class="jumbotron p-2">

<div class="card-deck p-4">
  <div class="card text-white text-center bg-dark shadow rounded">
    <div class="row justify-content-md-center">
      <div class="col-3"><i class="fas fa-inbox fa-5x"></i></div>
      <div class="col-3"><h1 class="font-weight-bold"><span class="badge badge-primary">
          {{ $filestore }}
      </span></h1></div>
    </div>
    <div class="card-body">
      <h5 class="card-title"><button type="button" class="btn btn-outline-light">Documentos totales</button></h5>
      <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
    </div>
  </div>

  @if ( $filesnew == 0)
  <div class="card text-white text-center bg-dark">    
  @else
  <div class="card text-success text-center bg-dark">
  @endif
    <div class="row justify-content-md-center">
      <div class="col-2"><i class="fas fa-file-download fa-5x"></i></div>
      <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
          {{ $filesnew }}
      </span></h2></div>
    </div>
    <div class="card-body">
      <h5 class="card-title"><button type="button" class="btn btn-outline-light">Recepcion archivos</button></h5>
      <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
    </div>
  </div>

  <div class="card text-white text-center bg-dark">
    <div class="row justify-content-md-center">
      <div class="col-2"><i class="fas fa-hourglass-half fa-5x"></i></div>
      <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
          {{ $fileprocess }}
      </span></h2></div>
    </div>
    <div class="card-body">
      <h5 class="card-title"><button type="button" class="btn btn-outline-light">En proceso</button></h5>
      <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
    </div>
  </div>

  @if ($warning == 0)
  <div class="card text-white text-center bg-dark">    
  @else
  <div class="card text-warning text-center bg-dark">
  @endif
    <div class="row justify-content-md-center">
      <div class="col-2"><i class="fas fa-exclamation-triangle fa-5x"></i></div>
      <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
        {{ $warning }}
      </span></h2></div>
    </div>
    <div class="card-body">
      <h5 class="card-title"><button type="button" class="btn btn-outline-light">Advertencias</button> </h5>
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
                      <th>Archivo</th>
                      <th>Id tender</th>
                      <th>Tipo</th>
                      <th>Propósito</th>
                      <th>Confirmacion</th>
                      <th>Fecha recepcion</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($files204 as $data)

                  <tr>
                    <td><a href="{{ url('getfile/'.$data->id) }}"><i class="fas fa-download"></i></a></td>
                    <td>{{$data->shipment_id}}</td>
                    <td>{{$data->code}}</td>
                    
                      @if ($data->purpose_code == '00')
                        <td> Nueva orden </td>
                        <td>
                          @if ($data->response == 'A')
                            <span class="badge badge-pill badge-success">Realizada <i class="fas fa-check fa-xs"></i></span>
                          @elseif ($data->response == 'D')
                            <span class="badge badge-pill badge-success">Realizada <i class="fas fa-times fa-xs"></i></span>
                          @else
                            <span class="badge badge-pill badge-light">Pendiente <i class="fas fa-exclamation-triangle fa-xs"></i></span>
                          @endif
                        </td>
                      @elseif(($data->purpose_code == '05'))
                        <td> Actualizar orden </td>
                        <td> no aplica </td>
                      @elseif(($data->purpose_code == '01'))
                        <td> Cancelar orden </td>
                        <td> no aplica </td>
                      @else
                        <td> sin procesar </td>
                        <td> sin procesar </td>
                      @endif


                    <td>{{ date ('d-m-Y H:i', strtotime($data->created_at))}}</tr>
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