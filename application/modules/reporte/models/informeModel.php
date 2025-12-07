<?php
class informeModel extends model
{

    public function __construct()
    {
        parent::__construct('creditos');

    }

    //METODO QUE CARGA EL REPORTE DEL SEGUIMIENTO DE CREDITO. 
    public function seguimientoCred($parametro)
    {
        
        $sql = $this->_dbi->sqliQuery("SELECT PrepFrmRepSegCred('$parametro') as rep");      
        return $sql;

    }

    //METODO QUE CARGA EL REPORTE DEL RESUMEN DEL PLAN DE FINANCIAMIENTO. 
    public function resumenPlan($parametro)
    {
        
        $sql = $this->_dbi->sqliQuery("SELECT resumCredxProgFinanc('$parametro') as rep");      
        return $sql;

    }

    //METODO QUE CARGA EL REPORTE DEL RESUMEN DEL PLAN DE FINANCIAMIENTO. 
    public function resumenCosecha($parametro)
    {
        
        $sql = $this->_dbi->sqliQuery("SELECT resumExcCosechaxProgFinanc('$parametro') as rep");      
        return $sql;

    }

}

?>