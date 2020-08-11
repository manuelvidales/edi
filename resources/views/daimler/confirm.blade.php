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
                <td><strong>{{ $data[0] }}</strong></td>
                <td> <strong>{{ date ('d-m-Y', strtotime($data[6]))}}</strong></td>
                <td><strong>{{ date ('H:i', strtotime($data[7]))}}</strong></td>
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
                <input type="text" class="form-control" placeholder="{{ $data[1]}}" disabled>
                <br>
                <label for="inputAddress"><i class="fas fa-map-marker-alt"></i> Origen direccion</label>
                <input type="text" class="form-control" placeholder="{{$data[2]}}, {{$data[3]}}, {{$data[4]}}, {{$data[5]}}" disabled>
                </div>
                <div class="form-group col-md-6">
                <label for="inputPassword4"><i class="fas fa-warehouse"></i> Destino</label>
                <input type="text" class="form-control" placeholder="{{ $data[8]}}" disabled>
                <br>
                <label for="inputAddress2"><i class="fas fa-map-marker-alt"></i> Destino direccion</label>
                <input type="text" class="form-control" placeholder="{{$data[9]}}, {{$data[10]}}, {{$data[11]}}, {{$data[12]}}" disabled>
                </div>
            </div>
        </strong>

<!-- inicio Collapses paradas -->
      <p>
        @if($sql->stop8 ==! null)
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample1" aria-expanded="false" aria-controls="multiCollapseExample1">1er. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">2da. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample3" aria-expanded="false" aria-controls="multiCollapseExample3">3er. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample4" aria-expanded="false" aria-controls="multiCollapseExample4">4ta. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample5" aria-expanded="false" aria-controls="multiCollapseExample5">5ta. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample6" aria-expanded="false" aria-controls="multiCollapseExample6">6ta. Parada</button>
          <button class="btn btn-info" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Todas</button>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample1">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 1er. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop2 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop2_addres}}, {{$sql->stop2_city}}, {{$sql->stop2_state}}, {{$sql->stop2_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample2">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 2da. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop3 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop3_addres}}, {{$sql->stop3_city}}, {{$sql->stop3_state}}, {{$sql->stop3_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>          
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample3">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 3ra. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop4 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop4_addres}}, {{$sql->stop4_city}}, {{$sql->stop4_state}}, {{$sql->stop4_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample4">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 4ta. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop5 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop5_addres}}, {{$sql->stop5_city}}, {{$sql->stop5_state}}, {{$sql->stop5_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample3">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 5ta. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop6 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop6_addres}}, {{$sql->stop6_city}}, {{$sql->stop6_state}}, {{$sql->stop6_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample4">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 6ta. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop7 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop7_addres}}, {{$sql->stop7_city}}, {{$sql->stop7_state}}, {{$sql->stop7_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

        @elseif($sql->stop7 ==! null)
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample1" aria-expanded="false" aria-controls="multiCollapseExample1">1er. Parada</button>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">2da. Parada</button>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample3" aria-expanded="false" aria-controls="multiCollapseExample3">3er. Parada</button>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample4" aria-expanded="false" aria-controls="multiCollapseExample4">4ta. Parada</button>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample5" aria-expanded="false" aria-controls="multiCollapseExample5">5ta. Parada</button>
        <button class="btn btn-info" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Todas</button>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample1">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 1er. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop2 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop2_addres}}, {{$sql->stop2_city}}, {{$sql->stop2_state}}, {{$sql->stop2_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample2">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 2da. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop3 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop3_addres}}, {{$sql->stop3_city}}, {{$sql->stop3_state}}, {{$sql->stop3_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>          
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample3">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 3ra. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop4 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop4_addres}}, {{$sql->stop4_city}}, {{$sql->stop4_state}}, {{$sql->stop4_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample4">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 4ta. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop5 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop5_addres}}, {{$sql->stop5_city}}, {{$sql->stop5_state}}, {{$sql->stop5_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample3">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 5ta. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop6 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop6_addres}}, {{$sql->stop6_city}}, {{$sql->stop6_state}}, {{$sql->stop6_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
            </div>
          </div>

          @elseif($sql->stop6 ==! null)
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample1" aria-expanded="false" aria-controls="multiCollapseExample1">1er. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">2da. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample3" aria-expanded="false" aria-controls="multiCollapseExample3">3er. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample4" aria-expanded="false" aria-controls="multiCollapseExample4">4ta. Parada</button>
          <button class="btn btn-info" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Todas</button>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample1">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 1er. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop2 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop2_addres}}, {{$sql->stop2_city}}, {{$sql->stop2_state}}, {{$sql->stop2_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample2">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 2da. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop3 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop3_addres}}, {{$sql->stop3_city}}, {{$sql->stop3_state}}, {{$sql->stop3_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>          
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample3">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 3ra. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop4 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop4_addres}}, {{$sql->stop4_city}}, {{$sql->stop4_state}}, {{$sql->stop4_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample4">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 4ta. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop5 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop5_addres}}, {{$sql->stop5_city}}, {{$sql->stop5_state}}, {{$sql->stop5_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          @elseif($sql->stop5 ==! null)
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample1" aria-expanded="false" aria-controls="multiCollapseExample1">1er. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">2da. Parada</button>
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample3" aria-expanded="false" aria-controls="multiCollapseExample3">3er. Parada</button>
          <button class="btn btn-info" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Todas</button>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample1">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 1er. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop2 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop2_addres}}, {{$sql->stop2_city}}, {{$sql->stop2_state}}, {{$sql->stop2_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample2">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 2da. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop3 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop3_addres}}, {{$sql->stop3_city}}, {{$sql->stop3_state}}, {{$sql->stop3_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample3">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 3ra. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop4 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop4_addres}}, {{$sql->stop4_city}}, {{$sql->stop4_state}}, {{$sql->stop4_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
            </div>
          </div>

        @elseif($sql->stop4 ==! null)
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample1" aria-expanded="false" aria-controls="multiCollapseExample1">1er. Parada</button>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">2da. Parada</button>
        <button class="btn btn-info" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Todas</button>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample1">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 1er. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop2 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop2_addres}}, {{$sql->stop2_city}}, {{$sql->stop2_state}}, {{$sql->stop2_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample2">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 2da. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop3 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop3_addres}}, {{$sql->stop3_city}}, {{$sql->stop3_state}}, {{$sql->stop3_country}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

        @elseif($sql->stop3 ==! null)
          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample1" aria-expanded="false" aria-controls="multiCollapseExample1">1er. Parada</button>
          <button class="btn btn-info" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Todas</button>
          <div class="row">
            <div class="col">
              <div class="collapse multi-collapse" id="multiCollapseExample1">
                <div class="card card-body">
                  <div class="form-row">
                    <label for=""><i class="fas fa-truck-loading"></i> Sitio 1er. parada</label>
                  </div>
                  <div class="alert alert-secondary" role="alert">
                    <i class="fab fa-houzz"></i> <label for=""> {{ $sql->stop2 }}</label> <br>
                    <i class="fas fa-map-marker-alt"></i> <label style="font-size: 12px;">{{$sql->stop2_addres}}, {{$sql->stop2_city}}, {{$sql->stop2_state}}, {{$sql->stop2_country}} </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
            </div>
          </div>
        @endif
      </p>
<!-- fin Collapses paradas -->
    <hr>
<div class="row justify-content-md-center">
      <div class="col-md-3" style="text-align:center;">
        <form id="aceptarform">
                <input type="hidden" name="orderid" value="{{$data[0]}}">
                <input type="hidden" name="res" value="A">
                <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-check"></i> Aceptar</button>
              </form>
      </div>
      <div class=""></div>    
      <div class="col-md-3" style="text-align:center;">
              <form id="denyform">
                <input type="hidden" name="order" value="{{ $data[0]}}">
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