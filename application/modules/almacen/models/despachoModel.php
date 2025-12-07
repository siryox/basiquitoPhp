<?php
class despachoModel extends model
{

    public function __construct()
    {
        parent::__construct('producto');
    }

    


    public function cargarDespachos($parameters = false)
    {
        if(!$parameters)
        $parameters= '{"action":"search all"}';
    
        $sql = $this->_dbi->sqliQuery("CALL gest_movInv('$parameters')");      
        return $sql;                
    }

    public function grabarDespacho($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_movInv(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function anularDespacho($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_movInv(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function eliminarDespacho($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_transac_cred(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

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

    public function cargarNotaImp($id)
    {
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmSalInv('$id') as nota");      
        return $sql;
    }


    public function getResult()
    {
        return $this->_dbi->getLastResult();
    }





}