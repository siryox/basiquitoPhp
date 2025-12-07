<?php
class productoModel extends model
{
	private $_ult_producto;
    public function __construct() {
        parent::__construct('producto');
		$this->_ult_producto=0;
    }
    
    public function cargarProductos($parameters = false)
    {
        if(!$parameters)
            $parameters= '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_productos('$parameters')");      
        return $sql;                 
    }  
    //---------------------------------------------------------------------------------
    //
    //---------------------------------------------------------------------------------
    public function grabarProducto($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_productos(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function eliminarProducto($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_productos(?)","$datos");   
              
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

    public function cargarNotaImp($id,$usuario,$fi,$ff,$almacen)
    {
        $parameters = '{"codProd":"'.$id.'","id_usuario":"'.$usuario.'","fechaInicio":"'.$fi.'","fechaFinal":"'.$ff.'","almacen":"'.$almacen.'"}';
        $sql = $this->_dbi->sqliQuery("SELECT prepFrmRelMovProducto('$parameters') as nota");      
        return $sql;
    }
    
    

    public function cargarProductoDeposito($datos) 
    {
        $parameters = '{"action":"search productos-exi","campo":"almacen","valor":"'.$datos['almacen'].'"}';
        $sql = $this->_dbi->sqliQuery("CALL gest_productos('".$parameters."')");      
        return $sql;
    }   
}