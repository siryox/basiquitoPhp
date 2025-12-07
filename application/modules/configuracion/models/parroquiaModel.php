<?php
class parroquiaModel  extends model
{
    public function __construct() {
        parent::__construct('parroquia');
    }
   
    public function cargarParroquia($ref=FALSE)
    { 
        if($ref)
        {   
            $sql = "select par.*,mun.descripcion_municipio as medida from parroquia as par, municipio as mun"
            . " where par.municipio_id = mun.id_municipio and par.descripcion_parroquia like '%$ref%' order by par.descripcion_parroquia";
        }
        else
        {      
            $sql = "select par.*,mun.descripcion_municipio as medida from parroquia as par, municipio as mun"
            . " where par.municipio_id = mun.id_municipio order by par.descripcion_parroquia";
        }
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }else
            return array();
    } 
    public function incluir($datos)
    {
        if($datos)
        {
            $sql = "insert into parroquia (descripcion_parroquia, fecha_creacion, estatus_parroquia, municipio_id)
             values('".strtoupper($datos['descripcion'])."', now(), 1,".$datos['municipio'].")";
            $res = $this->_db->exec($sql);
            if($res)
            {
                    $this->_ultimo_registro = $this->_db->lastInsertId();	
                    return TRUE;	
            }
            else
                    return false;
        }
    }
    public function modificar($datos)
    {
        $sql = "update parroquia set "
                . "descripcion_parroquia = '".strtoupper($datos['descripcion'])."', "
                . "municipio_id = '".$datos['municipio']."' "
                . " where id_parroquia = ".$datos['id'];
        $res = $this->_db->exec($sql);
        if($res)
        {
            return TRUE;	
        }
        else
            return false;
    }
    public function buscarParroquia($descripcion)
    {
        $sql = "select * from parroquia as par where par.descripcion_parroquia = '$descripcion'";
       // die($sql);
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch();
        }
        else
            return FALSE;
    }
    
    public function buscar($id)
    {
        $sql = "select par.*,mun.estado_id from parroquia as par,municipio as mun 
        where id_parroquia='$id' and mun.id_municipio = par.municipio_id ";
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch();
        }else
            return array();
        
    }

    public function desactivar($id_ref)
    {
        $sql = "update parroquia set estatus_parroquia = '9' where id_parroquia='$id_ref'";
        $res = $this->_db->exec($sql);
        if(!$res)
            return FALSE;               
        else
            return TRUE;
    }
	
	
	 //--METODO PARA CARGAR PARROQUIAS CORRESPONDIENTES A UN MUNICIPIO
    public function buscarParroquias($municipio)
    {
         $sql = "select * from parroquia as parr"
        . " where parr.municipio_id = '$municipio' and parr.estatus_parroquia='1'"
        . " order by descripcion_parroquia";
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }
        else
            return array();
    }
	
	//--METODO PARA CARGAR MUNICIPIO CORRESPONDIENTES A UNA PARROQUIA INVERSO
    public function buscarMunicipioParroquia($parroquia)
    {
         $sql = "select mun.* from municipio as mun,parroquia parr  
         where parr.id_parroquia = '$parroquia' and mun.id_municipio = parr.municipio_id";
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch();
        }
        else
            return array();
    }
	
	public function comprobarParroquia($mun,$par)
    {
        $sql ="select count(*)as total from parroquia as par where par.municipio_id='$mun' and par.descripcion_parroquia = ".$par;
        $res = $this->_db->query($sql);
        if($res){
			$res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch() ;
		}
        else
            return array("total"=>0);
    }
	
}