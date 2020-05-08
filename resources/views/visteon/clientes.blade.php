@extends('layouts.app')
@section('content')
<div class="container p-4">
    <form action="{{route('visteon.clientes')}}" method="post" enctype="multipart/form-data">
      @csrf
        <div class="form-row">
          <div class="form-group col-md-2">
            <label>Id. halcon</label>
            <input type="text" class="form-control" name="idhalcon" value="{{old('idhalcon')}}">
            <label style="color:red">{!! $errors->first('idhalcon', '<small>:message</small><br>') !!}</label>
          </div>
          <div class="form-group col-md-2">
            <label>Id. visteon</label>
            <input type="text" class="form-control" name="idvisteon" value="{{old('idvisteon')}}">
            <label style="color:red">{!! $errors->first('idvisteon', '<small>:message</small><br>') !!}</label>
          </div>
          <div class="form-group col-md-8">
            <label for="inputEmail4">Cliente</label>
            <input type="cliente" class="form-control" name="cliente" value="{{old('cliente')}}">
            <label style="color:red">{!! $errors->first('cliente', '<small>:message</small><br>') !!}</label>
          </div>
        </div>
        <div class="form-group">
          <label for="inputAddress">Direccion</label>
          <input type="text" class="form-control" name="direccion" value="{{old('direccion')}}">
          <label style="color:red">{!! $errors->first('direccion', '<small>:message</small><br>') !!}</label>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
            <label for="inputZip">Pais</label>
            <select id="pais" class="form-control country" name="pais" onchange="Selectedpais();">
                <option value="">Selecionar...</option>
                <option value="MX" class="form-control countryMx">Mexico</option>
                <option value="US" class="form-control countryUsa">Estados Unidos</option>
                </select>
                <label style="color:red">{!! $errors->first('pais', '<small>:message</small><br>') !!}</label>
            </div>
            <div class="form-group col-md-3"  id="selectores1">
                <label for="inputState">Estado</label>
                <select id="" name="estado" class="form-control"></select>
                <label style="color:red">{!! $errors->first('estado', '<small>:message</small><br>') !!}</label>
            </div>
            <div class="form-group col-md-3"  id="">
                <label for="inputCity">Ciudad</label>
                <input type="text" class="form-control" name="ciudad" value="{{old('ciudad')}}">
                <label style="color:red">{!! $errors->first('ciudad', '<small>:message</small><br>') !!}</label>
            </div>        
          <div class="form-group col-md-2">
            <label for="inputZip">Codigo Postal</label>
            <input type="text" class="form-control" name="cp" value="{{old('cp')}}">
            <label style="color:red">{!! $errors->first('cp', '<small>:message</small><br>') !!}</label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">Guardar</button>
    </form>
<hr>
<div>
  <table class="table table-hover table-sm">
    <thead>
      <tr class="table-info">
        <th scope="col">Halcon</th>
        <th scope="col">Visteon</th>
        <th scope="col">Nombre de cliente</th>
        <th scope="col">Direccion</th>
        <th scope="col">Ciudad</th>
        <th scope="col">Estado</th>
        <th scope="col">Pais</th>
        <th scope="col">C.P.</th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($clientes as $row)  
      <tr data-id="{{ $row->id_cliente}}">
        <td>{{$row->id_cliente}}</td>
        <td>{{$row->cliente}}</td>
        <td>{{$row->nombre}}</td>
        <td>{{$row->direccion}}</td>
        <td>{{$row->ciudad}}</td>
        <td>{{$row->estado}}</td>
        <td>{{$row->pais}}</td>
        <td>{{$row->cp}}</td>
        <td><a href="#" type="button" class="btn btn-info btn-sm editar" data-toggle="modal" data-target="#editar"> editar </a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
      
    <!-- Modal Editar Asistencias-->
    <div class="modal fade bd-example-modal-xl reloadpage" id="editar" tabindex="-1" role="dialog" aria-labelledby="editarLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" >Editar</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div id="editarMessage"></div>
              
                <form id="editarForm">

                  <div id="camposEditar">
      <!-- inicio va Dentro de JS  -->
                  <div class="form-row">
                    <div class="form-group col-md-2">
                      <label>Id. halcon</label>
                      <input type="text" class="form-control" name="idhalcon" id="idhalcon" value="" required>
                    </div>
                    <div class="form-group col-md-2">
                      <label>Id. visteon</label>
                      <input type="text" class="form-control" name="idvisteon" id="idvisteon" value="" required>
                    </div>
                    <div class="form-group col-md-8">
                      <label for="inputEmail4">Cliente</label>
                      <input type="cliente" class="form-control" name="cliente" id="cliente" value="" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputAddress">Direccion</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" value="" required>
                  </div>
                  <div class="form-row">
                      <div class="form-group col-md-3">
                      <label for="inputZip">Pais</label>
                      <select id="paisEditar" class="form-control countryEditar" name="pais" onchange="SelectedpaisEditar();" required>
                          <option value="">Selecionar...</option>
                          <option value="MX">Mexico</option>
                          <option value="US">Estados Unidos</option>
                          </select>
                      </div>
                      <div class="form-group col-md-3"  id="selectorEditar1">
                          <label for="inputState">Estado</label>
                          <select id="estadoEditar" name="estado" class="form-control"></select>
                      </div>
                      <div class="form-group col-md-3">
                          <label for="inputCity">Ciudad</label>
                          <input type="text" class="form-control" name="ciudad" id="ciudad" value="">
                      </div>        
                    <div class="form-group col-md-2">
                      <label for="inputZip">Codigo Postal</label>
                      <input type="text" class="form-control" name="cp" id="cp" value="">
                    </div>
                  </div>

      <!-- fin va Dentro de JS  -->

            </div>

          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-warning">Actualizar</button>
            </div>
                </form>
        </div>
      </div>
    </div>
</div>

{{$clientes->links()}}
</div>
<script src="{{ asset('js')}}/selector.js" ></script>
@endsection