<?php 
class visitaModel extends model
{
    public function __construct()
    {
        parent::__construct('visitas');
    }

    public function cargarVisitas($parameters = false)
    {
        
        if(!$parameters)
            $parameters = '{"action":"search visitas"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_app('$parameters')");      
        return $sql;  

    }





}
