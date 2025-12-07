<?php
class usuarioModel extends Model{
    private $_ult_usuario;
    
    public function __construct() {
        parent::__construct('usuario');
        
       
    }
    public function ult_usuario_reg()
    {
        return $this->_ult_usuario;
    }        
    //metodo del controlador que carga un listado de usuarios
    public function cargarUsuario($item = false,$emp)
    {
        if($item)
            $sql="select * from usuario as usu,persona as per where per.id_persona = usu.persona_id "
              . "and (concat( per.pri_nombre_persona, ' ', per.pri_apellido_persona ) LIKE '%$item%'"
              . " or usu.alias_usuario like '%$item%') and usu.empresa_id='$emp' group by usu.id_usuario order by per.cedula_persona,usu.id_usuario ";
        else {
            $sql = "select * from usuario as usu where  usu.estatus_usuario!='9' and usu.empresa_id='$emp'  order by id ";
        }
        
        
       /// die($sql);
        $res = $this->_db->query($sql);
        if($res){
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }else
            {
                $error =$this->_db->getError();
                logger::errorLog($error['2'],'DB');           
                return array();	
            }
    }
    
    // public function getAllUser()
    // {
        
    //    $res = r::findAll("usuario");
     
    //    return $res; 
    // }   
    //--------------------------------------------------------------------------
    //metodo del controlador que busca un usuario por su id 
    //--------------------------------------------------------------------------
    public function buscar($id)
    {
        $sql = "select u.*,r.nombre_role from usuario u inner join role r on r.id = u.role_id  where  u.id ='$id'  ";
        //die($sql);
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch();
        }else
            return array();
        
    }
    //------------------------------------------------------------
    //metodo que inserta un registro nuevo en la base de datos mediante query directo
    //------------------------------------------------------------
    public function insertar($datos)
    {
        if($datos)
        {
            $this->_db->start();
            $sql = "insert into usuario("
                    . "fec_crea_usuario,"
                    . "correo_usuario,"
                    . "alias_usuario,"
                    . "telefono_usuario,"
                    . "estatus_usuario,"
                    . "persona_id,"
                    . "role_id,"
                    . "condicion_usuario,"
                    . "empresa_id"
                    . ")values("
                    . "now(),"
                    . "'".$datos['correo']."',"
                    . "'".$datos['alias']."',"
                    . "'".$datos['telefono']."',"
                    . "'1',"
                    . "'".$datos['persona']."',"
                    . "'".$datos['role']."',"
                    . "'DESCONECTADO',"
                    . "'".$datos['empresa']."')";
            
            
            //die($sql);
            $res = $this->_db->exec($sql);
            if(!$res)
            {
				$error =$this->_db->getError();
				logger::errorLog($error['2'],'DB');
                $this->_db->cancel();
				return false;
            }else
                {

                    //$this->confirmar();
                    $this->_ult_usuario = $this->_db->lastInsertId();
                    //---------------------------------------------------------------
                    //se guarda preguna de seguridad
                    //--------------------------------------------------------------- 
                    $pre = array(
						"usuario"=>$this->_ult_usuario,
						"pregunta"=>$datos['pregunta'],
						"respuesta"=>$datos['respuesta']
						);
                    
                    if($this->incluirUsuarioPregunta($pre))
                    {
                        $seguridad = array(    
                            "fcreado"=>date('Y-m-d'),
                            "fexpira"=>date('Y-m-d',strtotime ( '+'.TIME_KEY.' day' , strtotime ( date('Y-m-d')) )),
                            "usuario"=>$this->_ult_usuario,
                            "clave" => $datos['clave'],
                            "estado"=>1
                        );
                        if(!$this->incluirUsurioClave($seguridad))
                        {
                            $error =$this->_db->getError();
                            logger::errorLog($error['2'],'DB');
                            $this->_db->cancel();
                            return false; 
                        }
                    }else
                        {
                            $error =$this->_db->getError();
				            logger::errorLog($error['2'],'DB');
                            $this->_db->cancel();
				            return false;
                        }
                        $this->_db->confirm();    
                    return true;
                }
               
               
        }    
    }

    //-----------------------------------------------------------------------------------------
    //inserta registro en tabla usuario mediante procedimiento almacenado
    //-----------------------------------------------------------------------------------------
    public function insertarUsuario($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_usuario(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }

    public function cambiarClaveUsuario($datos)
    {

        $sql = $this->_dbi->spExec("CALL gest_usuario(?)","$datos");   
              
        if($sql)
            return true;
        else
            return false;

    }


    public function loginUsuario($parameters)
    {    
        if(!$parameters)
            $parameters = '{"action":"search all"}';
        
        $sql = $this->_dbi->sqliQuery("CALL gest_usuario('$parameters')");      
        return $sql;  

    }

    //----------------------------------------------------------------------------------------------------
    public function editar($datos)
    {
        
        $sql = "update usuario set "
                . "correo_usuario = '".$datos['correo']."',"
                . "alias_usuario = '".$datos['alias']."',"
                . "telefono_usuario = '".$datos['telefono']."',"
                . "role_id = '".$datos['role']."'"
                . " where id = '".$datos['id']."'";
        
        //die($sql);        
        $res = $this->_db->exec($sql);
        if(!$res)
        {        
            $error =$this->_db->getError();
            logger::errorLog($error['2'],'DB');
            return FALSE;
        }else
            {           
                return true;
            }
    }        
    //metodo que carga los recursos que tiene asignado un usuario
    public function cargarRecursoUsuario($usuario)
    {
        $sql = "select tru.recurso_id,trec.nombre_recurso,trec.clave,tru.fecha_ult_act from recursousuario as tru,recurso as trec where tru.usuario_id = '$usuario'"
                . " and tru.recurso_id = trec.id group by recurso_id order by recurso_id";
       //die($sql);
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }else
            return array();
        
    }
    //metodo que agregar una pregunta de seguridad a un usuario dado
    public function incluirUsuarioPregunta($datos)
    {
        if($datos)
        {
            $sql = "insert into usuariopregunta("
                    . "usuario_id,"
                    . "pregunta_id,"
                    . "respuesta_pregunta,"
                    . "estatus_usu_pregunta)"
                    . "values('".$datos['usuario']."',"
                    . "'".$datos['pregunta']."',"
                    . "'".base64_encode($datos['respuesta'])."',"
                    . "'1')";
            
            $res = $this->_db->exec($sql);
            if(!$res)
            {  
                $error =$this->_db->getError();
				logger::errorLog($error['2'],'DB');
				return FALSE;
            }else
                {
                    return TRUE;
                }
        }
    }
    
    public function editarUsuarioPregunta($datos)
    {
        $sql = "update usuariopregunta set "
                . "pregunta_id = '".$datos['pregunta']."',"
                . "respuesta_pregunta = '".base64_encode($datos['respuesta'])."'"
                . " where usuario_id = '".$datos['usuario']."' ";
        //die($sql);
        $res = $this->_db->exec($sql);
        if(!$res)
        {
            $error =$this->_db->getError();
			logger::errorLog($error['2'],'DB');
			return FALSE;
        }else
            {
                return TRUE;
            }    
    }         
    
    // metodo que carga la pregunta de seguridad de un usuario
    public function cargarPreguntaUsuario($usuario)
    {
        $sql = "select * from usuariopregunta where estatus_usu_pregunta = '1' and usuario_id = '$usuario'";
        //die($sql);
        $res = $this->_db->query($sql);
        if($res)
        {
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch();
        }else
            return array();
        
        
    }
            
    public function incluirUsurioClave($datos)
    {
        if($datos)
        {
            $sql = "insert into clave("
                    . "fecha_creacion,"
                    . "fecha_expiracion,"
                    . "estatus_clave,"
                    . "clave,"
                    . "usuario_id)"
                    . "values("
                    . "'".$datos['fcreado']."',"
                    . "'".$datos['fexpira']."',"
                    . "'1',"
                    . "'".$datos['clave']."',"
                    . "'".$datos['usuario']."')";
            $res = $this->_db->exec($sql);
            if(!$res)
            {
                $error =$this->_db->getError();
				logger::errorLog($error['2'],'DB');
				return FALSE;
            }else
                {       
                    return TRUE;
                }
            
        }    
    }
    
    // metodo que busca un usuario y lo carga completo
    public function buscarUsuario($cedula, $tipo)
    {

        $sql = "SELECT per.*, usu.* FROM persona AS per, usuario AS usu  WHERE per.cedula_persona = '$cedula' AND per.nacionalidad_persona='$tipo'
        AND usu.persona_id = per.id_persona";
        //die($sql); 
        $res = $this->_db->query($sql);
        if($res){
             $res->setFetchMode(PDO::FETCH_ASSOC);
             return $res->fetch();
        }else
            return array();
    }
    
    public  function buscarAlias($alias)
    {
        $sql = "select count(*)as total from usuario where alias_usuario = '$alias'";
        
        $res = $this->_db->query($sql);
        if($res){
             $dato = $res->fetch();
             if($dato['total']>0)
             {
                 return $dato;
             }else
                 return array("total"=>0);
        }else
            return array("total"=>0);
    }
	
    public  function buscarCorreo($correo)
    {
        $sql = "select count(*)as total from usuario where correo_usuario = '$correo'";    
        $res = $this->_db->query($sql);
        if($res){
            $res->setFetchMode(PDO::FETCH_ASSOC);
             $dato = $res->fetch();
             if($dato['total']>0)
             {
                 return $dato;
             }else
                 return array("total"=>0);
        }else
            return array("total"=>0);
    }
	
    public function actualizarClave($usuario,$clave)
    {
        $sql = "update tclave set clave='$clave' where usuario_id = '$usuario'";                
        $res = $this->_db->exec($sql);
        if(!$res)
        {
            $error =$this->_db->getError();
			logger::errorLog($error['2'],'DB');
			return FALSE;
        }else
            {       
                return TRUE;
            }
            
     
    }
	
    public function usuariosRole($rol)
    {
        $sql = "select usu.id_usuario,usu.role_id,per.razon_social_persona "
                . "from tusuario as usu,tpersona as per,trole as rol where usu.persona_id = per.id_persona"
                . " and usu.role_id = rol.id_role and rol.nombre_role = '".$rol."'";    
        $res = $this->_db->query($sql);
        if($res){
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }else
            return array();
    }
    
	public function eliminarRecurso($usuario,$recurso)
	{
		$sql="delete from recursousuario where usuario_id ='$usuario'  and recurso_id='$recurso' ";
		$res = $this->_db->exec($sql);
        if($res)
           return true;
		else
			{
				$error =$this->_db->getError();
				logger::errorLog($error['2'],'DB');
				return FALSE;	
			}
		
		
	}	

	 
    //==========================================================================
    //METODO QUE RETORNA EL TRABAJADOR RELACIONADO A UN USUARIO
    //==========================================================================
    public function usuarioTrabajador($usuario)
    {
        $sql = "select * from trabajador as trb where trb.persona_id "
                . "in (select id_persona  from persona as per,usuario as usu  "
                . "where usu.id_usuario = '$usuario' and usu.persona_id = per.id_persona)";
        
        //die($sql);
        $res = $this->_db->query($sql);
        if($res){
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch();
        }else
            return array();
        
    }
    //==========================================================================
    
    public function eliminar($id)
    {
        $sql="update usuario set estatus_usuario='9' where id_usuario='$id'";
        //die($sql);
        $res = $this->_db->exec($sql);
        if(!$res)
        {
            return FALSE;
        }else
            {       
                return TRUE;
            }
    }

	public function bloquearUsuario($id)
    {
        $sql="update usuario set condicion_usuario = 'BLOQUEADO',estatus_usuario='2' where id_usuario = '$id''";
        //die($sql);
        $res = $this->_db->exec($sql);
        if(!$res)
        {
            return FALSE;
        }else
            {       
                return TRUE;
            }
    }
	
	public function desbloquearUsuario($id)
    {
        $sql="update usuario set condicion_usuario = 'DESBLOQUEADO',estatus_usuario='1' where id_usuario = '$id''";
        //die($sql);
        $res = $this->_db->exec($sql);
        if(!$res)
        {
            return FALSE;
        }else
            {       
                return TRUE;
            }
    }
	
	public function desconectarUsuario($id)
    {
        $sql="update usuario set condicion_usuario = 'DESCONECTAR',estatus_usuario='1' where id_usuario = '$id''";
        //die($sql);
        $res = $this->_db->exec($sql);
        if(!$res)
        {
            return FALSE;
        }else
            {       
                return TRUE;
            }
    }
	
	public function controlAccesoAdmicion($id,$mes)
	{
		
         $sql = "select count(*)as total,month(fecha) from bitacora where  usuario_id =  '$id' and accion ='ADMICION' and month(fecha)='$mes'  order by fecha";
 
        //die($sql);
        $res = $this->_db->query($sql);
        if($res){
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch();
        }else
            return array();
		
	}
	
	public function controlAccesoDesconexion($id,$mes)
	{
		
         $sql = "select count(*)as total,month(fecha) from bitacora where  usuario_id =  '$id' and accion ='DESCONEXION' and month(fecha)='$mes'  order by fecha";
 
        //die($sql);
        $res = $this->_db->query($sql);
        if($res){
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetch();
        }else
            return array();
		
	}
            
}
