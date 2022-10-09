$(document).ready(function ()
{

});

// Mostrar errores en los inputs del formulario donde sea que se use
function errores_formulario(campos) 
{
	 $.each(campos, function(campo,mensaje)
	 {
	 		$('#'+campo).removeClass('is-valid');
 			$('#'+campo).addClass('is-invalid');
 			$("#"+campo+"-error").text(mensaje);
	 });
}

// Mostrar mensajes general del sistema en TOASTR
function mensajes_web(tipo,mensaje,codigo = null) 
{

	if (mensaje == '' && tipo == "success" && codigo == '') 
	{
		mensaje = 'Se ha procesado el registro exitosamente';
	}
	else if (mensaje == '' && tipo == 'error' && codigo == '') 
	{
		mensaje = 'Ha ocurrido un error, por favor intente mas tarde';
	}
	else
	{
		if (codigo == 404) 
	    {
	        mensaje = "Página no encontrada";
	    }
	    else if (codigo == 403) 
	    {
	    	mensaje = "No autorizado";
	    }
	    else if (codigo == 500) 
	    {
	        mensaje = "Error interno, intente de nuevo mas tarde o contacte al administrador del portal.";
	    }
	    else if(codigo == 0)
	    {
	        mensaje = "No hay conexión a internet, por favor revise su conexión.";
	    }
	    else if(codigo == 422)
	    {
	    	mensaje = "Por favor valide los campos seleccionados.";
	    }
	    else
	    {
	        mensaje = mensaje;
	    }
	}

	toastr.options =
	{
		"closeButton" : true,
		"progressBar" : false,
		"preventDuplicates": true,
		"newestOnTop": false,
		"positionClass": "toast-bottom-right"
	}

	if (tipo == "success") 
	{
		toastr.success(mensaje);
	}
	else if (tipo == "warning") 
	{
		toastr.warning(mensaje);
	}
	else
	{
		toastr.error(mensaje);
	}
}