@extends('layouts.app')
@section('content')
<div class="jumbotron">
    <div class="card shadow p-3 mb-5 bg-white rounded">
        <div class="card-header text-center">
        <h4>  Ordenes recibidas de Daimler</h4>
        </div>
        <div class="card-body">
        <div class="row justify-content-md-center">

          <div class="table-responsive">
            <table class="table table-sm table-striped table-hover" id="pedidos">
              <thead class="thead-light">
                  <tr>
                      <th>archivo</th>
                      <th>Id envio</th>
                      <th>Respuesta</th>
                      <th>Fecha recepcion</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($ships as $data)  
                  <tr>
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