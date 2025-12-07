<?php 
final class movimientoModel extends model
{
    public function __construct()
    {
        parent::__construct('inv_movimientos');
    }


    public function cargarMovimientos($parameters = false)
    {
        if(!$parameters)
            $parameters= '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_movInv('$parameters')");      
        return $sql;
    }






}