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
          },
      });
  });