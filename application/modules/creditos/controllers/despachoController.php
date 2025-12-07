<?php
final class despachoController extends creditosController
{
    private $_productor;
    private $_credito;
    private $_deposito;
    private $_producto;
    private $_usuario;
    private $_despacho;

    private $_configInv;
    private $_configEmp;
    private $_configCalDsp; 
    private $_configUpdPvp;


    public function __construct()
    {
        parent::__construct();
        
        $this->_despacho =  $this->loadModel('despacho');
        $this->_productor = $this->loadModel('productor','productores');
        $this->_credito   = $this->loadModel('credito');
        $this->_deposito =  $this->loadModel('deposito','almacen');
        $this->_producto =  $this->loadModel('producto','almacen');
        $this->getLibrary('rate_exchange');




        $this->_usuario = session::get('id_usuario');

        $inv = $this->_despacho->cargarConfiguracionDespacho();
        $emp = $this->_despacho->cargarConfiguracionEmpresa();
        $cal = $this->_despacho->cargarConfiguracioncalculo();
        $pvp = $this->_despacho->cargarConfigUpdtePvp();

        $this->_configInv = json_decode($inv[0]['config'],true);
        $this->_configEmp = json_decode($emp[0]['config'],true);
        $this->_configCalDsp = json_decode($cal[0]['config'],true);
        $this->_configUpdPvp = json_decode($pvp[0]['config'],true);
    }

    public function index()
    {

        $this->_view->title = "Despacho de Insumos";
        $this->_view->setJs(array('despacho'));
        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));
                
        //$this->_view->lista = $this->_despacho->cargarDespachos('{"action":"search","campo1":"tipo","v1":"NE"}');
        $this->_view->lista = $this->_despacho->cargarDespachos();
        $this->_view->renderizar('index','creditos','Despacho de Insumos');
        exit();


    }


    public function agregar()
    {

        if(validate::getInt('guardar')==1)
        {
            
            $idDeposito = validate::getPostParam('idDeposito');
            $deposito = validate::getPostParam('deposito');
            $codigo = validate::getPostParam('codigo');
            $descripcion = validate::getPostParam('descripcion');
            $precio = validate::getPostParam('pvp');
            $cantidad = validate::getPostParam('cantidad');
            $totalLinea = validate::getPostParam('subtotal');
            $idProducto = validate::getPostParam('id');
            $tsaImpuesto = validate::getPostParam('tsaImpuesto');
            $costo  = validate::getPostParam('costo');    
            
            for($i=0;$i < count($idProducto);$i++)
            {
                //$precio = str_replace(',','.',$precio[$i]);
                $producto[] = ["codigo"=>$codigo[$i],"descripcion"=>$descripcion[$i],"idAlmacen"=>$idDeposito[$i],"nombreAlmacen"=>$deposito[$i],"cantidad"=>$cantidad[$i],"costo"=>$costo[$i],"pvp"=>$precio[$i],"tasaImpto"=>$tsaImpuesto[$i],"cantEntregada"=>$cantidad[$i]];
            }

            $datos = array(
                "action"=>"jinsert",
                "fecha"=>validate::getPostParam('fecha'),
                "idCredito"=>validate::getPostParam('credito'),
                "productos"=>$producto,
                "subtotal"=>validate::getPostParam('subtotalDoc'),
                "totImponible"=>validate::getPostParam('baseImponibleDoc'),
                "impuesto"=>validate::getPostParam('impuestoDoc'),
                "totExcento"=>validate::getPostParam('exentoDoc'),
                "montoTotal"=>validate::getPostParam('totalDoc'),
                "comentarios"=>validate::getPostParam('comentario'),
                "id_usuario"=>$this->_usuario
            );

            //echo json_encode($datos);exit();

            if($this->_despacho->grabarDespacho(json_encode($datos,true)))
            {
                $msj = $this->_producto->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_despacho->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }


        }
        $this->_view->productor = $this->_productor->cargarProductor('{"action":"search all"}');

        $this->_view->configEmp = $this->_configEmp;
        $this->_view->configCal = $this->_configCalDsp;
        $this->_view->configPvp = $this->_configUpdPvp;

       
        $this->_view->title = "Nuevo Despachos";
        $this->_view->setJs(array('despacho'));
        $this->_view->renderizar('agregar','creditos','Despacho de Insumos');
        exit();

    }

    //----------------------------------------------------------------------------------------
    //metodo que permite hacer entrega de los insumos, marcando los productos como entregados
    //descontando en inventario.
    //----------------------------------------------------------------------------------------
    public function editar($id = 0)
    {

        if(validate::getInt('guardar')==3)
        {
            $codigo = validate::getPostParam('codigo');
            $entregar = (validate::getPostParam('entregar')>0)?validate::getPostParam('entregar'):0;
            $tipo     = validate::getPostParam('tipo');

            $idDeposito = validate::getPostParam('idDeposito');
            $deposito = validate::getPostParam('deposito');
            //$codigo = validate::getPostParam('codigo');
            $descripcion = validate::getPostParam('descripcion');
            $precio = validate::getPostParam('pvp');
            $cantidad = validate::getPostParam('cantidad');
            $totalLinea = validate::getPostParam('subtotal');
            $idProducto = validate::getPostParam('id');
            $tsaImpuesto = validate::getPostParam('tsaImpuesto');
            $costo  = validate::getPostParam('costo');   



            for($i=0;$i < count($codigo);$i++)
            {
                //$precio = str_replace(',','.',$precio[$i]);
                if($tipo == 'NDI')
                {
                    $producto[] = ["codigo"=>$codigo[$i],"descripcion"=>$descripcion[$i],"idAlmacen"=>$idDeposito[$i],"nombreAlmacen"=>$deposito[$i],"cantidad"=>$cantidad[$i],"costo"=>$costo[$i],"pvp"=>$precio[$i],"tasaImpto"=>$tsaImpuesto[$i],"cantDevolucion"=>$cantidad[$i]];

                   // ["codigo"=>$codigo[$i],"descripcion"=>$descripcion[$i],"idAlmacen"=>$idDeposito[$i],"nombreAlmacen"=>$deposito[$i],"cantidad"=>$cantidad[$i],"costo"=>$costo[$i],"pvp"=>$precio[$i],"tasaImpto"=>$tsaImpuesto[$i],"cantDevolucion"=>$devolver[$i]];
                }                    
                else
                    $producto[] = ["codigo"=>$codigo[$i],"descripcion"=>$descripcion[$i],"idAlmacen"=>$idDeposito[$i],"nombreAlmacen"=>$deposito[$i],"cantidad"=>$cantidad[$i],"costo"=>$costo[$i],"pvp"=>$precio[$i],"tasaImpto"=>$tsaImpuesto[$i],"cantEntregada"=>$entregar[$i]];
            }

            $datos = array(
                "action"=>"jupdateentregas",
                "id"=>validate::getInt('id'),
                "productos"=>$producto,
                "subtotal"=>validate::getPostParam('subtotalDoc'),
                "totImponible"=>validate::getPostParam('baseImponibleDoc'),
                "impuesto"=>validate::getPostParam('impuestoDoc'),
                "totExcento"=>validate::getPostParam('exentoDoc'),
                "montoTotal"=>validate::getPostParam('totalDoc'),
                "comentarios"=>validate::getPostParam('comentario'),
                "id_usuario"=>$this->_usuario
            );

            //echo json_encode($datos);
            //exit();
            if($this->_despacho->actualizarDespacho(json_encode($datos)))
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

        $this->_view->configEmp = $this->_configEmp;
        $this->_view->configCal = $this->_configCalDsp;
        $this->_view->configPvp = $this->_configUpdPvp;

        if($id>0)
        {
            $transaccion = $this->_despacho->cargarDespachos('{"action":"search","campo1":"id","v1":"'.$id.'"}');
            
            $credito = $this->_credito->cargarCreditos('{"action":"search","campo1":"id","v1":"'.$id.'"}');

            $this->_view->datos = $transaccion;
            $this->_view->productos = json_decode($transaccion[0]['productos'],true);
        }



        $this->_view->title = "Entrega de Insumos";
        $this->_view->setJs(array('despacho'));
        $this->_view->renderizar('editar','creditos','Despachos');
        exit();
    }



    //----------------------------------------------------------------------------------------
    //metodo que permite hacer entrega de los insumos, marcando los productos como entregados
    //descontando en inventario.
    //----------------------------------------------------------------------------------------
    public function devolucion($id = 0)
    {

        if(validate::getInt('guardar')==4)
        {
            $codigo = validate::getPostParam('codigo');

            
            $devolver = (validate::getPostParam('devolver')>0)?validate::getPostParam('devolver'):0;

            $idDeposito = validate::getPostParam('idDeposito');
            $deposito = validate::getPostParam('deposito');
            //$codigo = validate::getPostParam('codigo');
            $descripcion = validate::getPostParam('descripcion');
            $precio = validate::getPostParam('pvp');
            $cantidad = validate::getPostParam('cantidad');
            $totalLinea = validate::getPostParam('subtotal');
            $idProducto = validate::getPostParam('id');
            $tsaImpuesto = validate::getPostParam('tsaImpuesto');
            $costo  = validate::getPostParam('costo');   
            $fecha  = validate::getPostParam('fecha');
           

            $montoTotal = 0;
            $totalImponible = 0;
            $totalNota =0;
            $totalIva =0;
            $totalExcento =0;
            $subtotalTotal =0;
            $imponible =0;
            $excento =0;
            $iva =0;

            for($i=0;$i < count($codigo);$i++)
            {
                //echo $devolver[$i];
                //echo $precio[$i];
                $devolver[$i] = (!empty($devolver[$i]))?$devolver[$i]:0;
                if($devolver[$i] >0)
                {

                    $prod = $this->_producto->cargarProductos('{"action":"search","campo":"codigo","valor":"'.$codigo[$i].'"}');


                    //$precio = str_replace(',','.',$precio[$i]);
                 
                    $producto[] = ["codigo"=>$codigo[$i],"descripcion"=>$descripcion[$i],"idAlmacen"=>$idDeposito[$i],"nombreAlmacen"=>$deposito[$i],"cantidad"=>$cantidad[$i],"costo"=>$costo[$i],"pvp"=>$precio[$i],"tasaImpto"=>$tsaImpuesto[$i],"cantDevolucion"=>$devolver[$i],"id"=>$prod[0]['id']];

                    $subtotal       = $devolver[$i]  * $precio[$i];
                    if($tsaImpuesto[$i]>0)
                    {
                        $imponible  = $devolver[$i]  * $precio[$i];
                        $iva        = ($imponible * ($tsaImpuesto[$i]/100 )+1) - $imponible;
                    }else
                        {
                            $excento = $devolver[$i]  * $precio[$i];
                            $imponible=0;    
                        }
                    
                $totalImponible = $totalImponible + $imponible;
                $totalIva       = $totalIva + $iva;
                $totalExcento   = $totalExcento + $excento;
                $subtotalTotal  = $subtotalTotal + $subtotal;
                $totalNota      = $totalNota + ($subtotal + $iva);


                }
                 
            }

            $datos = array(
                "action"=>"jdevoluciones",
                "id"=>validate::getInt('id'),
                "fecha"=>$fecha,
                "productos"=>$producto,
                "subtotal"=>$subtotalTotal,
                "totImponible"=>$totalImponible,
                "impuesto"=>$totalIva,
                "totExcento"=>$totalExcento,
                "montoTotal"=>$totalNota,
                "idAlmacen"=>validate::getPostParam('almacen'),
                "comentarios"=>validate::getPostParam('comentario'),
                "id_usuario"=>$this->_usuario
            );

            //echo json_encode($datos);
            //exit();
            if($this->_despacho->actualizarDespacho(json_encode($datos,true)))
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

        $this->_view->configEmp = $this->_configEmp;
        $this->_view->configCal = $this->_configCalDsp;
        $this->_view->configPvp = $this->_configUpdPvp;

        if($id>0)
        {
            $transaccion = $this->_despacho->cargarDespachos('{"action":"search","campo1":"id","v1":"'.$id.'"}');
            
            $credito = $this->_credito->cargarCreditos('{"action":"search","campo1":"id","v1":"'.$id.'"}');

            $this->_view->datos = $transaccion;
            $this->_view->productos = json_decode($transaccion[0]['productos'],true);

            

            $this->_view->almacen = $this->_deposito->cargarDeposito('{"action":"search all"}');  

        }



        $this->_view->title = "Devolución de Insumos";
        $this->_view->setJs(array('despacho'));
        $this->_view->renderizar('devolucion','creditos','Despachos');
        exit();
    }



    public function anular($id = false )
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
        if($id)
        {
            $transaccion = $this->_despacho->cargarDespachos('{"action":"search","campo1":"id","v1":"'.$id.'"}');
            
            $this->_view->datos = $transaccion;   
        }
        $this->_view->setJs(array('despacho'));
        $this->_view->renderizar('anular','creditos','Despachos');
        exit();
    }


    //---------------------------------------------------------------------------------------------------------------------
    //entrega de fondos
    //--------------------------------------------------------------------------------------------------------------------
    public function fondos()
    {
        if(validate::getInt('guardar')==1)
        {
            $datos = [
                "action"=>"jinsertFondos",
                "productor"=>validate::getPostParam('productor'),
                "credito"=>validate::getInt('credito'),
                "fecha"=>validate::getPostParam('fecha'),
                "forma"=>validate::getPostParam('forma'),
                "monto"=>validate::getPostParam('monto'),
                "comision"=>validate::getPostParam('comision'),
                "tasa"=>validate::getPostParam('tasa'),
                "total"=>validate::getPostParam('total'),
                "comentario"=>validate::getPostParam('comentario'),
                "id_usuario"=>$this->_usuario
            ];

            //print_r($datos);
           // exit();
            if($this->_despacho->grabarDespacho(json_encode($datos,true)))
            {
                $msj = $this->_producto->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_despacho->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        $rateExchange = new rate_exchange();
        $value = $rateExchange->getRate("dolar");
        $value=str_replace(",",".",$value);
        $value = round($value,2);
        $this->_view->tasaBcv =  $value;

        $this->_view->configEmp = $this->_configEmp;
        $this->_view->configCal = $this->_configCalDsp;
        $this->_view->productor = $this->_productor->cargarProductor('{"action":"search all"}');
        $this->_view->setJs(array('fondos'));
        $this->_view->title = "Entrega de Fondos";
        $this->_view->renderizar('fondos','creditos','Despachos');
        exit();

    }



    //-----------------------------------------------------------------------------------------
    //
    //-----------------------------------------------------------------------------------------
    public function cargarNotaImp()
    {
        echo json_encode($this->_despacho->cargarNotaImp(validate::getPostParam('value')),JSON_INVALID_UTF8_IGNORE);
    }

    public function cargarCredito()
    {
        $parameter = '{"action":"search","campo1":"idFiscalProductor","v1":"'.validate::getPostParam('value').'","campo2":"estado","v2":"APROBADO"}';
        echo json_encode($this->_credito->cargarCreditos($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarCredito()
    {
        $parameter = '{"action":"search","campo1":"id","v1":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_credito->cargarCreditos($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarProductor()
    {
        $parameter = '{"action":"search","campo":"idFiscal","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_productor->cargarProductor($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarProducto()
    {
        $parameter = '{"action":"search productos-exi","campo":"descripcion","valor":"'.strtoupper(validate::getPostParam('value')).'","planTrabajo":"'.validate::getPostParam('pt').'"}';
        echo json_encode($this->_producto->cargarProductos($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function cargarProducto()
    {
        $parameter = '{"action":"search","campo":"id","valor":"'.strtoupper(validate::getPostParam('value')).'","planTrabajo":"'.validate::getPostParam('pt').'","almacen":"'.validate::getPostParam('almacen').'"}';
        echo json_encode($this->_producto->cargarProductos($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function cargarProductoCodigo()
    {
        $parameter = '{"action":"search productos-exi","campo":"codigo","valor":"'.strtoupper(validate::getPostParam('value')).'"}';
        echo json_encode($this->_producto->cargarProductos($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarDeposito()
    {
        $parameter = '{"action":"search a","campo":"idFiscal","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_deposito->cargarDeposito(),JSON_INVALID_UTF8_IGNORE);
    }
    



}

