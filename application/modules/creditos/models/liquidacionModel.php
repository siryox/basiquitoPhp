<?php
final class liquidacionModel extends model
{
    public function __construct()
    {
        parent::__construct('liquidaciones');
    }



    public function cargarLiquidaciones($parameters = false)
    {
        
        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_liquidaciones('$parameters')");      
        return $sql;  

    }


    public function grabarLiquidacion($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_liquidaciones(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }


    public function cuentaLiquidaciones($parameters = false)
    {
        
        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL filtrarDocsALiq('$parameters')");      
        return $sql;  

    }


    public function cargarPagosLiq($parameters = false)
    {
        
        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_pagos('$parameters')");      
        return $sql;  

    }




    public function cargarNotaImp($id)
    {
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmRecPago('$id') as nota");      
        return $sql;
    }

    public function anularLiquidacion($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_liquidaciones(?)","$datos");   
              
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


    public function cargarConfiguracionEmpresa()
    { 
        $parameters= '{"key":"DatosCliente"}';   
        $sql = $this->_dbi->sqliQuery("SELECT jDefinir('$parameters') as config");      
        return $sql;                 
    }



    

}