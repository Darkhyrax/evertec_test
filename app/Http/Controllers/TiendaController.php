<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Customer;
use App\Api\PlaceToPay;
use DB;

class TiendaController extends Controller
{
    /**
     * Vista inicial (Mostrará todas las ordenes que ha realizado el usuario logueado).
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $ordenes = Orders::all();
        return view('tienda.index',compact('ordenes'));
    }

    /**
     * Acción formulario nueva orden.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $productos = Products::all();
        return view('tienda.form_orden',compact('productos'));
    }

    public function validar_cliente(Request $request)
    {
        $infocliente = Customer::where('customer_email',strtolower($request->email))->first();

        if (!$infocliente) 
        {
            $infocliente = json_encode('new');
        }

        return $infocliente;
    }

    /**
     * Guardar orden en base de datos e iniciar proceso de pago.
     *
     * @return URL API
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try 
        {
            $request->validate(
                [
                    'customer_email' => 'required|email',
                    'customer_name' => 'required|string',
                    'customer_surname' => 'required|string',
                    'customer_company' => 'nullable|string',
                    'products_id' => 'required|integer|exists:products,id',
                    'customer_mobile' => [
                        'required',
                        function($attribute,$value,$fail)
                        {
                            if (substr($value, 0,1) != '+') 
                            {
                                $fail('El numero de teléfono debe iniciar con el signo + y debe contener numeros solamente');
                            }
                            else 
                            {
                                $phone = explode("+", $value);

                                if (!is_numeric($phone[1])) 
                                {
                                    $fail('El numero de teléfono debe contener solo numeros y el signo + al inicio');
                                }
                            }
                        }
                    ]
                ]
            ,[],[
               'customer_email' => 'Correo',
               'customer_name' => 'Nombre',
               'customer_surname' => 'Apellido',
               'customer_company' => 'Compañía',
               'products_id' => 'Producto',
               'customer_mobile' => 'Teléfono'
            ]); 

            if (!$request->customer_id) 
            {
                $modelcustomer = new Customer();
                $modelcustomer->fill($request->all());
                $modelcustomer->save();
                $id = $modelcustomer->id;
            }
            else
            {
                $modelcustomer = Customer::findOrFail($request->customer_id);
                $id = $modelcustomer->id;
            }

            $modelorder = new Orders;
            $modelorder->customer_id = $id;
            $modelorder->products_id = (int) $request->products_id;
            $modelorder->status_id = 1;
            $modelorder->total_order = Products::findOrFail($request->products_id)->product_price;
            $modelorder->order_reference = uniqid(); //Referencia de la pagina para controlar las ordenes dentro de la web
            $modelorder->save(); 

            //Se invoca la API webcheckout de evertec y se envian los datos de la orden y el cliente para su generación y se retorna;
            $api = PlaceToPay::show_payment($modelorder,$modelcustomer);

            //Esta referencia interna corresponde a un ID interno que retorna la API para poder luego notificar y reversar pagos usando dicho ID y luego se guarda en BD
            $modelorder->internal_reference = $api->requestId;
            $modelorder->save(); 

            DB::commit();

            //Retornamos la URL que arrojo la API
            return response(json_encode(['url'=>$api->processUrl]));
        } 
        catch (Exception $e) 
        {
            DB::rollBack();
            echo $e->getMessage();
        }
    }

    /**
     * Reintentar pagar orden rechazada o cancelada.
     *
     * @return URL API
     */
    public function reintentarpago($idorden)
    {
        $orden = Orders::findOrFail($idorden);

        //Se invoca la API webcheckout de evertec y se envian los datos de la orden y el cliente para su generación y se retorna;
        $api = PlaceToPay::show_payment($orden,$orden->customer);

        //Esta referencia interna corresponde a un ID interno que retorna la API para poder luego notificar y reversar pagos usando dicho ID y luego se guarda en BD
        $orden->internal_reference = $api->requestId;
        $orden->save(); 

        //Retornamos la URL que arrojo la API
        return response($api->processUrl);
    }

    /**
     * Reportar pago de orden pendiente.
     *
     * @return URL API
     */
    public function reportarpago($idorden)
    {
        $orden = Orders::findOrFail($idorden);

        //Se invoca la API webcheckout de evertec y se envian los datos de la orden y el cliente para su generación y se retorna;
        $api = PlaceToPay::report_payment($orden->internal_reference);

        //Esta referencia interna corresponde a un ID interno que retorna la API para poder luego notificar y reversar pagos usando dicho ID y luego se guarda en BD
        $orden->internal_reference = $api->requestId;
        $orden->save(); 

        //Retornamos la URL que arrojo la API
        return response($api->processUrl);
    }

    /**
     * Revisar el estatus de la orden para actualizar.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkresponse($orden)
    {
        // Condicional que aplica si el usuario cancela el pago
        if ($orden == 0) 
        {
            $orderapistatus = "CANCELADO";
            $mensaje = "Se ha cancelado la compra, puede volver a intentarlo más tarde.";
        }
        else
        {
            $infoorden = Orders::findOrFail($orden);
            $api = PlaceToPay::check_order_status($infoorden->internal_reference);
            
            $orderapistatus = $api->status->status;

            switch ($orderapistatus) 
            {
                case 'APPROVED':
                    $status = 2;
                    $mensaje = "¡Su orden {$infoorden->order_reference} se ha procesado y pagado exitosamente, gracias por comprar!";
                    break;
                case 'REJECTED':
                    $status = 3;
                    $mensaje = "¡Su pago de la orden {$infoorden->order_reference} ha sido rechazada o cancelada por usted!";
                    break;
                case 'PENDING':
                    $status = 1;
                    $mensaje = "¡Su pago de la orden {$infoorden->order_reference}, se encuentra pendiente, en se procesará en unos minutos!";
                    break;
            }

            // Se guarda el estatus retornado por la API
            $infoorden->status_id = $status;
            $infoorden->save();
        }
        
        return view('tienda.proceso_status',compact('orderapistatus','mensaje')); 
    }
}
