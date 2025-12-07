<?php
class productoController extends almacenController
{
    private $_producto;
    private $_deposito;
    private $_clasificacion;
    //private $_grupo;
    //private $_presentacion;
    //private $_marca;
    private $_usuario;
    public function __construct() {
        parent::__construct();
        $this->_producto = $this->loadModel('producto');
       
	    $this->_clasificacion = $this->loadModel('clasificacion');
	    $this->_deposito = $this->loadModel('deposito');
		$this->_usuario = session::get('id_usuario');
    }
    
    public function index($pagina = 1) {

        //$this->_acl->acceso('producto_agregar',5050,'');
 
       $producto =  $this->_producto->cargarProductos();
       $this->_view->lista = $producto; 
       $this->_view->almacenes = $this->_deposito->cargarDeposito();

       $this->_view->setCss(array('print'));
       $this->_view->setJsPlugin(array('print'));
       $this->_view->setJs(array("producto"));
	   $this->_view->title = "Productos";
	   $this->_view->renderizar('index','almacen','Productos y Servicios');
       exit();
               
    }
    
	//-----------------------------------------------------------------------------------
	//metodo que agrega nuevo producto 
	//----------------------------------------------------------------------------------
    public function agregar()
    {
        if(validate::getInt('guardar')==1)
        {     
            $exiMin = validate::getPostParam('exiMinima');
            if($exiMin < 1)
                $exiMin = 1;  
            //print_r($_POST);exit();
            $tasaIva = (!empty(validate::getPostParam('tasaIva'))?validate::getPostParam('tasaIva'):'0.00');
            $clasificacion = array(
                "grupo"=>validate::getPostParam('clasificacion'),
                "subGrupo"=>""
            );

            $precios = array(
                "ctoUltCompra"=>validate::getPostParam('costoUltcpra'),
                "ctoPromedio"=>validate::getPostParam('costoPromedio'),
                "ctoProrrateado"=>validate::getPostParam('costoProrrateado'),
                "ctoActual"=>validate::getPostParam('costoActual'),
                "ctoActualMercado"=>validate::getPostParam('costoActualMercado'),
                "tasaUtil"=>validate::getPostParam('utilidad'),
                "tasaUtilMinima"=>validate::getPostParam('utilidadMin'),
                "pvp1"=>validate::getPostParam('pvp1'),
                "pvp2"=>validate::getPostParam('pvp2')
            );


            $datos = array(
                "codigo"=>        strtoupper(validate::getPostParam('codigo')),
                "descripcion"=>   strtoupper(validate::getPostParam('descripcion')),
                //"codigoAlterno"=> strtoupper(validate::getPostParam('codigoAlterno')),
                "etiquetaExcel"=> strtoupper(validate::getPostParam('etiquetaExcel')),
                "presentacion"=>  strtoupper(validate::getPostParam('presentacion')),
                "comentarios"=>   strtoupper(validate::getPostParam('comentarios')),
                "consignado"=>    validate::getPostParam('consignado'),
                "estado"=>  "ACTIVO",
                "unidMedida"=> strtoupper(validate::getPostParam('unidadMedida')),
                "exiMinina"=> $exiMin,
                "tasaImpuesto"=>$tasaIva,
                "clasificacion"=>$clasificacion,
                "precios"=>$precios,
                "action"=>"jinsert",
                "id_usuario"=>$this->_usuario
                );
		    //print_r($datos);exit();	
            if($this->_producto->grabarProducto(json_encode($datos,true)))
            {
                $msj = $this->_producto->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else{
                $msj = $this->_producto->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
                }           
                      
        }
		
        $this->_view->setJs(array("producto"));
        
           
        $this->_view->clasificacion = $this->_clasificacion->listar();
	    $this->_view->title = "Agregar Producto";
        $this->_view->renderizar('agregar','almacen',"Prouctos y Servicios");
        exit();
    }        
    
    //--------------------------------------------------------------------------
    //METODO QUE PERMITE EDITAR DATOS DE UN PRODUCTO
    //--------------------------------------------------------------------------
    public function editar($id = false)
    {
        if(validate::getInt('guardar')==2)
        { 
            if(!isset($_POST['consignado']))
            {
                $consignado = 0;
            }else
                $consignado = validate::getPostParam('consignado');
           
            $clasificacion = array(
                "grupo"=>validate::getPostParam('clasificacion'),
                "subGrupo"=>""
            );

            $precios = array(
                "ctoUltCompra"=>validate::getPostParam('costoUltcpra'),
                "ctoPromedio"=>validate::getPostParam('costoPromedio'),
                "ctoProrrateado"=>validate::getPostParam('costoProrrateado'),
                "ctoActual"=>validate::getPostParam('costoActual'),
                "ctoActualMercado"=>validate::getPostParam('costoActualMercado'),
                "tasaUtil"=>validate::getPostParam('utilidad'),
                "tasaUtilMinima"=>validate::getPostParam('utilidadMin'),
                "pvp1"=>validate::getPostParam('pvp1'),
                "pvp2"=>validate::getPostParam('pvp2')
            );
            $exiMin = validate::getPostParam('exiMinima');
            if($exiMin < 1)
                $exiMin = 1;

            $datos = array(
                "id"=> validate::getInt('id'),
                "codigo"=>        strtoupper(validate::getPostParam('codigo')),
                "descripcion"=>   strtoupper(validate::getPostParam('descripcion')),
                //"codigoAlterno"=> strtoupper(validate::getPostParam('codigoAlterno')),
                "etiquetaExcel"=> strtoupper(validate::getPostParam('etiquetaExcel')),
                "presentacion"=>  strtoupper(validate::getPostParam('presentacion')),
                "comentarios"=>   strtoupper(validate::getPostParam('comentarios')),
                "consignado"=>    $consignado,
                "estado"=>  "ACTIVO",
                "unidMedida"=> strtoupper(validate::getPostParam('unidadMedida')),
                "exiMinima"=> $exiMin,
                "tasaImpuesto"=>validate::getPostParam('tasaIva'),
                "clasificacion"=>$clasificacion,
                "precios"=>$precios,
                "action"=>"jupdate",
                "usuario"=>$this->_usuario
                );
            //print_r($datos);exit();	
            if($this->_producto->grabarProducto(json_encode($datos,true)))
            {
                $msj = $this->_producto->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else{
                    $msj = $this->_producto->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
        }


        if($id)
        {
            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';
            $datos = $this->_producto->cargarProductos($parameters);
            $this->_view->datos = $datos;
           
        }
        $this->_view->clasificacion = $this->_clasificacion->listar();
        $this->_view->setJs(array("producto"));
        $this->_view->title = "Editar Producto";
        $this->_view->renderizar('editar','almacen','Prouctos y Servicios');
        exit();

    }        
    
    
    
   public function existencia($codigo)
   {
        if($codigo)
        {
            $productos = $this->_producto->cargarProductos('{"action":"search productos-exi","campo":"codigo","valor":"'.$codigo.'"}');

            $this->_view->productos  = $productos;

            print_r($productos);
        }

        $this->_view->title = "Existencia de Producto";
        $this->_view->renderizar('existencia','almacen','Productos y Servicios');
        exit();


   }

    
   public function cargarNotaImp()
   {    

        $codigo = validate::getPostParam('value');
        $fi=(empty(validate::getPostParam('fi')))?'':validate::getPostParam('fi');
        $ff=(empty(validate::getPostParam('ff')))?'':validate::getPostParam('ff');
        $almacen = (empty(validate::getPostParam('almacen')))?'':validate::getPostParam('almacen');
        $usuario = $this->_usuario;

       echo json_encode($this->_producto->cargarNotaImp($codigo,$usuario,$fi,$ff,$almacen,JSON_INVALID_UTF8_IGNORE));
       exit();

   }



    
    //==========================================================================
    //METODO QUE PERMITE BUSCAR EL PROUCTO POR SU NOMBRE
    //==========================================================================
	public function validarCodigo()
	{
        $parameters = '{"action":"search","campo":"codigo","valor":"'.validate::getPostParam('codigo').'"}';
		echo json_encode($this->_producto->cargarProductos($parameters));       				
	}
    
    //==========================================================================
    //METODO QUE PERMITE BUSCAR LAS CLASIFICACIONES POR COMPARACION
    //==========================================================================	
	public function buscarClasificacion()
	{
		echo json_encode($this->_clasificacion->autoClasificacion($this->getGetParam('term')));		
	}
    //==========================================================================
    //METODO QUE PERMITE VALIDAR LAS CLASIFICACIONES 
    //==========================================================================	
	public function validarClasificacion() 
	{
		echo json_encode($this->_clasificacion->buscarClasificacion($this->getPostParam('valor')));		
	}
	
	

        
    
	
	
}