<?php
class depositoController extends almacenController
{
    private $_deposito;
    private $_usuario;    
    private $_proveedor;
    
    public function __construct() {
        parent::__construct();
        $this->_deposito = $this->loadModel('deposito');
        $this->_proveedor = $this->loadModel('proveedor','compras');
        
        $this->_usuario = session::get('id_usuario');
    }
    
    
    public function index($pagina = 1)
    {    
        $this->_view->title = "Depositos";
        $this->_view->setJs(array('deposito'));
        
       
        
        $this->_view->lista = $this->_deposito->cargarDeposito('{"action":"search all"}');
        
        $this->_view->renderizar('index','almacen','Depósito');
        exit();
    }
    
    // ----- METODO PARA MOSTRAR LA VISTA DE agregar
    public function agregar()
    {
        if(validate::getInt('guardar')==1)
        {
            $telefonos = array(
                "oficina"=>validate::getPostParam('tlf_alm_ofi'),
                "encargado"=>validate::getPostParam('tlf_alm_enc')
            );
            $correos = array(
                "oficina"=>validate::getPostParam('correo_ofi'),
                "encargado"=>validate::getPostParam('correo_enc')
            );

            $mediosContacto=array(
                "telefonos"=>$telefonos,
                "correos"=>$correos,
                "whatsapp"=>validate::getPostParam('tlf_wsp')
            );

            $datos = array(
                "nombre"=>validate::getPostParam('nombAlmacen'),
                "descripcion"=>validate::getPostParam('descAlmacen'),
                "geoloc"=>validate::getPostParam('nombAlmacen'),
                "idProveedor"=>validate::getPostParam('provAlmacen'),
                "direccion"=>validate::getPostParam('direcAlmacen'),
                "estado"=>'ACTIVO',
                "comentarios"=>validate::getPostParam('observacion'),
                "mediosContacto"=>$mediosContacto,
                "action"=>"jinsert",
                "usuario"=>$this->_usuario
            );
            if($this->_deposito->grabarDeposito(json_encode($datos,true)))
            {
                $msj = $this->_deposito->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }
            else
            {
                $msj = $this->_deposito->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }
           
        }
        

        $this->_view->title = "Agregar Depósito";
        $this->_view->setJs(array('deposito'));
    	
        $this->_view->proveedor = $this->_proveedor->cargarProveedores();

        $this->_view->renderizar('agregar','almacen','Deposito');
        exit();
        
    }
    
    // ----- METODO PARA MOSTRAR LA VISTA DE EDICION Y EJECUTAR LOS CAMBIOS
    public function editar($id = FALSE)
    {       
        if(validate::getPostParam('guardar')==2)
        {
            $telefonos = array(
                "oficina"=>validate::getPostParam('tlf_alm_ofi'),
                "encargado"=>validate::getPostParam('tlf_alm_enc')
            );
            $correos = array(
                "oficina"=>validate::getPostParam('correo_ofi'),
                "encargado"=>validate::getPostParam('correo_enc')
            );

            $mediosContacto=array(
                "telefonos"=>$telefonos,
                "correos"=>$correos,
                "whatsapp"=>validate::getPostParam('tlf_wsp')
            );

            $datos = array(
                "id"=>validate::getInt('id'),
                "nombre"=>validate::getPostParam('nombAlmacen'),
                "descripcion"=>validate::getPostParam('descAlmacen'),
                "geoloc"=>validate::getPostParam('nombAlmacen'),
                "idProveedor"=>validate::getPostParam('provAlmacen'),
                "direccion"=>validate::getPostParam('direcAlmacen'),
                "estado"=>'ACTIVO',
                "comentarios"=>validate::getPostParam('observacion'),
                "mediosContacto"=>$mediosContacto,
                "action"=>"jupdate",
                "usuario"=>$this->_usuario
            );
            if($this->_deposito->grabarDeposito(json_encode($datos,true)))
            {
                $msj = $this->_deposito->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }
            else
            {
                $msj = $this->_deposito->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }
        }
       
            $this->_view->title = "Editar Depósito";
            $this->_view->setJs(array('deposito'));
        
            if($id)
            {
                $paramater = '{"action":"search","campo":"id","valor":"'.$id.'"}';
                $this->_view->datos = $this->_deposito->cargarDeposito($paramater);

            }                
            $this->_view->proveedor = $this->_proveedor->cargarProveedores();
            $this->_view->renderizar('editar','archivo','Deposito');
            exit();
    }
    
    
    public function eliminar($id=0)
    {


        if(validate::getInt('eliminar')==1)
        {

            $datos = array(
                   "id"=> validate::getInt('id'), 
                   "idUsuario"=>$this->_usuario,
                   "action"=>"jdelete"
            );
        
            if($this->_deposito->eliminarDeposito(json_encode($datos,true)))
            {
                
                $this->redireccionar('almacen/deposito/index/');
                exit();
            }else
                {
                    $msj = $this->_deposito->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
        
        }
        if($id)
        {

            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';

            $datos = $this->_deposito->cargarDeposito($parameters);

            if(count($datos))
            {
                //print_r($datos);
                //exit();
                $this->_view->datos = $datos;
            }

            $this->_view->title = "Almacenes";
            $this->_view->setJs(array('deposito'));
            $this->_view->renderizar('eliminar','Almacen','Deposito');
            exit();

        }

    }
    //metodo que muestra los productos de un deposito
    public function productosDepositos($deposito=false)
    {
        if($deposito)   
        {
            $paramater = '{"action":"search","campo":"id","valor":"'.$deposito.'"}';
            $this->_view->almacen = $this->_deposito->cargarDeposito($paramater);
            

            $productos = $this->loadModel('producto');
            $productosPorDeposito = $productos->cargarProductoDeposito(["almacen"=>$deposito,"planTrabajo"=>""]);
           // $this->_view->productos = $productos->cargarProductoDeposito(["almacen"=>$deposito,"planTrabajo"=>""]);

            foreach($productosPorDeposito as $row)
            {
                $dataRow = array();
                if($row['existenciaActual']>0)
                {    
                    foreach($row as $key=>$value)
                    {
                        $dataRow[$key]=$value;
                    }
                    $data[] = $dataRow;
                }    
            }
            $this->_view->productos = $data;

        }

        $this->_view->title = "Productos por Almacen";
        $this->_view->setJs(array('deposito'));
        $this->_view->renderizar('productos','Almacen','Deposito');
        exit();
    }
   
   
    public function buscarDeposito()
    {
        $parameters = '{"action":"search","campo":"nombre","valor":"'.validate::getPostParam('valor').'"}';
        echo json_encode($this->_deposito->cargarDeposito($parameters));
    }
    
   
	
}  //FIN DE LA CLASE CONTROLADOR DEL OBJETO
