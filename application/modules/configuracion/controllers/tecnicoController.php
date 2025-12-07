<?php
class tecnicoController extends configuracionController
{
    private $_tecnico;
    private $_usuario;
    public function __construct()
    {
        parent::__construct();
        
        $this->_tecnico = $this->loadModel('tecnico');
        $this->_usuario = session::get('id_usuario');
    }

    public function index()
    {
        $this->_view->title = "Tecnicos";
        $this->_view->setJs(array('tecnico'));


        $this->_view->lista = $this->_tecnico->cargarTecnicos();

        $this->_view->renderizar('index','configuracion','Registro de Tecnicos');
        exit();

    }


    public function agregar()
    {
        if(validate::getInt('guardar')==1)
        {
            $datos = array(
                "idFiscal"=>validate::getPostParam('idFiscal'),
                "nombre"=>validate::getPostParam('nombre'),
                "telefonos"=>validate::getPostParam('telefonos'),
                "correos"=>validate::getPostParam('correos'),
                "action"=>'jinsert',
                "id_usuario"=>$this->_usuario
            );
            
            if($this->_tecnico->grabarTecnico(json_encode($datos,true)))
            {
                $this->redireccionar('configuracion/tecnico/index/');
                exit();
            }else
                {
                    $msj = $this->_tecnico->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }
        if(validate::getInt('guardar')==2)
        {
            $datos = array(
                "id"=>validate::getInt('id'),
                "idFiscal"=>validate::getPostParam('idFiscal'),
                "nombre"=>validate::getPostParam('nombre'),
                "telefonos"=>validate::getPostParam('telefono'),
                "correos"=>validate::getPostParam('correo'),
                "action"=>'jupdate',
                "id_usuario"=>$this->_usuario
            );
            
            if($this->_tecnico->grabarTecnico(json_encode($datos,true)))
            {
                $this->redireccionar('configuracion/tecnico/index/');
                exit();
            }else
                {
                    $msj = $this->_tecnico->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }




    } 

    public function editar()
    {
        

    }

    public function eliminar()
    {

    }

    
    public function buscarTecnico()
    {
        $parameters = '{"action":"search","campo":"id","valor":"'.validate::getPostParam('valor').'"}';
        echo json_encode($this->_tecnico->cargarTecnicos($parameters));  
    }
}


