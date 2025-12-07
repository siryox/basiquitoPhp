<?php
final class fincaModel extends model
{
    public function __construct()
    {
        parent::__construct('fincas');
    }

    public function cargarFincas($parameters = false)
    {
        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_fincas('$parameters')");      
        return $sql;  
        
    }

    //---------------------------------------------------------------------------------
    //
    //---------------------------------------------------------------------------------
    public function grabarFinca($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_fincas(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }


    public function eliminarFinca($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_fincas(?)","$datos");   
              
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
}
?>