<?php 
class convenioModel extends model
{

    public function __construct()
    {
        parent::__construct('convenios');
    }


    public function cargarconvenio($parameters = false)
    {
        if(!$parameters)
            $parameters= '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_convenio('$parameters')");      
        return $sql;                 
    }


}

?>