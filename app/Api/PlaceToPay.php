<?php 

namespace App\Api;

/**
 * Funciones API PlacetoPay Evertec
 */
class PlaceToPay
{
	/**
     * Establecer conexión con API.
     *
     * @return JSON Response
     */
	private function connect($action,$params)
	{
		// Se guardan los codigos de respuesta válidos por la API
		$respuesta_api_validas = [
			'?C',
			'00',
			'PC',
			'PT'
		];

		$curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_URL => "https://checkout-co.placetopay.dev/api/{$action}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $params,
                  CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                  ],
        ]);

        $response = curl_exec($curl);
        $response = json_decode($response);

        //Si no tenemos una respuesta exitosa se emite el error ya que no puede continuar con el proceso
        if (!in_array($response->status->reason, $respuesta_api_validas)) 
        {
            // throw new \Exception("No se pudo autenticar contra la API,valide los parametros e intente nuevamente", 999);

            // Debug respuesta API
            dd($response);
        }

        return $response;
	}

	/**
     * Función para desplegar API y pagar el producto.
     *
     * @return JSON Response
     */

	public function show_payment($orden,$infocliente)
	{
		//URL destino de la API
		$action = "session";

		// Se pone como limite de pago 1 hora antes que expire el mismo
		$actualdate = date('d-m-Y H:i:s');
        $limite = strtotime('+1 hour',strtotime($actualdate));
        $limite = date('c',$limite);

        // Se arma la estructura para invocar 
        $params = 
                [
                    'locale'=>'es_CO',
                    'auth'=>self::generate_auth(),
                    'payer' => [
                                    'document'=>'11111',
                                    'documentType'=>'CC',
                                    'name'=>$infocliente->customer_name,
                                    'surname'=>$infocliente->customer_surname,
                                    'company'=>$infocliente->customer_company,
                                    'email'=>$infocliente->customer_email,
                                    'mobile'=>$infocliente->customer_mobile
                                ],
                    'payment' => [
                                    'reference'=>$orden->order_reference,
                                    'description'=>"Compra del producto: {$orden->product->product_name}",
                                    'amount'=>[
                                                'currency'=>'USD',
                                                'total'=>$orden->total_order,
                                                'taxes'=>null,
                                                'details'=>null
                                             ]
                                ],
                    'expiration' => $limite,
                    'returnUrl' => route('tienda.checkresponse',['orden'=>$orden->id]),
                    'cancelUrl' => route('tienda.checkresponse',['orden'=>isset($orden->id) ? $orden->id : 0]), //Si la orden queda pendiente se manda el id de la orden, si no, se envia cero para determinar que se cancelo por orden del usuario
                    'ipAddress' => '127.0.0.1',
                    'userAgent' => 'PlacetoPay Sandbox',
                    'paymentMethod' => 'visa,master,amex,diners,BBVAC,visa_electron,CDNSA'

                ];
        
        $params = json_encode($params);

        // Ivocamos la funcion connect para que llame a la API y ejecute la acción que le indicamos
        $api = self::connect($action,$params);

        // Retornamos el objeto que nos dio la API
        return $api;
	}

	/**
     * Función para consultar orden en la  API y actualizar orden.
     *
     * @return JSON Response
     */

	public function check_order_status($reference)
	{
		//URL destino de la API
		$action = "session/{$reference}";

		// Se arma la estructura para invocar 
        $params = 
                [
                    'auth'=>self::generate_auth()
                ];
        
        $params = json_encode($params);
        
        // Ivocamos la funcion connect para que llame a la API y ejecute la acción que le indicamos
        $api = self::connect($action,$params);

        // Retornamos el objeto que nos dio la API
        return $api;
	}

	/**
     * Función para consultar orden en la  API y actualizar orden.
     *
     * @return JSON Response
     */

	public function report_payment($reference)
	{
		//URL destino de la API
		$action = "notify";

		// Se arma la estructura para invocar 
        $params = 
                [
                    'auth'=>self::generate_auth(),
                    'internalReference' => $reference,
                    'reference' => 1234
                ];
        
        $params = json_encode($params);
        
        // Ivocamos la funcion connect para que llame a la API y ejecute la acción que le indicamos
        $api = self::connect($action,$params);

        // Retornamos el objeto que nos dio la API
        return $api;
	}

	/**
     * Generar auth token para conectarse a la API.
     *
     * @return Array conexion
     */
	private function generate_auth()
	{
		$transact_id = uniqid();
        $date = date('c');
		$trankey = base64_encode(sha1($transact_id.$date.env('PAY_SECRETKEY'),true));

		return ['login'=>env('PAY_LOGIN'),'tranKey'=>$trankey,'nonce' => base64_encode($transact_id),'seed' => $date];
	}
}

?>