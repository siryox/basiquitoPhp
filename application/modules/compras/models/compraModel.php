<?php
final class compraModel extends model
{

    public function __construct()
    {
        parent::__construct('compras');
    }



    public function cargarCompras($parameters = false)
    {
        if(!$parameters)
        $parameters= '{"action":"search all"}';
    
        $sql = $this->_dbi->sqliQuery("CALL gest_compras('$parameters')");      
        return $sql;  

    }


    //-------------------------------------------------------------------------
    //
    //------------------------------------------------------------------------
    public function grabarCompra($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_compras(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }
    public function cargarNotaImp($id)
    {
        $parameters= '{"idReg":"'.$id.'","pagina":""}';
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmOrdCompra('$parameters') as nota");      
        return $sql;
    }

    public function enviarOrdenCompra($id,$usuario)
    {
        $parameters = '{"action":"enviarcorreo","tipoDoc":"ord-cpra","id":"'.$id.'","id_usuario":"'.$usuario.'"}';
        $sql = $this->_dbi->spExec("CALL gest_compras(?)","$parameters");   

        return $this->getResult();
    }

     public function anularCompra($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_compras(?)","$datos");   
              
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