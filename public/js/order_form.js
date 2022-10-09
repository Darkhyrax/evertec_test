$(document).ready(function ()
{

    $("#customer_email").on("keyup", function () 
    {
       $(this).val($(this).val().toLowerCase());
    });

	$('#products_id').change(function()
    {	
    	// Se obtiene el costo del producto del atributo precio del option de la lista y se plasma en el texto
        $("#product_price").text(parseFloat($('option:selected',this).attr("precio")));
    });

    $('#customer_email').change(function()
    {   
        if ($(this).val()) 
        {
            $("#loading").show();

             $.ajax({
                  url: $(this).attr('validate_url'),
                  data: {_token:$('input[name=_token]').val(),email: $(this).val()},
                  type: 'POST',
                  dataType:'JSON',
                  complete: function()
                  {
                        $("#loading").hide();
                  },
                  success: function(resp)
                  {
                        // Se valida si la respuesta es un objeto y no texto
                       if (typeof resp == "object") 
                       {
                            $.each(resp, function(campo,valor)
                             {
                                //Se valida que no se bloquee el campo correo, los demas se bloquean 
                                // y se colocan el valor guardado en BD
                                if (campo != "customer_email") 
                                {
                                    $('#'+campo).val(valor);
                                    $("#"+campo).prop('readonly',true);
                                }
                             });

                            // Se asigna el ID del cliente al input hidden
                            $("#customer_id").val(resp.id);
                       }
                       else
                       {
                            // Se limpian los campos de texto y el oculto que poseia el ID
                            $("input:text,input:hidden").not("#customer_email,input[name=_token]").val('');
                            // Se habilitan
                            $("input:text").prop('readonly',false);
                       }
                  },
                  error: function(xhr, ajaxOptions, thrownError) 
                  {
                        
                        // Error de formulario validacion
                      if (xhr.status == 422) 
                      {
                            // Elimina el mensaje de error de los campos que estan correctos
                            $("#orden_form").find("strong[id]").text('');
                         // Estilo de bootstrap para marcar que estan correctos los datos
                         $("#orden_form").find('input,select, textarea').removeClass('is-invalid').addClass('is-valid');

                         // Definido en el archivo general.js, muestra los campos que contienen errores en el formulario
                         errores_formulario(xhr.responseJSON.errors);
                         
                         mensajes_web('error',xhr.status);
                      }
                      else
                      {
                         mensajes_web('error','',xhr.status);
                      }          
                  }
                 
            });
        }
        else
        {
            // Se limpian los campos de texto y el oculto que poseia el ID
            $("input:text,input:hidden").not("#customer_email,input[name=_token]").val('');
            // Se habilitan
            $("input:text").prop('readonly',false);
        }

    });

    $("#orden_form").submit(function( event ) 
    {
        $("#loading").show();
        event.preventDefault();
        
        $.ajax({
              url: $(this).attr('action'),
              data: $(this).serialize(),
              type: $(this).attr('method'),
              dataType:'JSON',
              success: function(resp)
              {
                    $("#loadingtext").text("Se abrió una ventana emergente, finalice el proceso para poder volver a la página");
                  //Se abre una ventana emergente con la url del webservice para que el usuario pague
                  var caracteristicas = "height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0";
                  window.open(resp.url, 'Popup', caracteristicas);
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

});
