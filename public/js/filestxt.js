
/*
 * ::Archivos EDI format .txt
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
//leer texto archivos
$(document).on('click', '.viewfiles', function(){
    let id = $(this).closest('tr').data('id');
    let name = $(this).closest('tr').data('name');
    let texto = $('#mostrartexto');
    let fail = $('#noExiste');
    let filename = $('#filename');
    $(texto).html('');
    $(fail).html('');
    $(filename).html('');
      $.ajax({
          type:'GET',
          url:'/viewfile/'+id,
          success: function(data){
            $(texto).html('');
            console.log(data);
            if (data == 'null') {
                $(fail).append(`<div class="alert alert-warning" role="alert">El archivo no se encuentra disponible</div>`);
            } else {
              $(filename).append(`<label>`+name+`</label>`);
              for (x in data) {
                $(texto).append(``+data[x]+`<br/>`);
              }
            }
          }
      });
  });
//reenviar correo
  $(document).on('click', '.reenviarmail', function(){
    let ship = $(this).closest('tr').data('ship');
    $('#confirmEnvio').modal('show');
      $.ajax({
          type:'GET',
          url:'/edidaimlernotifica/'+ship,
          success: function(data){
            console.log(data);
          }
      });
  });
//update header contadores
$(document).ready(function () {
  let store = $('#almacen');
  let recent = $('#nuevos');
  let process = $('#proceso');
  let warning = $('#warning');
  setInterval(function() {
      $.get("/edidaimlerheader", function (data) {
        console.log(data);
        $(store).html('');
        $(recent).html('');
        $(process).html('');
        $(warning).html('');
        $(store).append(``+data[1]+data[2]+data[3]+``);
        $(recent).append(``+data[5]+``);
        $(process).append(``+data[7]+``);
        $(warning).append(``+data[9]+``);
      });
  }, 60000);//(1min)
});
//recargar pagina al seleccionar 204
$(document).on('click', '.code204', function(){
  location.reload();
});
// tabla code 824
$(document).on('click', '.code824', function(){
  let tabla = $('#tablaEdicode');
  $(tabla).html('');
  $(tabla).append(`
  <div class="card shadow p-3 mb-5 bg-white rounded">
    <div class="card-body">
      <div class="row justify-content-md-center">
        <div class="table-responsive">
          <table class="table table-sm table-striped table-hover" id="code824">
            <thead class="thead-light">
              <tr>
                <th>Archivo</th>
                <th>Tender</th>
                <th>Tipo</th>
                <th>Estatus</th>
                <th>Fecha recepcion</th>
              </tr>
            </thead>
            <tbody id="mostrarfiles824">

            </tbody>
          </table> 
        </div>
      </div>
    </div>
  </div>
  `);
    $.ajax({
      type:'GET',
      url:'/files824',
      success: function(data){
        for (i = 0; i < data.length; i++){
            let dt = new Date(data[i].created_at);
            //dar fomato fecha dd/mm/yyy HH:mm
            fecha = (`${dt.getDate().toString().padStart(2, '0')}/
                ${(dt.getMonth()+1).toString().padStart(2, '0')}/${
                dt.getFullYear().toString().padStart(4, '0')} ${
                dt.getHours().toString().padStart(2, '0')}:${
                dt.getMinutes().toString().padStart(2, '0')}`
                );
            if (data[i].status == 0) {
              $('#mostrarfiles824').append(`
              <tr data-id="`+data[i].id+`" data-name="`+data[i].filename+`">
                <td> <a href="/getfile/`+data[i].id+`"><i class="fas fa-download"></i></a> <a href="#" class="viewfiles" data-toggle="modal" data-target="#verarchivo"><i class="fas fa-eye"></i></a>
                </td>
                <td>`+data[i].shipment_id+`</td>
                <td>`+data[i].code+`</td>
                <td>recepcion</td>
                <td>`+fecha+`</td>
              </tr>
              `);
            } else {
            $('#mostrarfiles824').append(`
              <tr data-id="`+data[i].id+`">
                <td> <a href="/getfile/`+data[i].id+`"><i class="fas fa-download"></i></a> <a href="#" class="viewfiles" data-toggle="modal" data-target="#verarchivo"><i class="fas fa-eye"></i></a>
                </td>
                <td>`+data[i].shipment_id+`</td>
                <td>`+data[i].code+`</td>
                <td>procesado</td>
                <td>`+fecha+`</td>
                </tr>
              `);
            }
        }
        $('#code824').DataTable({
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
      }
    });
});
