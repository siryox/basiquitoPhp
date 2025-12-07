<?php
class creditoModel extends model
{
    public function __construct()
    {
        parent::__construct('creditos');

    }


    public function cargarCreditos($parameters = false)
    {
        
        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_creditos('$parameters')");      
        return $sql;  

    }

    public function eliminarCredito($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_creditos(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }


    public function grabarCredito($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_creditos(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function grabarDocumento($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_creditos(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }


    public function aprobarCredito($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_creditos(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function cargarCuenta($id)
    {

        $sql = $this->_dbi->sqliQuery("CALL edoCtaCred('$id')");      
        return $sql;       
    }


    public function cargarIntereses($id)
    {
        $parameters = '{"idCredito":"'.$id.'","fecha":"'.date('Y-m-d').'"}';
        $sql = $this->_dbi->sqliQuery("select calIntCredito('$parameters') as intereses");      
        return $sql;       
    }

    //Metodo qu carga el reporte de estado de cuenta 
    public function cargarCxcImp($credito,$usuario)
    {
        $parameters = '{"idUsuario":"'.$usuario.'","idCredito":"'.$credito.'"}';
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmEdoCta
        ('$parameters') as cxc");      
        return $sql;       
    }


    public function cargarDespImp($credito)
    {
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmRelMovCred(".$credito.") as dsp");      
        return $sql;       
    }

    //carga reporte de recepciones
    public function cargarRecepImp($credito,$usuario)
    {
        $parameters = '{"nombreUsuario":"'.$usuario.'","idCredito":"'.$credito.'"}';

        $sql = $this->_dbi->sqliQuery("SELECT prepFrmRelRecep('$parameters') as rec");      
        return $sql;       
    }

    public function cargarMotivoExtCred()
    {
        $parameters= '{"key":"MotivoExtCred"}';   
        $sql = $this->_dbi->sqliQuery("SELECT jDefinir('$parameters') as motivo");      
        return $sql;  
    }
    //----------------------------------------------------------------------------------
    //retorna el resultado de la ultima consulta
    //----------------------------------------------------------------------------------
    public function getResult()
    {
        return $this->_dbi->getLastResult();
    }


    public function cargarPlanTrabajo()
    {
        $sql = $this->_dbi->sqliQuery("SELECT * from  vPlanTrabajo");      
        return $sql;       
    }

}