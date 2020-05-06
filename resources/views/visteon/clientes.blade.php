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
                <option value="MX">Mexico</option>
                <option value="US">Estados Unidos</option>
                </select>
                <label style="color:red">{!! $errors->first('pais', '<small>:message</small><br>') !!}</label>
            </div>
            <div class="form-group col-md-3"  id="selectores1">
                <label for="inputState">Estado</label>
                <select id="estado" name="estado" class="form-control"></select>
                <label style="color:red">{!! $errors->first('estado', '<small>:message</small><br>') !!}</label>
            </div>
            <div class="form-group col-md-3"  id="selectores2">
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
  <table class="table table-hover">
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
      </tr>
    </thead>
    <tbody>
      @foreach ($clientes as $row)  
      <tr>
        <td>{{$row->id_cliente}}</td>
        <td>{{$row->cliente}}</td>
        <td>{{$row->nombre}}</td>
        <td>{{$row->direccion}}</td>
        <td>{{$row->ciudad}}</td>
        <td>{{$row->estado}}</td>
        <td>{{$row->pais}}</td>
        <td>{{$row->cp}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
{{$clientes->links()}}
</div>
<script src="{{ asset('js')}}/selector.js" ></script>
@endsection