@extends('layouts.app')
@section('content')

  <div class="jumbotron p-1">
    <div class="card-deck p-4">
      <div id="" class="card">
        <div class="text-white text-center bg-dark shadow rounded">
          <div class="row justify-content-md-center">
            <div class="col-3"><i class="fas fa-inbox fa-5x"></i></div>
            <div class="col-3"><h1 class="font-weight-bold"><span class="badge badge-primary">
              <div id="almacen">{{ $filestore }}</div>
            </span></h1></div>
          </div>
          <div class="card-body">
            <h5 class="card-title"><button type="button" class="btn btn-outline-light">Documentos totales</button></h5>
            <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
          </div>
        </div>
      </div>

      <div id="nuevos" class="card">
        @if ($filesnew ==0)
          <div class="text-white text-center bg-dark shadow rounded">    
        @else
          <div class="text-success text-center bg-dark shadow rounded">    
        @endif        
            <div class="row justify-content-md-center">
            <div class="col-2"><i class="fas fa-file-download fa-5x"></i></div>
            <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
              <div >{{ $filesnew }}</div>
            </span></h1></div>
          </div>
          <div class="card-body">
            <h5 class="card-title"><button type="button" class="btn btn-outline-light">Recepcion archivos</button></h5>
            <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
          </div>
        </div>
      </div>

      <div id="proceso" class="card">
        @if ($fileprocess == 0)
          <div class="text-white text-center bg-dark shadow rounded">
        @else
          <div class="text-secondary text-center bg-dark shadow rounded">  
        @endif
          <div class="row justify-content-md-center">
            <div class="col-2"><i class="fas fa-hourglass-half fa-5x"></i></div>
            <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
              <div >{{ $fileprocess }}</div>  
            </span></h1></div>
          </div>
          <div class="card-body">
            <h5 class="card-title"><button type="button" class="btn btn-outline-light">En proceso</button></h5>
            <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
          </div>
        </div>
      </div>
  
      <div id="warning" class="card">
        @if ($warning ==0)
          <div class="text-white text-center bg-dark shadow rounded">
        @else
          <div class="text-warning text-center bg-dark shadow rounded">
        @endif
          <div class="row justify-content-md-center">
          <div class="col-2"><i class="fas fa-exclamation-triangle fa-5x"></i></div>
          <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
            <div>{{ $warning }}</div>
          </span></h1>
          </div>
        </div>
        <div class="card-body">
          <h5 class="card-title"><button type="button" class="btn btn-outline-light">Advertencias</button> </h5>
          <!-- <p class="card-text"><small class="">actualizado hace 3 minutos</small></p> -->
        </div>
      </div>
      </div>  
    </div>
  </div>

<ul class="nav nav-tabs nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active code204" id="pills-204-tab"  data-toggle="pill" href="#" role="tab" aria-controls="pills-204" aria-selected="true">204</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link code824" id="pills-824-tab" data-toggle="pill" href="#" role="tab" aria-controls="pills-824" aria-selected="false">824</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link code214gps" id="pills-214gps-tab" data-toggle="pill" href="#" role="tab" aria-controls="pills-214gps" aria-selected="false">214(gps)</a>
  </li>
</ul>

<div class="jumbotron p-3">
  <div id="tablaEdicode">
    <div class="card shadow p-3 mb-5 bg-white rounded">
        <div class="card-body">
        <div class="row justify-content-md-center">
          <div class="table-responsive">
            <table class="table table-sm table-striped table-hover" id="pedidos">
              <thead class="thead-light">
                  <tr>
                      <th>Archivo</th>
                      <th>Tender</th>
                      <th>Tipo</th>
                      <th>Propósito</th>
                      <th>Confirmacion</th>                      
                      <th>Fecha recepcion</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($files204 as $data)
                <tr data-id="{{$data->id}}" data-name="{{$data->filename}}" data-ship="{{$data->shipment_id}}">
                    <td><a href="{{ url('getfile/'.$data->id) }}"><i class="fas fa-download"></i></a> <a href="#" class="viewfiles" data-toggle="modal" data-target="#verarchivo"><i class="fas fa-eye"></i></a></td>
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
                            <span class="badge badge-pill badge-light">Pendiente </span>
                            @include('daimler.reenvio')
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
                    <td>{{ date ('d-m-Y H:i', strtotime($data->created_at))}}
                    </td>
                    </tr>
                    @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <br>
    <div class="card-footer text-muted">
        Autofletes Internacionales Halcon S.C.
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="verarchivo" tabindex="-1" aria-labelledby="verarchivoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filename"> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class="modal-body">
          <div id="noExiste"></div>
          <div id="mostrartexto"></div>
        </div>

    </div>
  </div>
</div>
<!-- modal Confirmacion Reenvio correo-->
  <div class="modal fade" id="confirmEnvio" tabindex="-1" role="dialog" aria-labelledby="confirmEnvioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-body alert alert-dark">
        <h4 class="modal-title center" id="confirmEnvioLabel">
          Reenviado correo, espere..!
        </h4>
      </div>
    </div>
  </div>
<!-- Modal MApa-->
<div class="modal fade" id="vermapa" tabindex="-1" role="dialog" aria-labelledby="vermapaLabel">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body" >
        <div id="openmapa" ></div>

      </div>
    </div>
  </div>
</div>

<script src="{{ asset('js')}}/filestxt.js" ></script>
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

@endsection