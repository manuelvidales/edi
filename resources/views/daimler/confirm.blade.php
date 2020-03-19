@extends('layouts.app')
@section('content')
<div class="jumbotron">

    <div class="card shadow p-3 mb-5 bg-white rounded">
        <div class="card-header text-center">
        <h4>  Detalle de orden </h4>
        </div>
        <div class="card-body">
        <div class="row justify-content-md-center">
        <div style="text-align:center;">
          <table class="table table-md table-borderless table-responsive text-center">
            <thead>
              <tr>
                <th><span class="badge badge-pill badge-primary">Id de envio </span></th>
                <th><span class="badge badge-pill badge-primary">Fecha</span></th>
                <th><span class="badge badge-pill badge-primary">Hora</span></th>                
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>{{ $data->shipment_identification_number }}</strong></td>
                <td> <strong>{{ date ('d-m-Y', strtotime($data->load_date_1))}}</strong></td>
                <td><strong>{{ date ('H:i', strtotime($data->load_time_1))}}</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
        </div>

    <hr>

        <strong>
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="inputEmail4"><i class="fas fa-warehouse"></i> Origen</label>
                <input type="text" class="form-control" placeholder="{{ $data->origin}}" disabled>
                </div>
                <div class="form-group col-md-6">
                <label for="inputPassword4"><i class="fas fa-warehouse"></i> Destino</label>
                <input type="text" class="form-control" placeholder="{{ $data->stop1}}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label for="inputAddress"><i class="fas fa-map-marker-alt"></i> Origen direccion</label>
            <input type="text" class="form-control" placeholder="{{ $data->addres_origin}}, {{ $data->city_origin}}, {{ $data->state_origin}}, {{ $data->country_origin}}" disabled>
            </div>
            <div class="form-group">
                <label for="inputAddress2"><i class="fas fa-map-marker-alt"></i> Destino direccion</label>
                <input type="text" class="form-control" placeholder="{{ $data->addres_stop1}}, {{ $data->city_stop1}}, {{ $data->state_stop1}}, {{ $data->country_stop1}}" disabled>
            </div>
        </strong>
    <hr>       
<div class="row justify-content-md-center">
      <div class="col-md-3" style="text-align:center;">
        <form id="aceptarform">
                <input type="hidden" name="orderid" value="{{ $data->shipment_identification_number}}">
                <input type="hidden" name="res" value="A">
                <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-check"></i> Aceptar</button>
              </form>
      </div>
      <div class=""></div>    
      <div class="col-md-3" style="text-align:center;">
              <form id="denyform">
                <input type="hidden" name="order" value="{{ $data->shipment_identification_number}}">
                <input type="hidden" name="deny" value="D">
                <button type="submit" class="btn btn-danger btn-lg"><i class="fas fa-times"></i> Rechazar</button>
              </form>
      </div>
</div>
<br>
    <div class="card-footer text-muted">
      Autofletes Internacionales Halcon S.C.
    </div>
<!-- Modal -->
<div class="modal fade" id="confirmOrder" tabindex="-1" role="dialog" aria-labelledby="confirmOrderLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-body ">
      <div class="card shadow-lg p-4 mb-5 bg-white rounded text-center">
        <div class="card-body">
            <div class="alert alert-success" role="alert">
              <p class="card-text">
                <i class="fas fa-paper-plane fa-5x" style="color: green;"></i>
              </p>
             <h2 class="display-4">Respuesta enviada </h2>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<script src="{{ asset('js')}}/ediresponse.js" ></script>
</div>
@endsection