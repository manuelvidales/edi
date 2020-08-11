/*
 * MVidales: EDI respuesta para 990.
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#aceptarform').submit(function(e){
    e.preventDefault();
    
    let input1 = $('#aceptarform input[name="orderid"]');
    let input2 = $('#aceptarform input[name="res"]');

    let formData = {
        orderid: $(input1).val(),
        response: $(input2).val(),
    }
    $.ajax({
        type: 'POST',
        url: '/edidaimler/respuesta',
        data: formData,
        success: function(data){
          console.log(data);
          $('#confirmOrder').modal('show');
        },
        error: function(error){
            console.log(error);
        }
    });
    
});

$('#denyform').submit(function(e){
    e.preventDefault();
    
    let input1 = $('#denyform input[name="order"]');
    let input2 = $('#denyform input[name="deny"]');

    let formData = {
        orderid: $(input1).val(),
        response: $(input2).val(),
    }
    $.ajax({
        type: 'POST',
        url: '/edidaimler/respuesta',
        data: formData,
        success: function(data){
          console.log(data);
          $('#confirmOrder').modal('show');
        },
        error: function(error){
            console.log(error);
        }    
    });
    
});

//Cierra la pagina al cerrar el modal
$("#confirmOrder").on('hidden.bs.modal', function () {
    //window.close();
    window.location = "/edidaimler/alert"
});