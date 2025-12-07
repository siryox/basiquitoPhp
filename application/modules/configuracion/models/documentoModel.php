<?php
class documentoModel extends model
{
    public function __construct()
    {
        parent::__construct('documentos');
    }

    public function cargarDocumento($valor = false)
    {
        if($valor != false)
        {
            $sql = "select * from documentos where id = '".$valor."'";
        }else 
        {
            $sql = "select * from documentos";
        } 
        
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }
        else
        {
            return array();
        }
    }



    public function insertarDocumento($datos)
    {

         $sql = "insert into documentos("
            ."nombre,"
            ."sigla,descripcion,"
            ."estado,"
            ."plantilla,"
            ."contador) values("
            ."'". $datos['nombre']."',"
            ."'".strtoupper($datos['sigla'])."',"
            ."'".strtoupper($datos['descripcion'])."',"
            ."'".strtoupper($datos['estado'])."',"
            ."'".$datos['plantilla']."',"
            ."'".$datos['contador']."')";
        $res = $this->_db->exec($sql);
        if($res)
        {
            return TRUE;               
        }
        else
        {
            return FALSE;
        }
        
    }


    public function editarDocumento($datos)
    {
        $sql = "update documentos set "
                . "nombre = '". $datos['nombre']."',"
                . "sigla = '". strtoupper($datos['sigla'])."',"
                . "descripcion = '".  strtoupper($datos['descripcion'])."',"
                . "plantilla = '". $datos['plantilla']."',"
                . "contador = '".  $datos['contador']."' "
                . " where id = '".$datos['id']."'";
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



    public function cargarNumeradores()
    { 
        $parameters= '{"key":"Numeradores"}';   
        $sql = $this->_dbi->sqliQuery("SELECT jDefinir('$parameters') as Numeradores");      
        return $sql;                 
    }


    public function cargarDefiniciones($valor = false)
    {
        if($valor != false)
        {
            $sql = "select * from definiciones_v2 where siglas = '".$valor."'";
        }else 
        {
            $sql = "select * from definiciones_v2";
        } 
        
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }
        else
        {
            return array();
        }
    }
    

    
}