<?php 
class productorModel extends model
{
    
    public function __construct()
    {
        parent::__construct('productores');

        
    }

    //------------------------------------------------------------------------------
    //metodo de carga de datos
    //--------------------------------------------------------------------------------
    public function cargarProductor($parameters = false)
    {
        if(!$parameters)
            $parameters= '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_productores('$parameters')");      
        return $sql;                 
    }

    //---------------------------------------------------------------------------------
    //
    //---------------------------------------------------------------------------------
    public function grabarProductor($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_productores(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function eliminarProductor($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_productores(?)","$datos");   
              
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

    public function cargarBancos()
    {
        $sql = "select * from bancos";
        $res = $this->_db->sqlQuery($sql);

        return $res;

    }

    public function cargarNotaImpAlmacen($id)
    {
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmRelMovInv('$id') as nota");      
        return $sql;
    }

}