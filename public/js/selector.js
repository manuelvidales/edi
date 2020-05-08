$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function Selectedpais(){
  let select=document.getElementById("pais");
  let optionvalue=select.options[select.selectedIndex].value;
  let select1 = $('#selectores1');
  console.log(optionvalue);
    if (optionvalue == 'MX') {
        $(select1).html('');
        $(select1).prepend(`<label for="inputState">Estado</label>
        <select id="estadoMx" name="estado" class="form-control"></select>`);
    } else {
        $(select1).html('');
        $(select1).prepend(`<label for="inputState">Estado</label>
        <select id="estadoUsa" name="estado" class="form-control"></select>`);
    }
}
//muestra los estados
$(document).on('click', '.country', function(){
	$.getJSON('js/estadosMx.json', function(data) {
		$.each(data, function(key, value) {
			$("#estadoMx").append('<option value="' + key + '">' + value + '</option>');
		}); // close each()
	}); // close getJSON()
});
$(document).on('click', '.country', function(){ 	
	$.getJSON('js/estadosUsa.json', function(data) {
		$.each(data, function(key, value) {
			$("#estadoUsa").append('<option value="' + key + '">' + value + '</option>');
		}); // close each()
	}); // close getJSON()
});
//Editar
$(document).on('click', '.editar', function(){
	let id = $(this).closest('tr').data('id');
	let modal = $('#editarForm');
	$(name).html('');
	$('#editarMessage').html('');//limpia mensajes
	  $.ajax({
		  type:'GET',
		  url:'clientes/'+id,
		  success: function(data){
				console.log(data);
			  $(modal).find('#idhalcon').val(data.id_cliente);
			  $(modal).find('#idvisteon').val(data.cliente);
			  $(modal).find('#cliente').val(data.nombre);
			  $(modal).find('#direccion').val(data.direccion);
			  $(modal).find('#ciudad').val(data.ciudad);
			  $(modal).find('#cp').val(data.cp);
		  },
		  error: function(error){
			console.log(error);
		  }
	  });
  });
//Guardar datos
$('#editarForm').submit(function(e){
	e.preventDefault();
	let msg = $('#editarMessage');
	//datos del form
	let input1 = $('#editarForm input[name="idhalcon"]'),
	  input2 = $('#editarForm input[name="idvisteon"]'),
	  input3 = $('#editarForm input[name="cliente"]'),
	  input4 = $('#editarForm input[name="direccion"]'),
	  select5 = $('#editarForm select[name="pais"]'),
	  select6 = $('#editarForm select[name="estado"]'),
	  input7 = $('#editarForm input[name="ciudad"]'),
	  input8 = $('#editarForm input[name="cp"]');

	  let formData = {
		idhalcon: $(input1).val(),
		idvisteon: $(input2).val(),
		cliente: $(input3).val(),
		direccion: $(input4).val(),
		pais: $(select5).val(),
		estado: $(select6).val(),
		ciudad: $(input7).val(),
		cp: $(input8).val(),
	  }
	$.ajax({
	  type: 'POST',
	  url: 'clientes/actualizar',
	  dataType: 'json',
	  data: formData,
	  success: function(data){
		$(msg).html('');
		console.log(data);
		$(msg).prepend(`<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Se actualizo con exito!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>`);
	  },
	  error: function(error){
		$(msg).html('');
		console.log(error);
		$(msg).prepend(`<div class="alert alert-danger"><strong>Error al actualizar, favor de revisar campos</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></div>`);
	  }
	})
});
//Editar Selecion de Pais y Estado
function SelectedpaisEditar(){
	let select=document.getElementById("paisEditar");
	let optionvalue=select.options[select.selectedIndex].value;
	let selecteditar1 = $('#selectorEditar1');
	console.log(optionvalue);
    if (optionvalue == 'MX') {
        $(selecteditar1).html('');
        $(selecteditar1).prepend(`<label for="inputState">Estado</label>
        <select id="estadoEditarMx" name="estado" class="form-control"></select>`);
    } else {
        $(selecteditar1).html('');
        $(selecteditar1).prepend(`<label for="inputState">Estado</label>
        <select id="estadoEditarUsa" name="estado" class="form-control"></select>`);
    }
  }
  $(document).on('click', '.countryEditar', function(){
	  $.getJSON('js/estadosMx.json', function(data) {
		  $.each(data, function(key, value) {
			  $("#estadoEditarMx").append('<option value="' + key + '">' + value + '</option>');
		  }); // close each()
	  }); // close getJSON()
  });
  $(document).on('click', '.countryEditar', function(){
	$.getJSON('js/estadosUsa.json', function(data) {
		$.each(data, function(key, value) {
			$("#estadoEditarUsa").append('<option value="' + key + '">' + value + '</option>');
		}); // close each()
	}); // close getJSON()
});
//recarga page al cerrar el modal crear-actualizar
$('.reloadpage').on('hidden.bs.modal', function () {
	location.reload();
  });