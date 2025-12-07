<?php
class documentoController extends configuracionController
{
    private $_documento;


    public function __construct()
    {
        parent::__construct();

        $this->_documento = $this->loadModel('documento');

    }

    public function index()
    {
        $this->_view->title = "Documentos";
        $this->_view->setJs(array('documentos'));
        $this->_view->setJsPlugin(array('validaciones'));


        $this->_view->lista = $this->_documento->cargarDocumento();

        $numeradores = $this->_documento->cargarNumeradores();
        $numeradores = json_decode($numeradores[0]['Numeradores'], true);

        $definiciones = $this->_documento->cargarDefiniciones();  
        
        if(count($definiciones))
        {
            foreach($definiciones as  $value)
            {
                if(substr($value['siglas'],0,4)=='Html')
                {
                    $plantilla[] = ["id"=>$value['id'],"nombre"=>$value['siglas']];
                }

                
            }   
        }

       // print_r($plantilla);
        $this->_view->plantilla = $plantilla;
        
        $this->_view->numeradores = $numeradores;

        $this->_view->renderizar('index','configuracion','Documentos');

    }



    public function agregar()
    {


        $datos = array(
                'nombre' => validate::getPostParam('nombre'),
                'sigla'  => validate::getPostParam('sigla'),
                'descripcion' =>  validate::getPostParam('descripcion'),
                'estado' => 'ACTIVO',
                'contador' => validate::getPostParam('contador'),
                'plantilla' => validate::getPostParam('plantilla'),
                'id' => validate::getPostParam('id')
            );
            
            

        if(validate::getInt('guardar') == 1)
        {

            if($this->_documento->insertarDocumento($datos))
            {
                $this->redireccionar('configuracion/documento');
            }
            else
                {
                    $this->_view->error = "Error al guardar el documento";
                }
           
        }

        if(validate::getInt('guardar') == 2)
        {
            if($this->_documento->editarDocumento($datos))
            {
                $this->redireccionar('configuracion/documento');
            }
            else
                {
                    $this->_view->error = "Error al guardar el documento";
                }
        }

    } 
    
    
    public function buscarDocumento()
    {
        $parameters = validate::getPostParam('valor');
        echo json_encode($this->_documento->cargarDocumento($parameters));  
        
    }
     
    public function buscarPlantilla()
    {
        $parameters = validate::getPostParam('valor');
        
        $resp = $this->_documento->cargarDefiniciones($parameters);
        $plantilla = substr($resp[0]['valor'],0,stripos($resp[0]['valor'],"Fin primera pagina"));
        $resp[0]['valor'] = $plantilla;
        echo json_encode($resp);
             
    }


}

