<?php
class clasificacionModel extends model
{
    public function __construct() {
        parent::__construct('vInvGrupos');
    }
    //----------------------------------------------------------------------------------------------
	// METODO LISTAR: CARGA LOS DATOS DE CLASIFICACION POR COMPARACION O
	// TODOS DEPENDIENDO DEL PARAMETRO (2 = producto,5 servicios)
	//----------------------------------------------------------------------------------------------
    public function listar()
    {
        
		$sql = "select * from ".$this->_table;
        return $this->_db->sqlQuery($sql);
        
        
    }
    
    public function cargarRubro()
    {
        $sql = "select * from rubro where estatus_rubro = '1'";
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }else
            return array();
        
        
    }
	
            
}