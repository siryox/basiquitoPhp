<?php
class proveedorController extends comprasController
{
    private $_usuario;
    private $_proveedor;
    private $_producto;


    public function __construct()
    {
        parent::__construct();
        $this->_producto = $this->loadModel('producto','almacen');
        $this->_proveedor = $this->loadModel('proveedor');
        $this->_usuario = session::get("id_usuario");
        
    }

    public function index()
    {
        $this->_view->lista = $this->_proveedor->cargarProveedores();
        $this->_view->setJs(array('proveedor'));
        $this->_view->title = "Proveedores";
        $this->_view->renderizar('index','compra','Registro de Proveedor');
        exit();
  
    }


    public function agregar()
    {
        if(validate::getPostParam('guardar')==1)
        {
            $telefono = array(
                "ventas"=>validate::getPostParam('tlf_ventas'),
                "admon"=>validate::getPostParam('tlf_admon'),
                "almacen"=>validate::getPostParam('tlf_almacen')
            );

            $correo = array(
                "ventas"=>validate::getPostParam('correo_ventas'),
                "admon"=>validate::getPostParam('correo_administracion'),
                "almacen"=>validate::getPostParam('correo_almacen')
            );
             
            $mediosContacto = array(
                "telefonos"=>$telefono,
                "correos"=>$correo,
                "whatsapp"=>validate::getPostParam('tlf_wsp')
            );


            $proveedor = array(
                "tipoPersona"=>validate::getPostParam('tipo'),
                "idFiscal"=>validate::getPostParam('idFiscal'),
                "razonSocial"=>strtoupper(validate::getPostParam('razonSocial')),
                "domicilioFiscal"=>strtoupper(validate::getPostParam('domicilioFiscal')),
                "comentarios"=>strtoupper(validate::getPostParam('comentarios')),
                "mediosContacto"=>$mediosContacto,
                "action"=>"jinsert",
                "id_usuario"=>$this->_usuario
            );

            $datos = json_encode($proveedor,true);
            if($this->_proveedor->grabarProveedor($datos))
            {
                    $msj = $this->_proveedor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
            }else
                {
                    $msj = $this->_proveedor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }
        //$this->_view->lista = $this->_proveedor->cargarProveedores();
        $this->_view->setJs(array('proveedor'));
        $this->_view->title = "Proveedores";
        $this->_view->renderizar('agregar','compra','Registro de Proveedor');
        exit();
    }  




    public function editar($id = false)
    {
        
        if(validate::getPostParam('guardar')==2)
        {
            $telefono = array(
                "ventas"=>validate::getPostParam('tlf_ventas'),
                "admon"=>validate::getPostParam('tlf_admon'),
                "almacen"=>validate::getPostParam('tlf_almacen')
            );

            $correo = array(
                "ventas"=>validate::getPostParam('correo_ventas'),
                "admon"=>validate::getPostParam('correo_administracion'),
                "almacen"=>validate::getPostParam('correo_almacen')
            );
             
            $mediosContacto = array(
                "telefonos"=>$telefono,
                "correos"=>$correo,
                "whatsapp"=>validate::getPostParam('tlf_wsp')
            );


            $proveedor = array(
                "id"=>validate::getInt('id'),
                "tipoPersona"=>validate::getPostParam('tipo'),
                "idFiscal"=>validate::getPostParam('idFiscal'),
                "razonSocial"=>strtoupper(validate::getPostParam('razonSocial')),
                "domicilioFiscal"=>strtoupper(validate::getPostParam('domicilioFiscal')),
                "comentarios"=>strtoupper(validate::getPostParam('comentarios')),
                "mediosContacto"=>$mediosContacto,
                "action"=>"jupdate",
                "id_usuario"=>$this->_usuario
            );

            $datos = json_encode($proveedor,true);
            if($this->_proveedor->grabarProveedor($datos))
            {
                    $msj = $this->_proveedor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
            }else
                {
                    $msj = $this->_proveedor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        if($id)
        {
            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';
            $this->_view->datos = $this->_proveedor->cargarProveedores($parameters);
        }

        
        $this->_view->setJs(array('proveedor'));
        $this->_view->title = "Proveedores";
        $this->_view->renderizar('editar','compra','Registro de Proveedor');
        exit();
    }


    public function eliminar($id = false)
    {
        if(validate::getInt('eliminar')==1)
        {

            $datos = array(
                   "id"=> validate::getInt('id'), 
                   "id_usuario"=>$this->_usuario,
                   "action"=>"jdelete"
            );
        
            if($this->_proveedor->eliminarProveedores(json_encode($datos,true)))
            {
                
                $this->redireccionar('compras/proveedor/index/');
                exit();
            }else
                {
                    $msj = $this->_proveedor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
        
        }
        if($id)
        {

            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';

            $datos = $this->_proveedor->cargarProveedores($parameters);

            if(count($datos))
            {
                //print_r($datos);
                //exit();
                $this->_view->datos = $datos;
            }

            $this->_view->title = "Proveedores";
            $this->_view->setJs(array('proveedor'));
            $this->_view->renderizar('eliminar','compra','Registro de Proveedor');
            exit();

        }
        
    }

    public function compra($id = false)
    {

        if(validate::getPostParam('guardar'))
        {
            // cargo modelo de datos de compra
            $compra = $this->loadModel('compra');

           // print_r($_POST); exit();
            $filas = validate::getInt('filas');
            $codigo = validate::getPostParam('codigo');
            $descripcion = validate::getPostParam('descripcion');
            $pvp = validate::getPostParam('pvp');
            $cantidad = validate::getPostParam('cantidad');
            $impuesto = validate::getPostParam('tsaImpuesto');
            $totalFila = validate::getPostParam('subtotal');
            for($i = 0; $i < $filas;$i++)
            {
                if(!empty($codigo[$i]))
                    $productos[] = ["codProd"=>$codigo[$i],"descripcion"=>$descripcion[$i],"cantidad"=>$cantidad[$i],"cantPendiente"=>$cantidad[$i],"precio"=>$pvp[$i],"deposito"=>"","tsaImpuesto"=>$impuesto[$i]];
            }
            

            
            $plazo = (validate::getInt('dias_cre')>0)?validate::getInt('dias_cre'):'0';
            $orden = (validate::getInt('orden')>0)?validate::getInt('orden'):'0';
            if($orden > 0)
            {
                $otros = ["docOrigen"=>["tipo"=>"ord-cpra","referencia"=>$orden],"docAfectado"=>["tipo"=>"","referencia"=>""]];
            }

            $datos = [
                "action"=>'jinsert',
                "emision"=>validate::getPostparam('emision'),
                "vencimiento"=>validate::getPostparam('vencimiento'),
                "tipo"=>'compra',
                "idFiscalProv"=>validate::getPostparam('rif_proveedor'),
                "idProv"=>validate::getPostparam('proveedor'),
                "productos"=>$productos,
                "subTotal"=>validate::getPostparam('subtotalDoc'),
                "impuestos"=>validate::getPostparam('impuestoDoc'),
                "montoTotal"=>validate::getPostparam('totalDoc'),
                "estado"=>'ACTIVO',
                "comentarios"=>validate::getPostparam('comentario'),
                "otrosDatos"=>$otros,
                "id_usuario"=>$this->_usuario,
                "condicion"=>validate::getPostparam('condicion'),
                "plazoCredito"=>$plazo,
                "referencia"=>validate::getPostparam('referencia')


            ];


            //print_r($datos); exit();
            if($compra->grabarCompra(json_encode($datos,true)))
            {
            $msj = $compra->getResult();
            $this->redireccionar('reporte/error/alert/',$msj);
            exit();
            }else
            {
                $msj = $compra->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }


        }

        if($id)
        {          
            //print_r($pro); exit();
            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';
            $proveedor = $this->_proveedor->cargarProveedores($parameters);
            $this->_view->proveedor = $proveedor;

        }

        $this->_view->title = "Compra a Proveedor  : ". strtoupper($proveedor[0]['razonSocial']);
        $this->_view->setJs(array("compra"));
        //$this->_view->setJs(['orden']);  
        $this->_view->renderizar('compra','compra','Registro de Compra');
        exit();
    }

    public function ordenCompra($id = false)
    {
        if(validate::getInt('guardar')==1)
        {
              
           // print_r($_POST); exit();

            $idProd = validate::getPostParam('id');
            $codigo = validate::getPostParam('codigo');
            $descripcion = validate::getPostParam('descripcion');
            $pvp = validate::getPostParam('pvp');
            $cantidad = validate::getPostParam('cantidad');
            $impuesto = validate::getPostParam('tsaImpuesto');

            $plazo = (validate::getInt('dias_cre')>0)?validate::getInt('dias_cre'):'0';

            if(count($idProd)) 
            {
                for($i = 0;$i < count($idProd);$i++)
                {
                    $productos[] = ["codProd"=>$codigo[$i],"descripcion"=>$descripcion[$i],"cantidad"=>$cantidad[$i],"cantPendiente"=>$cantidad[$i],"precio"=>$pvp[$i],"deposito"=>"","tsaImpuesto"=>$impuesto[$i]];
                }
            }

            $datos = [
                "action"=>"InsertOrdenCompra",
                "emision"=>validate::getPostParam('emision'),
                "vencimiento"=>validate::getPostParam('vencimiento'),
                "tipo"=>'ord-cpra',
                "idFiscalProv"=>validate::getPostParam('rif_proveedor'),
                "idProv"=>validate::getInt('proveedor'),
                "productos"=>$productos,
                "subTotal"=>validate::getPostParam('subtotalDoc'),
                "impuestos"=>validate::getPostParam('impuestoDoc'),
                "montoTotal"=>validate::getPostParam('totalDoc'),
                "estado"=>'ACTIVO',
                "comentarios"=>validate::getPostParam('comentario'),
                "id_usuario"=>$this->_usuario,
                "condicion"=>validate::getPostParam('condicion'),
                "plazoCredito"=>$plazo


            ];
            //print_r($datos); exit();
            if($this->_proveedor->grabarOrden(json_encode($datos,true)))
            {
                $msj = $this->_proveedor->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_proveedor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        if($id)
        {
            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';
            $proveedor = $this->_proveedor->cargarProveedores($parameters);
            $this->_view->proveedor = $proveedor;
           

        }

        $this->_view->setJs(['orden']);    
        $this->_view->title = "Orden de Compra Proveedor  : ". strtoupper($proveedor[0]['razonSocial']);
        $this->_view->renderizar('orden','compra','Registro de Proveedor');
        exit();
    }


    
    public function buscarProducto()
    {
        $parameters = '{"action":"search","campo":"descripcion","valor":"'.strtoupper(validate::getPostParam('value')).'"}';
        echo json_encode($this->_producto->cargarProductos($parameters));
    }
 
    
    
    public function buscarProveedor()
    {
        $parameters = '{"action":"search","campo":"idFiscal","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_proveedor->cargarProveedores($parameters));
    }


}

