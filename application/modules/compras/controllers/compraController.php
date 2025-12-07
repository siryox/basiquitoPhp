<?php
final class compraController extends comprasController
{
    private $_compra;
    private $_proveedor;
    private $_usuario;
    public function __construct()
    {
        parent::__construct();

        $this->_compra = $this->loadModel('compra');
        $this->_proveedor = $this->loadModel('proveedor');

        $this->_usuario = session::get('id_usuario');
    }


    public function index()
    {

        $compras =  $this->_compra->cargarCompras();

        //print_r($compras);

        $this->_view->compras = $compras;
        $this->_view->title = "Compras";

        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));

        $this->_view->setJs(array("compra"));
        $this->_view->renderizar('index','compra','Registro de Compra');
        exit();
    }


    public function agregar($id = false)
    {

        if(validate::getPostParam('guardar')==1)
        {

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
            if($this->_compra->grabarCompra(json_encode($datos,true)))
            {
            $msj = $this->_compra->getResult();
            $this->redireccionar('reporte/error/alert/',$msj);
            exit();
            }else
            {
                $msj = $this->_compra->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }


        }

        if($id)
        {
            $parameters = '{"action":"search","campo1":"id","v1":"'.$id.'"}';
            $orden = $this->_compra->cargarCompras($parameters);  
            $this->_view->orden = $orden;
            $this->_view->productos =  json_decode($orden[0]['productos'],true);
          
            //print_r($pro); exit();

            $parameters = '{"action":"search","campo":"id","valor":"'.$orden[0]['idProv'].'"}';
            $proveedor = $this->_proveedor->cargarProveedores($parameters);
            $this->_view->proveedor = $proveedor;

        }

        $this->_view->title = "Compra a Proveedor  : ". strtoupper($proveedor[0]['razonSocial']);
        $this->_view->setJs(array("compra"));
        $this->_view->renderizar('agregar','compra','Registro de Compra');
        exit();
    }

    public function editar($id = false)
    {

        if(validate::getPostParam('guardar')==2)
        {

        }

        if($id)
        {
            $parameters = '{"action":"search","campo1":"id","v1":"'.$id.'"}';
            $compra = $this->_compra->cargarCompras($parameters);  
            $this->_view->compra = $compra;
            $this->_view->productos =  json_decode($compra[0]['productos'],true);
          
            //print_r($pro); exit();

            $parameters = '{"action":"search","campo":"id","valor":"'.$compra[0]['idProv'].'"}';
            $proveedor = $this->_proveedor->cargarProveedores($parameters);
            $this->_view->proveedor = $proveedor;


        }
        
        $this->_view->title = "Compra a Proveedor  : ";
        $this->_view->setJs(array("compra"));
        $this->_view->renderizar('editar','compra','Registro de Compra');
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

            if($this->_compra->anularCompra(json_encode($datos,true)))
            {
                $msj = $this->_compra->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_compra->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }
        if($id)
        {
            $parameters = '{"action":"search","campo1":"id","v1":"'.$id.'"}';
            $orden = $this->_compra->cargarCompras($parameters); 
        //print_r($orden); 

            $this->_view->orden = $orden;    
        }    
        //$this->_view->title = "Anular Compras";
        $this->_view->setJs(array("compra"));
        $this->_view->renderizar('anular','compras','Registro de Compra');
        exit();
        
    }


    public function enviarOrdenCpra()
    {
        echo json_encode($this->_compra->enviarOrdenCompra(validate::getPostParam('value'),$this->_usuario));
    }


    public function cargarNotaImp()
    {
        echo json_encode($this->_compra->cargarNotaImp(validate::getPostParam('value')));
    }

}

