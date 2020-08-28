<a href="#" data-toggle="modal" data-target="#reenvio{{$data->shipment_id}}"><i class="fas fa-envelope" data-toggle="tooltip" data-placement="top" title="reenvio"></i></a>
<!-- Modal -->
  <div class="modal fade" id="reenvio{{$data->shipment_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Notificacion de correo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Numero de Orden:</label>
                        <input type="text" name="nombre" required class="form-control" value="{{ $data->shipment_id }}" disabled>
                    </div>
                    <div class="modal-footer">
                    <a class="btn btn-success btn-block" href="{{ url('edidaimlernotifica/'.$data->shipment_id) }}"><i class="fas fa-paper-plane fa-lg"></i> Enviar correo</a>
                    </div>
              </div>
          </div>
    </div>
</div>
<!-- Fin Modal -->