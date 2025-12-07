<?php
class parroquiaController extends configuracionController
{
	private $_estado;
    private $_parroquia;
    private $_ultimo_registro;
    private $_municipio;
	
    public function __construct()  {
        parent::__construct();		
        $this->_ultimo_registro = 0;
        $this->_parroquia= $this->loadModel('parroquia');
        $this->_municipio = $this->loadModel('municipio','configuracion');
		$this->_estado = $this->loadModel('estado');	
		
    }
    
    public function index($pagina = 1)
    {
    	//$this->_acl->acceso('parroquia_consultar',105,'configuracion-estado-index');
		
        $this->_view->title = "Localidad";
        $this->_view->setJs(array('parroquia'));
        $this->getLibrary('paginador');
        $paginador = new Paginador();
		if(validate::getPostParam('busqueda'))
        {
            $this->_view->lista = $paginador->paginar($this->_parroquia->cargarParroquia(validate::getPostParam('busqueda')),$pagina);
        }
        else      
        {
        	$this->_view->lista = $paginador->paginar($this->_parroquia->cargarParroquia(),$pagina);
        }
        $this->_view->paginacion = $paginador->getView('paginacion','configuracion/parroquia/index');        
        
        $this->_view->estado = $this->_estado->cargarEstado();
        
        $this->_view->renderizar('index','configuracion','Localidad');
		
		
    }
    
    
    public function agregar()
    {
        if(validate::getPostParam('guardar')==1)
        {
        	$this->_acl->acceso('parroquia_agregar',105,'configuracion-parroquia-index');
            $datos = array(
            "descripcion"=>validate::getPostParam('descripcion'),
            "municipio"=>validate::getPostParam('municipio'));
            if($this->_parroquia->incluir($datos))
            {
                $this->_view->error = "Datos Guardados  .....";
                $this->redireccionar('configuracion/parroquia/index/','configuracion');
                exit();
            }else
            {
                $this->_view->error = "Error Guardando parroquia ....." . $this->_parroquia->regLog();
                //$this->_view->renderizar('index','configuracion');
                //exit();
            }
        }    
        if(validate::getPostParam('guardar')==2)
        {
        	$this->_acl->acceso('parroquia_editar',105,'configuracion-parroquia-index');
			
            $datos = array(
            "descripcion"=>validate::getPostParam('descripcion'),
            "municipio"=>validate::getPostParam('municipio'),
            "id"=>validate::getPostParam('id'));
            
            if($this->_parroquia->modificar($datos))
            {
                $this->_view->error = "Datos Guardados  .....";
                $this->redireccionar('configuracion/parroquia/index/','configuracion');
                exit();
            }else
            {
                $this->_view->error = "Error Guardando parroquia ....." . $this->_parroquia->regLog();
                //$this->_view->renderizar('index','configuracion');
                //exit();
            }
        }
		$this->redireccionar('configuracion/parroquia/index/','configuracion');
        exit();
		
    } 

    public function activar($id)
    {
        if($id)
        {
            if($this->_parroquia->activar($id))
            {
                $this->redireccionar('configuracion/parroquia/index');
                exit();
            }else
            {
                error::alerta('1002','configuracion/parroquia/index');
                exit();
            }
            
        }
            
    }        
    public function desactivar($id)
    {
        if($id)
        {
            if($this->_parroquia->desactivar($id))
            {
                $this->redireccionar('configuracion/parroquia/index');
                exit();
            }else
            {
                error::alerta('1002','configuracion/parroquia/index');
                exit();
            }
            
        }
            
    }
    public function buscar($valor)
    {
        if($valor){
            $pagina =1;
            $this->_view->setJs(array('parroquia'));
            $this->getLibrary('paginador');
            $paginador = new Paginador();

            $this->_view->datos = $paginador->paginar($this->_parroquia->buscar($valor),$pagina);
            $this->_view->paginacion = $paginador->getView('paginacion','configuracion/parroquia/index');	
            $this->_view->renderizar('index','configuracion/parroquia/index');
            exit();
        }else
        {
            $this->redireccionar('configuracion/parroquia/index/');
            exit();
        }
    }
    public function buscarParroquia()
    {
         echo json_encode($this->_parroquia->buscar(validate::getPostParam('valor')));
    }        
    public function comprobarParroquia()
    {
     
        echo json_encode($this->_parroquia->comprobarParroquia(strtoupper(validate::getPostParam('descripcion')),validate::getInt('municipio')));
    
    }
     public function eliminarParroquia()
    {
        echo json_encode($this->_parroquia->desactivar(validate::getInt('valor')));
    }
	
	 /*LLAMADOS A PARROQUIAS CORRESPONDIENTES A UN MUNICIPIO*/
    public function buscarParroquias()
    {
        echo json_encode($this->_parroquia->buscarParroquias(validate::getPostParam('valor')));
    }
	//METODO QUE CARGA MUNICIPIOS DE UN ESTADO
	public function buscarMunicipioEstado()
    {
        echo json_encode($this->_municipio->buscarMunicipios(validate::getPostParam('valor')));
    }
	//METODO QUE BUSCA MUNICIPIOS 
	public function cargarMunicipio()
    {
        echo json_encode($this->_parroquia->buscarMunicipioParroquia(validate::getPostParam('valor')));
    }
	
	public function cargarEstado()
    {
        echo json_encode($this->_estado->cargarEstado());
    }
}


?>
