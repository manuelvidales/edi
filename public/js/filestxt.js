
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
            if (data == 'null') {
                $(fail).append(`<div class="alert alert-warning" role="alert">El archivo no se encuentra disponible</div>`);
            } else {
              $(filename).append(`<label>`+name+`</label>`);
              for (x in data) {
                $(texto).append(``+data[x]+`~<br/>`);
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
//actualizar los contadores del encabezado
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

        $(store).append(``+data.total+``);
        if (data.nuevos == 0) {
          $(recent).append(`
          <div class="text-white text-center bg-dark shadow rounded">
              <div class="row justify-content-md-center">
              <div class="col-2"><i class="fas fa-file-download fa-5x"></i></div>
              <div class="col-2">
              <h1 class="font-weight-bold"><span class="badge badge-primary">
                <div>`+data.nuevos+`</div>
              </span></h1>
              </div>
            </div>
            <div class="card-body">
              <h5 class="card-title"><button type="button" class="btn btn-outline-light">En recepcion</button></h5>
            </div>
          </div>
          `);
          } else {
            $(recent).append(`
            <div class="text-success text-center bg-dark shadow rounded">
                <div class="row justify-content-md-center">
                <div class="col-2"><i class="fas fa-file-download fa-5x"></i></div>
                <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
                  <div>`+data.nuevos+`</div>
                </span></h1></div>
              </div>
              <div class="card-body">
                <h5 class="card-title"><button type="button" class="btn btn-outline-light">En recepcion</button></h5>
              </div>
            </div>
            `);
          }

        if (data.proceso == 0) {
        $(process).append(`
        <div class="text-white text-center bg-dark shadow rounded">
            <div class="row justify-content-md-center">
            <div class="col-2"><i class="fas fa-hourglass-half fa-5x"></i></div>
            <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
              <div>`+data.proceso+`</div>
            </span></h1></div>
          </div>
          <div class="card-body">
          <h5 class="card-title"><button type="button" class="btn btn-outline-light">En proceso</button></h5>
          </div>
        </div>
        `);
        } else {
          $(process).append(`
          <div class="text-secondary text-center bg-dark shadow rounded">
              <div class="row justify-content-md-center">
              <div class="col-2"><i class="fas fa-hourglass-half fa-5x"></i></div>
              <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
                <div>`+data.proceso+`</div>
              </span></h1></div>
            </div>
            <div class="card-body">
            <h5 class="card-title"><button type="button" class="btn btn-outline-light">En proceso</button></h5>
            </div>
          </div>
          `);
        }

        if (data.fallos == 0) {
          $(warning).append(`
            <div class="text-white text-center bg-dark shadow rounded">
              <div class="row justify-content-md-center">
              <div class="col-2"><i class="fas fa-exclamation-triangle fa-5x"></i></div>
              <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
                <div>`+data.fallos+`</div>
              </span></h1></div>
            </div>
            <div class="card-body">
            <h5 class="card-title"><button type="button" class="btn btn-outline-light">Advertencias</button></h5>
            </div>
          </div>
          `);
          } else {
            $(warning).append(`
              <div class="text-warning text-center bg-dark shadow rounded">
                <div class="row justify-content-md-center">
                <div class="col-2"><i class="fas fa-exclamation-triangle fa-5x"></i></div>
                <div class="col-2"><h1 class="font-weight-bold"><span class="badge badge-primary">
                  <div>`+data.fallos+`</div>
                </span></h1></div>
              </div>
              <div class="card-body">
              <h5 class="card-title"><button type="button" class="btn btn-outline-light">Advertencias</button></h5>
              </div>
            </div>
            `);
          }
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
                <td>procesado</td>
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
                <td>recepcion</td>
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
// tabla code 214 Gps
$(document).on('click', '.code214gps', function(){
  let tabla = $('#tablaEdicode');
  $(tabla).html('');
  $(tabla).append(`
  <div class="card shadow p-3 mb-5 bg-white rounded">
    <div class="card-body">
      <div class="row justify-content-md-center">
        <div class="table-responsive">
          <table class="table table-sm table-striped table-hover" id="code214gps">
            <thead class="thead-light">
              <tr>
                <th>Datos</th>
                <th>Tender</th>
                <th>Tipo</th>
                <th>Longitud</th>
                <th>Latitud</th>
                <th>Mapa</th>
                <th>Enviado</th>
              </tr>
            </thead>
            <tbody id="mostrarfiles214gps">

            </tbody>
          </table> 
        </div>
      </div>
    </div>
  </div>
  `);
    $.ajax({
      type:'GET',
      url:'/filesgps214',
      success: function(data){
        for (i = 0; i < data.length; i++){
            let dt = new Date(data[i].created_at);
            //dar fomato fecha dd/mm/yyy HH:mm
            fecha = (`${dt.getDate().toString().padStart(2, '0')}/${(dt.getMonth()+1).toString().padStart(2, '0')}/${
                dt.getFullYear().toString().padStart(4, '0')} ${
                dt.getHours().toString().padStart(2, '0')}:${
                dt.getMinutes().toString().padStart(2, '0')}`
                );
              $('#mostrarfiles214gps').append(`
              <tr data-id="`+data[i].id+`" data-name="`+data[i].filename+`">
                <td><a href="#" class="verdatos214" data-toggle="modal" data-target="#verarchivo"><i class="fas fa-eye"></i></a></td>
                <td>`+data[i].shipment_identification_number+`</td>
                <td>`+data[i].code+`</td>
                <td>`+data[i].longitude+`</td>
                <td>`+data[i].latitude+`</td>
                <td data-lat='`+data[i].latitude+`' data-lng='`+data[i].longitude+`' data-unidad='`+data[i].unidad+`'>
                <a href="#" class="mapa" data-toggle="modal" data-target="#vermapa" >
                <i class="fas fa-map-marked-alt fa-lg"></i>
                </a>
                </td>
                <td>`+fecha+`</td>
              </tr>
              `);
        }
        $('#code214gps').DataTable({
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
//Mostrar campos del 214 gps
$(document).on('click', '.verdatos214', function(){
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
        url:'/getfile214/'+id,
        success: function(data){
          $(texto).html('');
          if (data == 'null') {
              $(fail).append(`<div class="alert alert-warning" role="alert">Los campos no se encuentra disponible</div>`);
          } else {
            $(filename).append(`<label>`+name+`</label>`);
            let dt = new Date(data.date_time);
            fechalg = (`${ dt.getFullYear().toString().padStart(4, '0')}${(dt.getMonth()+1).toString().padStart(2, '0')}${dt.getDate().toString().padStart(2, '0')}`);
            fechasm = (`${ dt.getFullYear().toString().substr(-2)}${(dt.getMonth()+1).toString().padStart(2, '0')}${dt.getDate().toString().padStart(2, '0')}`);
            hora = (`${ dt.getHours().toString().padStart(2, '0')}${dt.getMinutes().toString().padStart(2, '0')}`);
            $(texto).append(`ISA*00*          *00*          *`+data.id_qualifier_receiver+`*`+data.id_receiver+`*`+data.id_qualifier_sender+`*`+data.id_sender+`*`+fechasm+`*`+hora+`*`+data.version_number+`*`+data.control_number+`*`+data.idnew+`*0*P*^~<br/>GS*QM*`+data.id_receiver+`*`+data.sender_code+`  *`+fechalg+`*`+hora+`*`+data.id_incremental+`*`+data.agency_code+`*`+data.industry_identifier+`~<br/>
            ST*214*0001~<br/>
            B10*`+data.reference_identification+`*`+data.shipment_identification_number+`*`+data.alpha_code+`~<br/>
            LX*1~<br/>
            AT7*`+data.status_code+`*`+data.reason_code+`***`+fechalg+`*`+hora+`*CT~<br/>
            MS1****`+data.longitude+`*`+data.latitude+`*`+data.code_longitude+`*`+data.code_latitude+`~<br/>
            MS2*`+data.alpha_code+`*`+data.equipment+`~<br/>
            SE*7*0001~<br/>
            GE*1*`+data.id_incremental+`~<br/>
            IEA*1*`+data.idnew+`~<br/>`);
            }
        }
    });
});
//mapa en modal
$(document).on('click', '.mapa', function(){
  let lat = $(this).closest('td').data('lat');
  let lon = $(this).closest('td').data('lng');
  let unidad = $(this).closest('td').data('unidad');
  let modalmap = $('#openmapa');
  
  $(modalmap).html('');
  $('#openmapa').append(`<div id="mapid" style="width: 760px; height: 400px; position: relative;" class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom" tabindex="0">
  </div>`);

  L.mapbox.accessToken = 'pk.eyJ1IjoiaGFsY29uZGV2ZWxvcGVyIiwiYSI6ImNrZXN5Mml3MDFxb3kyenBuNndpazlueHoifQ._BCjaRBr77FE6gJ7Zl1CHA';

  let mymap = L.mapbox.map('mapid')
    .setView([lat, -lon], 13)
    .addLayer(L.mapbox.styleLayer('mapbox://styles/halcondeveloper/ckeuc490g9v0a19k2yi9rhaua'));

      // let mapboxTiles = L.tileLayer('https://api.mapbox.com/styles/v1/halcondeveloper/ckeuc490g9v0a19k2yi9rhaua/tiles/{z}/{x}/{y}?access_token=' + L.mapbox.accessToken, {
      //     attribution: '© <a href="https://www.mapbox.com/feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      //     tileSize: 512,
      //     zoomOffset: -1
      //   });
      // let mymap = L.map('mapid')
      //   .addLayer(mapboxTiles)
      //   .setView([lat, -lon], 15);

      /* Mostrar icono en mapa*/
  L.marker([lat, -lon]).addTo(mymap).bindPopup("<b>"+unidad+"</b>").openPopup();
});