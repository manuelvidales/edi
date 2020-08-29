
/*
 * ::Archivos EDI format .txt
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
//mostrar archivos
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