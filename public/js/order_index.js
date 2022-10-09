$(document).ready( function () {

    $('#ordenes-table').DataTable({
    	language: {
    		url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
    	}
    });

    $(document).on('click','#neworden',function(e)
    {
        let url = $(this).attr('data-attr');
        e.preventDefault();

        $.ajax({
          url: url,
          success: function(resp)
          {
              $("#formulario_orden").html(resp);
              $("#modal_details_order").modal("show"); 
          },
          error: function(xhr, ajaxOptions, thrownError) 
          {
                // Definido en el archivo general.js, muestra alerta de mensajes (error 500 o otros)
                mensajes_web('error','',xhr.status);              
          }
         
        });
    });

    $(document).on('click','.reintentar,.reportar',function(e)
    {
        $("#loading").show();
        event.preventDefault();
        
        $.ajax({
              url: $(this).attr('url'),
              // data: $(this).serialize(),
              type: 'get',
              dataType:'HTML',
              success: function(resp)
              {
                  $("#loadingtext").text("Se abrió una ventana emergente, finalice el proceso para poder volver a la página");
                  //Se abre una ventana emergente con la url del webservice para que el usuario pague
                  var caracteristicas = "height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0";
                  window.open(resp, 'Popup', caracteristicas);
              },
              error: function(xhr, ajaxOptions, thrownError) 
              {
                    $("#loading").hide();
                    // Error de formulario validacion
                  if (xhr.status == 422) 
                  {
                    // Elimina el mensaje de error de los campos que estan correctos
                    $("#orden_form").find("strong[id]").text('');
                     // Estilo de bootstrap para marcar que estan correctos los datos
                     $("#orden_form").find('input,select, textarea').removeClass('is-invalid').addClass('is-valid');

                     // Definido en el archivo general.js, muestra los campos que contienen errores en el formulario
                     errores_formulario(xhr.responseJSON.errors);
                     
                     mensajes_web('error','',xhr.status);
                  }
                  else
                  {
                     mensajes_web('error','',xhr.status);
                  }          
              }
             
        });
    });

} );