<?php
class indexController extends Controller
{
    private $_usuario;
    public function __construct() {
        parent::__construct();
        $this->getHelper("validate");
        $this->getHelper("logger");
        $this->_usuario = $this->loadModel('usuario','seguridad');
    }

    public function index()
    {
        $this->redireccionar('index/login');
    }


    public function login()
    {
        
        if(validate::getInt('entrar')==1)
        {
            //$this->getLibrary('security');
            //$security = new security();
            //$security->conection(validate::getPostParam('usuario'),validate::getPostParam('clave'))

            $parameters = '{"action":"jlogin","usuario":"'.validate::getPostParam('usuario').'","clave":"'.validate::getPostParam('clave').'"}';
            $usuario = $this->_usuario->loginUsuario($parameters);
            if(count($usuario)>0)
            {
                if(array_key_exists('response',$usuario))
                {


                }else
                    {
                        if($usuario[0]['condicion_usuario']=='DESCONECTADO' || $usuario[0]['condicion_usuario']=='CONECTADO' )
                        {
                            session::set('autenticado',1);
                            session::set('id_usuario',$usuario[0]['id']);
                            session::set('alias',$usuario[0]['alias_usuario']);

                            session::set('role_id',$usuario[0]['role_id']);
                            session::set('correo', $usuario[0]['correo_usuario']);
                            session::set('estatus',$usuario[0]['estatus_usuario']);
                            session::set('empresa',$usuario[0]['empresa_id']);
                        
                            session::set('tiempo',time());

                        }else
                            {
                                //if($usuario[0]['ip_ult_ent']==$security->getRealIP())
                                //{
                                //    session::acceso();
                                //}
                            }

                            $this->redireccionar("intranet");
                            exit();
                    }
            }                    

        }
        //die("llegue");
        $this->_view->titulo = "";
        $this->_view->setTemplate('barra');
        $this->_view->setCss(['ingresar']);
        $this->_view->renderizar("ingresar");
        exit();
    }

    public function logup()
    {

        $usuario = session::get('id_usuario');
       // $this->getLibrary('security');
       // $security = new security();
        
       // $security->outUser($usuario);
       $parameters = '{"action":"jlogup","id_usuario":"'.$usuario.'"}';
       $res = $this->_usuario->loginUsuario($parameters);

       //print_r($res);
       //exit();
        session::destroy();	
        $this->redireccionar("index/login");
        exit();
	}

}
