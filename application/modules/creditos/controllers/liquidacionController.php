<?php
class liquidacionController extends creditosController
{
    private $_productor;
    private $_programa;
    private $_liquidacion;
    private $_usuario;
    private $_credito;
    private $_configEmp;
    public function __construct()
    {
        parent::__construct();

        $this->_liquidacion = $this->loadModel('liquidacion');
        $this->_usuario = session::get('id_usuario');




        $emp = $this->_liquidacion->cargarConfiguracionEmpresa();


        $this->_configEmp = json_decode($emp[0]['config'],true);

    }


    public function index()
    {
        $liquidaciones = $this->_liquidacion->cargarLiquidaciones();


       // $pagoslig = $this->_liquidacion->cargarPagosLiq('{"action":"search","campo":"idLiquidacion","valor":"'.$liquidaciones[0]['id'].'"}'); 

        //print_r($pagoslig);


        //print_r($liquidaciones);
        $this->_view->setJs(['liquidacion']);
        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));

        $this->_view->lista = $liquidaciones;
        $this->_view->title = "Liquidaciones de Creditos";
        $this->_view->renderizar('index','creditos','Liquidacion de Crédito');
        exit();

    }


    public function agregar()
    {
        if(validate::getInt('guardar'))
        {

            $correlativo = validate::getPostParam('correlativo');
            $fecha = validate::getPostParam('fecha');
            $transporte = validate::getPostParam('transporte');
            $pneto = validate::getPostParam('pesoNeto');
            $pacondicionado = validate::getPostParam('pesoAcondicionado');
            $nroBoleta = validate::getPostParam('nroBoleta');
            $haSembradas = (!empty(validate::getPostParam('haSembradas')))?validate::getPostParam('haSembradas'):'0';
            //for($i=0;$i<count($correlativo);$i++)
           // {
           //     $boletas[] = ["nroBoleta"=>$nroBoleta[$i],"fecha"=>$fecha[$i],"transporte"=>$transporte[$i],"pesoNeto"=>$pneto[$i],"pesoAcondicionado"=>$pacondicionado[$i],"idRecepcion"=>$correlativo[$i]];
                  $boletas = json_decode(validate::getPostParam('boletas'),true);     
           // }


            $productor = validate::getPostParam('productor');
            $idCredito= validate::getPostParam('credito');
            $precioLiquidacion = validate::getPostParam('precioLiquidacion');
            
            /// sacamos las cxc
            $calculo = validate::getPostParam('documentos');

            $calculo = json_decode($calculo,true);

            //print_r($calculo);exit();
            $ncxc = '';
            foreach($calculo as $cal)
            {
                if(strlen($ncxc)>0)
                {
                    $ncxc = $ncxc .','. $cal['docId'];
                }else
                    $ncxc =  $cal['docId'];
                    
            }
            $ncxc = '['. $ncxc .']';


        /*    
            $detalle = [
                "haAprobadas"=>validate::getPostParam('haAprobadas'),
                "haSembradas"=>$haSembradas,
                "haCosechadas"=>validate::getPostParam('haCosechadas'),
                "kgCosechados"=>validate::getPostParam('kgCosechados'),
                "kgDescuento"=>validate::getPostParam('kgDescuento'),
                "kgALiquidar"=>validate::getPostParam('kgLiquidar'),
                "montoFinanciado"=>validate::getPostParam('montoFinanciado'),
                "totalCosecha"=>validate::getPostParam('totalCosecha'),
                "totalALiquidar"=>validate::getPostParam('totalLiquidar'),
                "precioLiquidacion"=>$precioLiquidacion,
                "tasaInteres"=>validate::getPostParam('tasaInteres'),
                "montoIntereses"=>validate::getPostParam('montoInteres')
                
            ];
            
            $datos = [
                "action"=>"jinsert",
                "idCredito"=>$idCredito,
                "fecha"=>validate::getPostParam('fechaLiquidacion'),
                "detalles"=>$detalle,
                "estado"=>'ACTIVO',
                "id_usuario"=>$this->_usuario,
                "boletas"=>$boletas,
                "comentarios"=>validate::getPostParam('comentario'),
                "productor"=>$productor,
                "calculo"=>$calculo
                ];
        */    
            
        $datos = [
            "action"=>"insert",
            "parametro"=>["idCredito"=>$idCredito,
                          "fecha"=>validate::getPostParam('fechaLiquidacion'),
                          "id_usuario"=>$this->_usuario,
                          "comentarios"=>validate::getPostParam('comentario'),
                          "id_usuario"=>$this->_usuario,
                          "cxc"=>$ncxc
                        ]
            
            ];
        


            $datos = json_encode($datos,true);    
            $datos = str_replace('"[','[',$datos);
            $datos = str_replace(']"',']',$datos);
            //print_r($datos); exit();

            if($this->_liquidacion->grabarLiquidacion($datos))
            {
                $msj = $this->_liquidacion->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();    

            }else
                {
                    $msj = $this->_liquidacion->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
            



        }

        //-------------------------------------------------------------
        $this->_productor = $this->loadModel('productor','productores');
        $productor= $this->_productor->cargarProductor();
        $this->_view->productor = $productor;
        //-------------------------------------------------------------
        


        
        $this->_view->setJs(['liquidacion']);
        $this->_view->title = "Liquidaciones de Créditos";
        $this->_view->renderizar('agregar','creditos','Liquidacion de Crédito');
        exit();


    }


    public function editar($id = false)
    {
        
        if(validate::getInt('guardar')==2)
        {



        }

        if($id)
        {

            $parameter = '{"action":"search","campo":"id","valor":"'.$id.'"}';
            $datos = $this->_liquidacion->cargarLiquidaciones($parameter);
           // print_r($datos);
            $this->_view->datos = $datos;

        }
        //-------------------------------------------------------------
        $this->_productor = $this->loadModel('productor','productores');
        $productor= $this->_productor->cargarProductor();
        $this->_view->productor = $productor;
        //-------------------------------------------------------------
        $credito = $this->loadModel('credito','creditos');
        $parameter = '{"action":"search","campo1":"id","v1":"'.$datos[0]['idCredito'].'"}';
        $this->_view->credito = $credito->cargarCreditos($parameter);



        $this->_view->title = "Liquidaciones de Creditos";
        $this->_view->renderizar('editar','creditos','Liquidacion de Crédito');
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

            if($this->_liquidacion->anularLiquidacion(json_encode($datos,true)))
            {
                $msj = $this->_liquidacion->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_liquidacion->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        if($id)
        {
            $liquidacion = $this->_liquidacion->cargarliquidaciones('{"action":"search","campo":"id","valor":"'.$id.'"}');
            $this->_view->datos = $liquidacion;   
        }

        $this->_view->setJs(array('liquidacion'));
        $this->_view->renderizar('anular','creditos','Liquidacion de Crédito');
        exit();
    }

    
    public function pagar($id=false)
    {

        if(validate::getInt('guardar')==1)
        {

            $datos = [
                "productor"=>validate::getPostParam("productor"),
                "fecha"=>validate::getPostParam("fecha"),
                "origen_fondos"=>(!empty(validate::getPostParam("origen_fondos")))?validate::getPostParam("origen_fondos"):"",
                "forma"=>validate::getPostParam("forma"),
                "destino_fondos"=>(!empty(validate::getPostParam("destino_fondos")))?validate::getPostParam("destino_fondos"):"",
                "cuenta"=>(!empty(validate::getPostParam("cuenta")))?validate::getPostParam("cuenta"):"",
                "referencia"=>(!empty(validate::getPostParam("referencia")))?validate::getPostParam("referencia"):"",
                "monto_pago"=>validate::getPostParam("monto_pago"),
                "comentario"=>validate::getPostParam("comentario"),
                "liquidacion"=>validate::getPostParam("idLiquidacion"),
                "action"=>"pagar-liquidacion",
                "tipo"=>"PAGO-PRODUCTOR",
                "idCredito"=>validate::getPostParam("idCredito"),
                "id_usuario"=>$this->_usuario
            ];

            if($this->_liquidacion->grabarLiquidacion(json_encode($datos,true)))
            {
                $msj = $this->_liquidacion->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_liquidacion->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        if($id)
        {
            $liquidacion = $this->_liquidacion->cargarliquidaciones('{"action":"search","campo":"id","valor":"'.$id.'"}');
            // print_r($liquidacion);
            $totalLiquidar =0;
            $totalLiquidar = abs($liquidacion[0]['saldoFinal']) - $liquidacion[0]['totalPagado'];
            $this->_view->datos = $liquidacion;
            $this->_view->totalLiquidar = $totalLiquidar;

            $this->_productor = $this->loadModel('productor','productores');
            $this->_view->productor = $this->_productor->cargarProductor('{"action":"search","campo":"idFiscal","valor":"'.$liquidacion[0]['idFiscalProductor'].'"}');
        
            $this->_view->bancos = $this->_productor->cargarBancos();
        }    


        $this->_view->configEmp = $this->_configEmp;

        $this->_view->setJs(array('liquidacion'));
        $this->_view->title = "Pago de Liquidación";
        $this->_view->renderizar('pago','creditos','Liquidacion de Crédito');
        exit(); 


    }


    public function cargarCredito()
    {
        $credito = $this->loadModel('credito','creditos');
        $parameter = '{"action":"search","campo1":"id","v1":"'.validate::getPostParam('value').'"}';
        echo json_encode($credito->cargarCreditos($parameter),JSON_INVALID_UTF8_IGNORE);
    }
    //BUSCA LOS CREDITOS CON ESTADO APROBADO DE UN PRODUCTOR
    public function buscarCredito()
    {
        $credito = $this->loadModel('credito','creditos');

        $parameter = '{"action":"search","campo1":"idFiscalProductor","v1":"'.validate::getPostParam('value').'","campo2":"estado","v2":"APROBADO"}';
        echo json_encode($credito->cargarCreditos($parameter),JSON_INVALID_UTF8_IGNORE);
    }


    public function infoLiquidacion()
    {
        
        $parameter = '{"action":"calculos iniciales","parametro":'.validate::getPostParam('value').'}';
        echo json_encode($this->_liquidacion->cargarLiquidaciones($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function cargarRecepciones()
    {
        $recepcion = $this->loadModel('recepcion','creditos');

        $parameter = '{"action":"search","campo":"credito","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($recepcion->cargarRecepcion($parameter),JSON_INVALID_UTF8_IGNORE);

    }

    //-------------------------------------------------------------------
    //CARGA ESTADO DE CUENTA
    //-------------------------------------------------------------------
    public function cargarCuenta()
    {
        //$this->_credito = $this->loadModel('credito','creditos');

        $cuenta = $this->_liquidacion->cuentaLiquidaciones(validate::getPostParam('value'));

        echo json_encode($cuenta);
    }
    //--------------------------------------------------------------------
    //CARGAR NOTA LIQUIDACION
    //-------------------------------------------------------------------
    public function cargarNotaLiq()
    {
        echo json_encode($this->_liquidacion->cargarNotaImp(validate::getPostParam('value')),JSON_INVALID_UTF8_IGNORE);
    }

}