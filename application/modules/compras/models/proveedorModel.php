<?php
class proveedorModel extends model
{

    public function __construct()
    {
        parent::__construct('proveedores');
    }


    public function cargarProveedores($parameters = false)
    {
        if(!$parameters)
        $parameters= '{"action":"search all"}';
    
        $sql = $this->_dbi->sqliQuery("CALL gest_proveedores('$parameters')");      
        return $sql;  

    }

    //-------------------------------------------------------------------------
    //
    //------------------------------------------------------------------------
    public function grabarProveedor($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_proveedores(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function eliminarProveedores($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_proveedores(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    //----------------------------------------------------------------------------------
    //retorna el resultado de la ultima consulta
    //----------------------------------------------------------------------------------
    public function getResult()
    {
        return $this->_dbi->getLastResult();
    }

    public function grabarOrden($datos)
    {
        $sql = $this->_dbi->spExec("call gest_compras(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;  
    }



}


?>