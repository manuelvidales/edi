$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function Selectedpais(){
  let select=document.getElementById("pais");
  let optionvalue=select.options[select.selectedIndex].value;
  let select1 = $('#selectores1');
  let select2 = $('#selectores2');
  console.log(optionvalue);
    if (optionvalue == 'MX') {
        $(select1).html('');
        $(select2).html('');
        $(select1).prepend(`<label for="inputState">Estado</label>
        <select id="estado" name="estado" class="form-control"></select>`);
        $(selectores2).prepend(`<label for="inputCity">Ciudad</label>
        <input type="text" class="form-control" name="ciudad">`);
    } else {
        $(select1).html('');
        $(select2).html('');
        $(select1).prepend(`<label for="inputState">Estado</label>
        <select id="" name="estado" class="form-control">
			<option value="AL">Alabama</option>
			<option value="AK">Alaska</option>
			<option value="AZ">Arizona</option>
			<option value="AR">Arkansas</option>
			<option value="CA">California</option>
			<option value="CO">Colorado</option>
			<option value="CT">Connecticut</option>
			<option value="DE">Delaware</option>
			<option value="DC">District Of Columbia</option>
			<option value="FL">Florida</option>
			<option value="GA">Georgia</option>
			<option value="HI">Hawaii</option>
			<option value="ID">Idaho</option>
			<option value="IL">Illinois</option>
			<option value="IN">Indiana</option>
			<option value="IA">Iowa</option>
			<option value="KS">Kansas</option>
			<option value="KY">Kentucky</option>
			<option value="LA">Louisiana</option>
			<option value="ME">Maine</option>
			<option value="MD">Maryland</option>
			<option value="MA">Massachusetts</option>
			<option value="MI">Michigan</option>
			<option value="MN">Minnesota</option>
			<option value="MS">Mississippi</option>
			<option value="MO">Missouri</option>
			<option value="MT">Montana</option>
			<option value="NE">Nebraska</option>
			<option value="NV">Nevada</option>
			<option value="NH">New Hampshire</option>
			<option value="NJ">New Jersey</option>
			<option value="NM">New Mexico</option>
			<option value="NY">New York</option>
			<option value="NC">North Carolina</option>
			<option value="ND">North Dakota</option>
			<option value="OH">Ohio</option>
			<option value="OK">Oklahoma</option>
			<option value="OR">Oregon</option>
			<option value="PA">Pennsylvania</option>
			<option value="RI">Rhode Island</option>
			<option value="SC">South Carolina</option>
			<option value="SD">South Dakota</option>
			<option value="TN">Tennessee</option>
			<option value="TX">Texas</option>
			<option value="UT">Utah</option>
			<option value="VT">Vermont</option>
			<option value="VA">Virginia</option>
			<option value="WA">Washington</option>
			<option value="WV">West Virginia</option>
			<option value="WI">Wisconsin</option>
			<option value="WY">Wyoming</option>
		</select>`);
    $(selectores2).prepend(`<label for="inputCity">Ciudad</label>
    <input type="text" class="form-control" name="ciudad">`);
    }
}
//solo Estados no mostrara ciudades
$(document).on('click', '.country', function(){
 		
	$.getJSON('js/estados.json', function(data) {
		$.each(data, function(key, value) {
			$("#estado").append('<option value="' + key + '">' + value + '</option>');
		}); // close each()
	}); // close getJSON()

});