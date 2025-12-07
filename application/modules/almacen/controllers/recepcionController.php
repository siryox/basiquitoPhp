<?php
final class recepcionController extends almacenController
{
    private $_almacen;
    private $_proveedor;
    private $_producto;
    private $_mov;
    private $_usuario;

    public function __construct()
    {
        parent::__construct();
        $this->_almacen = $this->loadModel('deposito');
        $this->_proveedor = $this->loadModel('proveedor','compras');
        $this->_producto = $this->loadModel('producto');
        $this->_mov = $this->loadModel('recepcion');

        $this->_usuario = session::get('id_usuario');


    }


    public function index()
    {


        $movimientos = $this->_mov->cargarRecepciones('{"action":"search","campo":"tipo","valor":"ENT"}');
        
        $this->_view->lista = $movimientos;
        $this->_view->title = "Recepciones de Almacén";

        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));
        $this->_view->setJs(["recepcion"]);
        $this->_view->renderizar('index','almacen','Recepción');
        exit();
    }


    public function agregar()
    {
        if(validate::getInt('guardar')==1)
        {
            
           

            $fechas = array(
                "fecCargaReg"=>date('Y-m-d'),
                "fecEmision"=>validate::getPostParam('emision'),
                "fecVencimiento"=>validate::getPostParam('vencimiento')
            );

            $origen = array(
                "tipoDoc"=>validate::getPostParam('tipoDocumento'),
                "nroDoc"=>validate::getPostParam('correlativo'),
                "origDoc"=>validate::getPostParam('destino'),
                "tipoOrig"=>validate::getPostParam('tipo')
            );

            $destino = array(
                "tipoDoc"=>"",
                "nroDoc"=>"",
                "destDoc"=>"",
                "tipoDest"=>""
            );

            $codigo = validate::getPostParam('codigo');
            $descripcion = validate::getPostParam('descripcion');
            $cantidad=validate::getPostParam('cantidad');
            $pvp = validate::getPostParam('pvp');
            $id = validate::getPostParam('id');
            $almacen = validate::getPostParam('almacen');
            $almacen_id = validate::getPostParam('almacen_id');

            $producto = array();
            if(count($codigo))
            {
                for($i=0;$i<count($codigo);$i++)
                {
                    $nalmacen = 0;
                    if($almacen[$i]>0)
                    {
                        if($almacen[$i] != $almacen_id)
                        {
                            $nalmacen = $almacen[$i];
                        }else
                            $nalmacen = $almacen_id;
                    }else
                        $nalmacen = $almacen_id;
                        
                    $producto[] = ["id"=>$id[$i],"codigo"=>$codigo[$i],"descripcion"=>$descripcion[$i],"cantidad"=>$cantidad[$i],"pvp"=>$pvp[$i],"idAlmacen"=>$nalmacen];
                }
            }
            
            $datos = array(
                "action"=>"jinsert",
                "tipo"=>"ENT",
                "id_usuario"=>$this->_usuario,
                "idAlmacen"=>$almacen_id,
                "correlativo"=>validate::getPostParam('correlativo'),
                "fechas"=>$fechas,
                "origen"=>$origen,
                "destino"=>$destino,
                "producto"=>$producto,
                "estado"=>"ACTIVO",
                "comentarios"=>validate::getPostParam('comentario')
            );

            //print_r($datos);
            //exit();

            if($this->_mov->guardarRecepcion(json_encode($datos,true)))
            {
                $msj = $this->_mov->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();

            }else
                {
                    $msj = $this->_mov->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }
        
        $this->_view->almacen = $this->_almacen->cargarDeposito('{"action":"search all"}');  
        $this->_view->proveedor = $this->_proveedor->cargarProveedores(); 

        $this->_view->setJs(["recepcion"]);
        $this->_view->title = "Recepcion de Almacén";
        $this->_view->renderizar('agregar','almacen','Recepción');
        exit();
    }



    public function detalle($id = false)
    {
        $this->_view->almacen = $this->_almacen->cargarDeposito('{"action":"search all"}');  
        $this->_view->proveedor = $this->_proveedor->cargarProveedores(); 

        $movimientos = $this->_mov->cargarRecepciones('{"action":"search","campo":"id","valor":"'.$id.'"}');
        $producto = json_decode($movimientos[0]['producto'],true);
        
        $subtotal = 0;
        foreach($producto as $pro)
        {
            $subtotal +=intval($pro['cantidad']) * floatval($pro['pvp']); 
        }

        
        $this->_view->producto = $producto;
        $this->_view->subtotal = $subtotal;
        $this->_view->mov = $movimientos;
        $this->_view->title = "Recepción de Almacén";
        $this->_view->renderizar('detalle','almacen','Recepción');
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
            
            if($this->_mov->anularRecepcion(json_encode($datos,true)))
            {
                $msj = $this->_mov->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_mov->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        $movimientos = $this->_mov->cargarRecepciones('{"action":"search","campo":"id","valor":"'.$id.'"}');
        $this->_view->mov = $movimientos;
        $this->_view->title = "Recepción de Almacén";
        $this->_view->setJs(["recepcion"]);
        $this->_view->renderizar('anular','almacen','Recepción');
        exit();

    }

    public function cargarNotaImp()
    {
        echo json_encode($this->_mov->cargarNotaImp(validate::getPostParam('value')),JSON_INVALID_UTF8_IGNORE);
    }


    public function buscarProducto()
    {
        $parameter = '{"action":"search productos-exi","campo":"descripcion","valor":"'.strtoupper(validate::getPostParam('value')).'"}';
        echo json_encode($this->_producto->cargarProductos($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarAlmacen()
    {
        
        echo json_encode($this->_almacen->cargarDeposito('{"action":"search all"}'),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarDocumento()
    {
        $parameter = '{"action":"search","campo":"origen","c1":"tipoDoc","v1":"'.validate::getPostParam('v2').'","c2":"NroDoc","v2":"'.validate::getPostParam('v1').'","c3":"proveedor","v3":"'.validate::getPostParam('v3').'"}';
        echo json_encode($this->_mov->buscarDocRecepcion($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function cargarProducto()
    {
        $parameter = '{"action":"search productos-exi","campo":"id","valor":"'.strtoupper(validate::getPostParam('value')).'"}';
        echo json_encode($this->_producto->cargarProductos($parameter),JSON_INVALID_UTF8_IGNORE);
    }


}