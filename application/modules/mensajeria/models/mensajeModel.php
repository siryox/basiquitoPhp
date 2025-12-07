<?php
class mensajeModel extends model
{
    public function __construct()
    {
        parent::__construct('mensajes');
    }

    public function cargarMensajes()
    {
        
        
    }


    public function grabarMensaje($datos)
    {
        $sql = $this->_dbi->spExecJs("CALL gest_mensajes(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }


    //----------------------------------------------------------------------------
    //carga configuracion datos para mensajes de whatsapp
    //----------------------------------------------------------------------------
    public function cargarDatosWhatsapp()
    { 
        $parameters= '{"key":"datosServWhatsapp"}';   
        $sql = $this->_dbi->sqliQuery("SELECT jDefinir('$parameters') as datos");      
        return $sql;                 
    }


    public function cargarConfigWhatsapp()
    { 
        $parameters= '{"key":"SwWhatsApp"}';   
        $sql = $this->_dbi->sqliQuery("SELECT jDefinir('$parameters') as config");      
        return $sql;                 
    }

    




}

?>