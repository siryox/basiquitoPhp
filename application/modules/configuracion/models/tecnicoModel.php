<?php
class tecnicoModel extends model
{

    public function __construct()
    {
        parent::__construct('tecnico');
    }


    public function cargarTecnicos($parameters = false)
    {

        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_tecnicos('$parameters')");      
        return $sql;  
    }  
    
    
    public function grabarTecnico($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_tecnicos(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function getResult()
    {
        return $this->_dbi->getLastResult();
    }

}


