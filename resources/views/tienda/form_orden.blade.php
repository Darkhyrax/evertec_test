<div class="container">
	<div class="row">
		<p>Llene por favor los datos de abajo para poder hacer el pedido.</p>
	</div>
	<div class="row">
		<p>Campos marcados con <b class="required">*</b> son obligatorios</p>
	</div>

	<form action="{{route('tienda.store')}}" method="POST" id="orden_form">
		@csrf
		<div class="row form-group">
			<div class="col-md-4">
				<label for="customer_email" class="form-label"><span class="required">*</span> Correo:</label>
				<input class="form-control" type="text" name="customer_email" id="customer_email" maxlength="50" placeholder="Ejemplo: ejemplo@ejemplo.com" validate_url="{{route('tienda.validar_cliente')}}"/>
				<span class="text-danger">
                    <strong id="customer_email-error"></strong>
              	</span>
			</div>
			<div class="col-md-4">
				<label for="customer_name" class="form-label"><span class="required">*</span> Nombre:</label>
				<input class="form-control" type="text" name="customer_name" id="customer_name" placeholder="Ejemplo:Juan Gabriel"/>
				<span class="text-danger">
                    <strong id="customer_name-error"></strong>
              	</span>
			</div>
			<div class="col-md-4">
				<label for="customer_surname" class="form-label"><span class="required">*</span> Apellido:</label>
				<input class="form-control" type="text" name="customer_surname" id="customer_surname" placeholder="Ejemplo:Ramirez Lopez"/>
				<span class="text-danger">
                    <strong id="customer_surname-error"></strong>
              	</span>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="customer_company" class="form-label">Compañía:</label>
				<input class="form-control" type="text" name="customer_company" id="customer_company" placeholder="Ejemplo:Evertec"/>
				<span class="text-danger">
                    <strong id="customer_company-error"></strong>
              	</span>
			</div>
			<div class="col-md-4">
				<label for="products_id" class="form-label"><span class="required">*</span> Producto:</label>
				<select class="form-control" id="products_id" name="products_id">
					<option value="" precio="0">--- Seleccione ---</option>
					@foreach ($productos as $producto)
                        <option value="{{$producto->id}}" precio="{{$producto->product_price}}">{{$producto->product_name}}</option>
                    @endforeach
				</select>
				<span>Precio: <strong id="product_price">0</strong>$</span>
				<br>
				<span class="text-danger">
                    <strong id="products_id-error"></strong>
              	</span>
			</div>
			<div class="col-md-4">
				<label for="customer_mobile" class="form-label"><span class="required">*</span> Teléfono:</label>
				<input class="form-control" type="text" name="customer_mobile" id="customer_mobile" placeholder="Ejemplo:+584121234567"/>
				<span class="text-danger">
                    <strong id="customer_mobile-error"></strong>
              	</span>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12">
				<input type="hidden" id="customer_id" name="customer_id">
				<button class="btn btn-success">Proceder con el pago</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</form>
</div>

<script src="{{ asset('js/order_form.js') }}" defer></script>