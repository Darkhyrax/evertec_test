@extends('layouts.app')

@section('css')
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Listado de ordenes</div>
                <div class="card-body">
                	<button class="btn btn-info text-white mb-4" data-toggle="modal" data-attr="{{ route('tienda.create') }}" id="neworden">Registrar orden</button>
                	<table class="table table-dark table-striped table-hover" id="ordenes-table">
				  	  <thead>
					    <tr>
					    	<th></th>
					    	<th>Nombres y apellidos del cliente</th>
					    	<th>Correo del cliente</th>
					    	<th>Telefono del cliente</th>
					    	<th>Producto</th>
					    	<th>Total orden</th>
					    	<th>Status</th>
					    	<th>Fecha creación orden</th>
					    	<th>Fecha última actualización</th>
					    </tr>
					  </thead>
					  <tbody>
					  	@foreach ($ordenes as $orden)
					  		<tr>
					  			<td>
					  				@if($orden->status_id == 3)
					  					<button class="btn btn-sm btn-primary reintentar" url="{{route('tienda.reintentarpago',['idorden'=>$orden->id])}}"><i class="fas fa-sync"></i> Reintentar pago</button>
					  				@endif
					  			</td>
					  			<td>
					  				{{$orden->customer->customer_name}} {{$orden->customer->customer_surname}}
					  			</td>
					  			<td>
					  				{{$orden->customer->customer_email}}
					  			</td>
					  			<td>
					  				{{$orden->customer->customer_mobile}}
					  			</td>
					  			<td>
					  				{{$orden->product->product_name}}
					  			</td>
					  			<td>
					  				{{$orden->total_order}}$
					  			</td>
					  			<td>
					  				<span class="badge badge-{{($orden->status_id == 1) ? 'secondary' : ($orden->status_id == 2 ? 'success' : 'danger') }}">{{$orden->status->sta_desc}}</span>
					  			</td>
					  			<td>
					  				{{date('d-m-Y h:ia',strtotime($orden->created_at))}}
					  			</td>
					  			<td>
					  				{{date('d-m-Y h:ia',strtotime($orden->created_at))}}
					  			</td>
					  		</tr>
					  	@endforeach
					  </tbody>
					</table>
            	</div>
        	</div>
    	</div>
	</div>
</div>

<!-- modal -->
    <div class="modal fade" id="modal_details_order" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                	<h3>Nueva orden</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="formulario_orden"></div>
                </div>
            </div>
        </div>
    </div>
<!-- //modal -->

@endsection

@section('js')
    	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" defer></script>
    	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js" defer></script>
        <script src="{{ asset('js/order_index.js') }}" defer></script>
@endsection