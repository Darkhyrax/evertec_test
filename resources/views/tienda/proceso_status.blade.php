<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<div class="row mb-5 mt-5 align-self-center">
	<div class="col-md-12 text-center">
		@if($orderapistatus == "APPROVED")
			<i class="fas fa-check fa-10x text-success"></i>
		@elseif($orderapistatus == "PENDING")
			<i class="fas fa-10x fa-exclamation-triangle text-warning"></i>
		@else
			<i class="fas fa-10x fa-times-circle text-danger"></i>
		@endif
	</div>
</div>
<div class="row align-self-center">
	<div class="col-md-11 ml-5 text-center">
		<h4>{{$mensaje}}</h4>
		<button class="btn btn-info" onclick="cerrar_this()">Cerrar</button>
	</div>
</div>

<script>
	function cerrar_this() 
	{ 
		//Cierra la ventana emergente y refresca la ventana padre que lo invocó
		opener.window.location.reload(); 
		self.close(); 
		return false; 
	} 
</script>