<?php
final class recepcionModel extends model
{
    public function __construct()
    {
        parent::__construct('recepcion');
    }

    public function cargarRecepcion($parameters = false)
    {
        if(!$parameters)
            $parameters= '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_recepcion('$parameters')");      
        return $sql;                 
    }

    public function grabarRecepcion($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_recepcion(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }


    public function anularRecepcion($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_recepcion(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function cargarRubros()
    {
        $sql = "select * from vRubros";
        $res = $this->_db->sqlQuery($sql);

        return $res;

    }

    //---------------------------------------------------------------------------
    //carga configuracion de manejo de inventario (validacion stock 0)
    //---------------------------------------------------------------------------
    public function cargarConfiguracionDespacho()
    { 
        $parameters= '{"key":"ConfigInventario"}';   
        $sql = $this->_dbi->sqliQuery("SELECT jDefinir('$parameters') as config");      
        return $sql;                 
    }
    //----------------------------------------------------------------------------
    //carga configuracion datos del cliente
    //----------------------------------------------------------------------------
    public function cargarConfiguracionEmpresa()
    { 
        $parameters= '{"key":"DatosCliente"}';   
        $sql = $this->_dbi->sqliQuery("SELECT jDefinir('$parameters') as config");      
        return $sql;                 
    }


    public function getResult()
    {
        return $this->_dbi->getLastResult();
    }

    public function cargarNotaRec($id)
    {
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmRecepCosecha('$id') as nota");      
        return $sql;
    }


}

