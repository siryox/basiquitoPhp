<?php
class whatsapp
{
    //valores de control
    private $_token;
    private $_telefono;
    private $_url;
    private $_tipo;
    private $_visualizar_url;
    private $_estructura_msj;
    //valores de estructura de mensaje
    private $_link_imagen;
    private $_link_documento;
    private $_emoji;
    private $_longitud;
    private $_latitud;
    private $_nom_ubicacion;
    private $_dir_ubicacion;
    private $_mensaje;
    private $_mensaje_id;
    
    private $_cabecera;

    public function __construct($param=false)
    {
        if($param)
        {
            $this->_token =    (isset($param['token']))?$param['token']:"";
            $this->_telefono = (isset($param['telefono']))?$param['telefono']:"";
            $this->_url =      (isset($param['url']))?$param['url']:"";
            $this->_tipo =     (isset($param['tipo']))?$param['tipo']:"";

            $this->_link_documento = (isset($param['link_documento']))?$param['link_documento']:"";
            $this->_link_imagen = (isset($param['link_imagen']))?$param['link_imagen']:"";
            $this->_emoji = (isset($param['emojin']))?$param['emojin']:"";
            $this->_longitud = (isset($param['longitud']))?$param['longitud']:"";
            $this->_latitud = (isset($param['latitud']))?$param['latitud']:"";
            $this->_nom_ubicacion = (isset($param['nom_ubicacion']))?$param['nom_ubicacion']:"";
            $this->_dir_ubicacion = (isset($param['dir_ubicacion']))?$param['dir_ubicacion']:"";
            $this->_mensaje = (isset($param['mensaje']))?$param['mensaje']:"";
            $this->_mensaje_id = (isset($param['mensaje_id']))?$param['mensaje_id']:"";
            $this->_visualizar_url = (isset($param['visualizar_url']))?$param['visualizar_url']:"";

            $opt = ["telefono"=>$this->_telefono,"contenido"=>$this->_mensaje,"visualizar"=>$this->_visualizar_url,"id_mensaje"=>$this->_mensaje_id,
                    "emoji"=>$this->_emoji,"link_imagen"=>$this->_link_imagen,"link_documento"=>$this->_link_documento,"lon"=>$this->_longitud,"lat"=>$this->_latitud,
                    "nom_ubicacion"=>$this->_nom_ubicacion,"dir_ubicacion"=>$this->_dir_ubicacion];

            $this->_cabecera = array("Authorization: Bearer ". $this->_token,"Content-Type: application/json");
            $this->_estructura_msj = $this->cargar_extructura_mensaje($this->_tipo,$opt);
            $this->enviar_mensaje();
        }

    }

    public function enviar_mensaje($param = false)
    {
        if($param)
        {
            $this->_token =    (isset($param['token']))?$param['token']:"";
            $this->_telefono = (isset($param['telefono']))?$param['telefono']:"";
            $this->_url =      (isset($param['url']))?$param['url']:"";
            $this->_tipo =     (isset($param['tipo']))?$param['tipo']:"";

            $this->_link_documento = (isset($param['link_documento']))?$param['link_documento']:"";
            $this->_link_imagen = (isset($param['link_imagen']))?$param['link_imagen']:"";
            $this->_emoji = (isset($param['emojin']))?$param['emojin']:"";
            $this->_longitud = (isset($param['longitud']))?$param['longitud']:"";
            $this->_latitud = (isset($param['latitud']))?$param['latitud']:"";
            $this->_nom_ubicacion = (isset($param['nom_ubicacion']))?$param['nom_ubicacion']:"";
            $this->_dir_ubicacion = (isset($param['dir_ubicacion']))?$param['dir_ubicacion']:"";
            $this->_mensaje = (isset($param['mensaje']))?$param['mensaje']:"";
            $this->_mensaje_id = (isset($param['mensaje_id']))?$param['mensaje_id']:"";
            $this->_visualizar_url = (isset($param['visualizar_url']))?$param['visualizar_url']:"";

            $opt = ["telefono"=>$this->_telefono,"contenido"=>$this->_mensaje,"visualizar"=>$this->_visualizar_url,"id_mensaje"=>$this->_mensaje_id,
                    "emoji"=>$this->_emoji,"link_imagen"=>$this->_link_imagen,"link_documento"=>$this->_link_documento,"lon"=>$this->_longitud,"lat"=>$this->_latitud,
                    "nom_ubicacion"=>$this->_nom_ubicacion,"dir_ubicacion"=>$this->_dir_ubicacion];

            $this->_cabecera = array("Authorization: Bearer ". $this->_token,"Content-Type: application/json");
            $this->_estructura_msj = json_encode($this->cargar_extructura_mensaje($this->_tipo,$opt));
        }

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$this->_url);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$this->_estructura_msj);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$this->_cabecera);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

        $response = json_decode(curl_exec($curl),true);

        //print_r($response);

        $status = curl_getinfo($curl,CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $response;

    }

    public function recibir_mensaje()
    {


    } 

   


    public function cargar_extructura_mensaje($tipo,$opt)
    {
        if($tipo)
        {
            if(is_array($opt))
            {
                switch($tipo)
                {
                    case 'text':
                            //ARREGLO QUE CONTIENE PLANTILLA PARA MENSAJE DE TEXT
                            $msj_tex = ["messaging_product"=>"whatsapp","recipient_type"=>"individual","to"=>"",
                                "type"=>"text","text"=>["preview_url"=>"","body"=>""]];
                            
                            $msj_tex['to'] = $opt['telefono'];
                            $msj_tex['text']['body']=$opt['contenido'];
                            $msj_tex['text']['preview_url']= $opt['visualizar']; 
                            return  $msj_tex;     
                            
                        break;
                    case 'reaction':
                            //ARREGLO QUE CONTIENE PLANTILLA PARA MENSAJE DE REACCION
                            $msj_reaccion = ["messaging_product"=>"whatsapp","recipient_type"=>"individual","to"=>'',
                                'type'=>'reaction','reaction'=>['messaje_id'=>'','emoji'=>'']];
                            $msj_reaccion['to'] = $opt['telefono'];
                            $msj_reaccion['reaction']['messaje_id']=$opt['id_mensaje'];
                            $msj_reaccion['reaction']['emoji']= $opt['emoji']; 
                            return  $msj_reaccion;     
                        break;
                    case 'image':
                            //ARREGLO QUE CONTIENE PLANTILLA PARA MENSAJE DE IMAGEN
                            $msj_imagen = ["messaging_product"=>"whatsapp","recipient_type"=>"individual","to"=>'',
                            'type'=>'image','image'=>['link'=>'']];
                            $msj_imagen['to'] = $opt['telefono'];
                            $msj_imagen['image']['link']=$opt['link_imagen'];
                             
                            return  $msj_imagen;     
                        break;
                    case 'location':
                            //ARREGLO QUE CONTIENE PLANTILLA PARA MENSAJE DE UBICACION
                            $msj_ubicacion = ["messaging_product"=>"whatsapp","recipient_type"=>"individual","to"=>'',
                            'type'=>'location','location'=>['longitude'=>"",'latitude'=>'','name'=>"","address"=>""]];
                            $msj_ubicacion['to'] = $opt['telefono'];
                            $msj_ubicacion['location']['longitude'] = $opt['lon'];
                            $msj_ubicacion['location']['latitude'] = $opt['lat'];
                            $msj_ubicacion['location']['name'] = $opt['nom_ubicacion'];
                            $msj_ubicacion['location']['address'] = $opt['dir_ubicacion'];
                            
                            return $msj_ubicacion;
                        break;
                    case 'document': 
                        //ARREGLO QUE CONTIENE PLANTILLA PARA MENSAJE DE IMAGEN
                        $msj_documento = ["messaging_product"=>"whatsapp","recipient_type"=>"individual","to"=>'',
                        'type'=>'document','document'=>['link'=>'']];

                        $msj_documento['to'] = $opt['telefono'];
                        $msj_documento['document']['link'] = $opt['link_documento']; 
                        return $msj_documento;
                        break;   
                    case 'confirm':
                        $msj_confirm = ["messaging_product"=>"whatsapp","status"=>"read","message_id"=>''];

                        $msj_confirm['message_id'] = $opt['id'];
                        return $msj_confirm;
                        break;
                }
            }else
                return false;    



        }
              
    }


}

?>