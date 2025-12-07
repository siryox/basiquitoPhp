<?php
class whatsappController extends mensajeriaController
{
    private $_content_input;
    
    public function __construct() {
        parent::__construct();
        $this->_content_input = file_get_contents("php://input");

    }

    public function index()
    {


    }

    public function enviar($param = false)
    {
        // peticion get    
        if($param)
        {
            $valores = json_decode($param);
            
        }else
            {
                $valores = json_decode(validate::getPostParam('mensaje'));
            }
            if($valores)
            {

                $valores = array(
                    "url" => WSP_URL_API,
                    "token"=> WSP_TOKEN,
                    "visualizar_url"=> WSP_VISUALIZACION_URL,
                    "tipo"=> $valores['tipo'],
                    "telefono"=>$valores['receptor'],
                    "mensaje"=>$valores['mensaje'],
                    
                );

              
            }
                
    }

    public function recibir()
    {

        if( $_SERVER['REQUEST_METHOD']=='POST')
        {
             = $this->_content_input;

           // $respuesta = json_decode($respuesta,true);


            echo $respuesta; exit();
            // if($respuesta != null)
            // {
            //     file_put_contents(LOG_PATH."logWhapi.txt",json_encode($respuesta), FILE_APPEND | LOCK_EX);

            //     http_response_code(200);
            //     $response['status'] = "Ok";
            //     $response['result'] = array("response_id"=>"200","response_msg"=>"Successfull operation");
            //     $response['body'] = $valor;       
        
            //     echo json_encode($response,true);
            //     exit();
            // }else{
            //     file_put_contents(LOG_PATH."logWhapi.txt",json_encode($respuesta), FILE_APPEND | LOCK_EX);
            //     http_response_code(400);
            //     $response['status'] = "Error";
            //     $response['result'] = array("error_id"=>"400","error_msg"=>"Fail ....");        
        
            //     echo json_encode($response,true);
            //     exit();



            // }
        }
    }

    
    
    

    

}
