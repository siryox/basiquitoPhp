<?php
class despachoController extends almacenController
{
    private $_despacho;
    private $_proveedor;
    private $_credito;
    private $_deposito;
    private $_productor;
    private $_usuario;

    private $_configInv;
    private $_configEmp;


    public function __construct()
    {
        parent::__construct();
        $this->_despacho = $this->loadModel('despacho');
        
        
        $this->_deposito = $this->loadModel('deposito');
        
        $this->_proveedor = $this->loadModel('proveedor','compras');

        $this->_productor = $this->loadModel('productor','productores');

        $this->_usuario = session::get('id_usuario');
        
        $inv = $this->_despacho->cargarConfiguracionDespacho();
        $emp = $this->_despacho->cargarConfiguracionEmpresa();

        $this->_configInv = json_decode($inv[0]['config'],true);
        $this->_configEmp = json_decode($emp[0]['config'],true);

       // print_r($this->_configEmp); exit();
    }


    public function index(int $pagina = 1):void
    {
        $this->_view->title = "Despachos de Almacén";
        $this->_view->setJs(array('despacho'));
        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));
                
        //$this->_view->lista = $this->_despacho->cargarDespachos('{"action":"search","campo1":"tipo","v1":"NE"}');
        $this->_view->lista = $this->_despacho->cargarDespachos('{"action":"search","campo":"tipo","valor":"SAL"}');
        $this->_view->renderizar('index','almacen','Despacho');
        exit();
    }
   
   
    public function agregar()
    {

        if(validate::getInt('guardar')==1)
        {
           // print_r($_POST);
            //exit();

            $tipo = validate::getPostParam('tipo');
            $direccion = validate::getPostParam('direccion');
            
            $destino = array(
                "tipoDoc"=>"NEA",
                "nroDoc"=>"",
                "destDoc"=>validate::getPostParam('destino'),
                "tipoDest"=>$tipo,
                "direccion"=>$direccion
            );

           
            $fechas = array(
                "fecCargaReg"=>date('Y-m-d'),
                "fecEmision"=>validate::getPostParam('emision'),
                "fecVencimiento"=>validate::getPostParam('vencimiento')
            );

            $origen = array(
                "tipoDoc"=>"",
                "nroDoc"=>"",
                "origDoc"=>"",
                "tipoOrig"=>""
            );

            
            
            $codigo = validate::getPostParam('codigo');
            $descripcion = validate::getPostParam('descripcion');
            $cantidad=validate::getPostParam('cantidad');
            $pvp = validate::getPostParam('pvp');
            $id = validate::getPostParam('id');
            $almacen  = validate::getPostParam('almacen');
            $comentario = validate::getPostParam('comentario');
            
            $producto = array();
            if(count($codigo))
            {
                for($i=0;$i<count($codigo);$i++)
                {
                    $producto[] = ["id"=>$id[$i],"codigo"=>$codigo[$i],"descripcion"=>$descripcion[$i],"cantidad"=>$cantidad[$i],"pvp"=>$pvp[$i],"idAlmacen"=>$almacen];
                }
            }

            $datos = array(
                "action"=>"jinsert",
                "tipo"=>"SAL",
                "id_usuario"=>$this->_usuario,
                "idAlmacen"=>$almacen,
                "correlativo"=>"",
                "fechas"=>$fechas,
                "origen"=>$origen,
                "destino"=>$destino,
                "producto"=>$producto,
                "estado"=>"ACTIVO",
                "comentarios"=>$comentario
            );

            //echo json_encode($datos);exit();

            if($this->_despacho->grabarDespacho(json_encode($datos,true)))
            {
                $msj = $this->_despacho->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_despacho->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }


        }
        $this->_view->proveedor = $this->_proveedor->cargarProveedores(); 

        $this->_view->almacen = $this->_deposito->cargarDeposito('{"action":"search all"}');  

        $this->_view->title = "Nuevo Despacho";
        $this->_view->setJs(array('despacho'));
        $this->_view->renderizar('agregar','almacen','Despacho');
        exit();
    }

    public function detalle($id = false)
    {


        if($id)
        {
            $despacho = $this->_despacho->cargarDespachos('{"action":"search","campo":"id","valor":"'.$id.'"}');  
            $producto = json_decode($despacho[0]['producto'],true); 
            
            switch($despacho[0]['destino_tipoDest'])
            {
                case 'proveedor':
                  $destino =  $this->_proveedor->cargarProveedores();
                break;
                case 'productor':
                  $destino = $this->_productor->cargarProductor();  
                break;
                
            }
                
        }
        //print_r($destino);

        $this->_view->proveedor = $destino; 

        $this->_view->despacho = $despacho;
        
        $this->_view->producto = $producto;

        $this->_view->almacen = $this->_deposito->cargarDeposito('{"action":"search all"}');  

        $this->_view->title = "Detalle de Despacho";
        $this->_view->setJs(array('despacho'));
        $this->_view->renderizar('detalle','almacen','Despacho');
        exit();

    }



    public function anular($id=false)
    {

        if(validate::getInt('anular')==3)
        {

            $datos = array(
                "action"=>'janular',
                "id"=>validate::getInt('id'),
                "fecAnulacion"=>validate::getPostParam('fechaAnulacion'),
                "motivo"=>validate::getPostParam('comentario'),
                "id_usuario"=>$this->_usuario
            );
            
            if($this->_despacho->anularDespacho(json_encode($datos,true)))
            {
                $msj = $this->_despacho->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_despacho->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        $movimientos = $this->_despacho->cargarDespachos('{"action":"search","campo":"id","valor":"'.$id.'"}');
        $this->_view->mov = $movimientos;
        $this->_view->title = "Despacho de Almacén";
        $this->_view->setJs(["despacho"]);
        $this->_view->renderizar('anular','almacen','Despachos');
        exit();

    }



    public function eliminar($id=0)
    {


        if(validate::getInt('eliminar')==1)
        {

            $datos = array(
                   "id"=> validate::getInt('id'), 
                   "id_usuario"=>$this->_usuario,
                   "action"=>"jdelete"
            );
        
            if($this->_despacho->eliminarDespacho(json_encode($datos,true)))
            {       
                $this->redireccionar('almacen/despacho/index/');
                exit();
            }else
                {
                    $msj = $this->_despacho->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
        
        }
        if($id)
        {

            $parameters = '{"action":"search","campo1":"id","v1":"'.$id.'"}';

            $datos = $this->_despacho->cargarDespachos($parameters);

            if(count($datos))
            {
                //print_r($datos);
                //exit();
                $this->_view->datos = $datos;
            }

            $this->_view->title = "Despacho";
            $this->_view->setJs(array('despacho'));
            $this->_view->renderizar('eliminar','Almacen','Despacho');
            exit();

        }

    }



    public function cargarNotaImp()
    {
        echo json_encode($this->_despacho->cargarNotaImp(validate::getPostParam('value')),JSON_INVALID_UTF8_IGNORE);
    }



    

    public function buscarProductor()
    {
        $this->_productor = $this->loadModel('productor','productores');
        $parameter = '{"action":"search all"}';
        echo json_encode($this->_productor->cargarProductor($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarProveedor()
    {
        echo json_encode($this->_proveedor->cargarProveedores(),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarProducto()
    {
        $producto = $this->loadModel('producto');
        $parameter = '{"action":"search productos-exi","campo":"descripcion","valor":"'.strtoupper(validate::getPostParam('value')).'","campo1":"almacen","valor1":"'.validate::getPostParam('value1').'"}';
        echo json_encode($producto->cargarProductos($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function cargarProducto()
    {
        $producto = $this->loadModel('producto');
        $parameter = '{"action":"search productos-exi","campo":"id","valor":"'.strtoupper(validate::getPostParam('value')).'"}';
        echo json_encode($producto->cargarProductos($parameter),JSON_INVALID_UTF8_IGNORE);
    }


    public function buscarDeposito()
    {
        $parameter = '{"action":"search a","campo":"idFiscal","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_deposito->cargarDeposito(),JSON_INVALID_UTF8_IGNORE);
    }
}