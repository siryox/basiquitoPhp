<?php
class depositoModel extends model
{
    public function __construct() {
        parent::__construct('almacenes');
    }
    
    
    public function cargarDeposito($parameters = false)
    {
        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_almacenes('$parameters')");      
        return $sql;  
    }
    
    public function grabarDeposito($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_almacenes(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }
    
    public function eliminarDeposito($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_almacenes(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }


    public function getResult()
    {
        return $this->_dbi->getLastResult();
    }



    //para eliminar logicamente un registro
    public function estatusDeposito($id,$est)
    {
        $sql = "update deposito set estatus_deposito = '$est'"
                . " where id_deposito = '$id'";
        $res = $this->_db->exec($sql);
        if($res)
        {
            return TRUE;	
        }
        else
        {
            return false;
        }
    }  
    

}