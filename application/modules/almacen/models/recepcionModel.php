<?php
final class recepcionModel extends model
{
    public function __construct()
    {
        parent::__construct('inv_movmientos');
    }


    public function cargarRecepciones($parameters = false)
    {
        if(!$parameters)
            $parameters= '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_movInv('$parameters')");      
        return $sql;
        
    }

    public function buscarDocRecepcion($parameters)
    {
        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_movInv('$parameters')");      
        return $sql;          
    }



    public function guardarRecepcion($parameters)
    {
        $sql = $this->_dbi->spExec("CALL gest_movInv(?)","$parameters");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function cargarNotaImp($id)
    {
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmEntInv('$id') as nota");      
        return $sql;
    }

    public function anularRecepcion($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_movInv(?)","$datos");   
              
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
