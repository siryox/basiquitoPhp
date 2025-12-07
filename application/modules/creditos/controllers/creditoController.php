<?php
class creditoController extends creditosController
{
    private $_credito;
    private $_programa;
    private $_finca;
    private $_productor;
    private $_tecnico;
    private $_configCalDsp;
    private $_recepcion;
    private $_mensajes; 

    private $_usuario;

    public function __construct()
    {
        parent::__construct();

        $this->_credito = $this->loadModel('credito');
        $this->_programa = $this->loadModel('programa','financiamiento');
        $this->_finca = $this->loadModel('finca','productores');
        $this->_productor = $this->loadModel('productor','productores');
        $this->_tecnico = $this->loadModel('tecnico','configuracion');
        $this->_recepcion = $this->loadModel('recepcion');
        $this->_mensajes = $this->loadModel('mensaje','mensajeria');

        $despacho = $this->loadModel('despacho');

        $this->_usuario = session::get('id_usuario');

        $cal = $despacho->cargarConfiguracioncalculo();
        $this->_configCalDsp = json_decode($cal[0]['config'],true);
    }


    public function index()
    {
        $this->_view->title = "Créditos";
        $this->_view->setJs(array('credito'));
        //$this->_view->setJsPlugin(array('validaciones'));
        //$this->getLibrary('paginador');
        //$paginador = new Paginador();

        $this->_view->lista = $this->_credito->cargarCreditos();
        
        $this->_view->renderizar('index','creditos','Registro de Crédito');
        exit();



    }

    //------------------------------------------------------------------------------------
    //Metodos para egregar creditos
    //------------------------------------------------------------------------------------
    public function agregar()
    {
        if(validate::getInt('guardar')==1)
        {
            $fechas = array(
                "solicitud"=>validate::getPostParam('fechaSolicitud'),
                "aprobacion"=>"",
                "liquidacion"=>""

            );
            
            
            $superficies = array(
                "solicitada"=>validate::getPostParam('superfSolicitada'),
                "aprobada"=>"0.0",
                "sembrada"=>"0.0",
                "cosechada"=>"0.0"
            );

            $otrosDatos = array(
                "fechaEstCosecha"=>validate::getPostParam('fechaCosecha'),
                "produccionEstimada"=>validate::getPostParam('prodEstimada'),
                "superfEfectiva"=>validate::getPostParam('haEfectivas'),
                "cantVisitas"=>validate::getPostParam('cantVisitas'),
                "faseActual"=>"",
                "observUltVisita"=>"",
                "fechaUltVisita"=>"",
                "planTrabajo"=>validate::getPostParam('planTrabajo')
            );

            $credito = array(
                "idFiscalProductor"=>validate::getPostParam('idFiscal'),
                "idFinca"=>validate::getInt('finca'),
                "idProgFinanc"=>validate::getInt('plan'),
                "comentarios"=>strtoupper(validate::getPostParam('observacion')),
                "estado"=>"POR EVALUACION",
                "fechas"=>$fechas,
                "superficies"=>$superficies,
                "otrosDatos"=>$otrosDatos,
                "idTecnico"=>"0",
                "action"=>"jinsert",
                "usuario"=>$this->_usuario
            );

            $datos = json_encode($credito,true);
            //print_r($datos);exit();
            if($this->_credito->grabarCredito($datos))
            {
                $msj = $this->_credito->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
            }else
                {
                    $msj = $this->_credito->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        $this->_view->setJs(array('credito'));
        $parameter = '{"action":"search","campo":"estado","valor":"ACTIVO"}';
        $this->_view->programa = $this->_programa->cargarPrgfinanc($parameter);

       

        $this->_view->finca = $this->_finca->cargarFincas();
        $this->_view->vciclo = $this->_programa->cargarCiclos();
        $this->_view->vmoneda = $this->_programa->cargarMonedas();

        $this->_view->planTrabajo = $this->_credito->cargarPlanTrabajo(); 


       
        $this->_view->title = "Registro Créditos";
        $this->_view->renderizar('agregar','creditos','Registro de Crédito');
        exit();
    }

    //----------------------------------------------------------------------------
    //Metodo que permite seditar los creditos
    //----------------------------------------------------------------------------
    public function editar($id = false)
    {

        if(validate::getInt('guardar')==2)
        {

            $id = validate::getInt('id');
            $parameter = '{"action":"search","campo1":"id","v1":"'.$id.'"}';
            $credito = $this->_credito->cargarCreditos($parameter);
            $otrosDatos = json_decode($credito[0]['otrosDatos'],true);
            


            $fechas = array(
                "solicitud"=>validate::getPostParam('fechaSolicitud'),
                "aprobacion"=>"",
                "liquidacion"=>""

            );
            
            
            $superficies = array(
                "solicitada"=>validate::getPostParam('superfSolicitada'),
                "aprobada"=>validate::getPostParam('superfAprobada'),
                "sembrada"=>(!empty(validate::getPostParam('haSembrada')))?validate::getPostParam('haSembrada'):0,
                "cosechada"=>(!empty(validate::getPostParam('superfCosechada')))?validate::getPostParam('superfCosechada'):0
            );

            $otrosDatos = array(
                "fechaEstCosecha"=>validate::getPostParam('fechaCosecha'),
                "produccionEstimada"=>validate::getPostParam('prodEstimada'),
                "superfEfectiva"=>(!empty(validate::getPostParam('haEfectivas')))?validate::getPostParam('haEfectivas'):0,
                "cantVisitas"=>(!empty(validate::getPostParam('cantVisitas')))?validate::getPostParam('cantVisitas'):0,
                "planTrabajo"=>validate::getPostParam('planTrabajo')
            );

/*
            $otrosDatos['fechaEstCosecha'] = validate::getPostParam('fechaCosecha');
            $otrosDatos['produccionEstimada'] = validate::getPostParam('prodEstimada');
            $otrosDatos['superfEfectiva'] = validate::getPostParam('haEfectivas');
            $otrosDatos['cantVisitas'] = validate::getPostParam('cantVisitas');
            $otrosDatos['planTrabajo'] = validate::getPostParam('planTrabajo');
*/
            $credito = array(
                "id"=>validate::getInt('id'),
                "idFiscalProductor"=>validate::getPostParam('idFiscal'),
                "idFinca"=>validate::getInt('finca'),
                "idProgFinanc"=>validate::getInt('plan'),
                "comentarios"=>strtoupper(validate::getPostParam('observacion')),
                "estado"=>"APROBADO",
                "fechas"=>$fechas,
                "superficies"=>$superficies,
                "otrosDatos"=>$otrosDatos,
                "idTecnico"=>validate::getPostParam('tecnico'),
                "action"=>"jupdate",
                "usuario"=>$this->_usuario
            );

            $datos = json_encode($credito,true);
           // print_r($_POST);
           // print_r($datos);exit();
            if($this->_credito->grabarCredito($datos))
            {
                $msj = $this->_credito->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
            }else
                {
                    $msj = $this->_credito->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        if($id)
        {
            $parameter = '{"action":"search","campo1":"id","v1":"'.$id.'"}';
            $credito = $this->_credito->cargarCreditos($parameter);
            $this->_view->credito = $credito;
            $pf = $credito[0]['idProgFinanc'];
            $parameter = '{"action":"search","campo":"id","valor":"'.$pf.'"}';
            $this->_view->progFi = $this->_programa->cargarPrgfinanc($parameter);

        }

        $this->_view->planTrabajo = $this->_credito->cargarPlanTrabajo(); 

        $this->_view->setJs(array('credito'));
        $this->_view->programa = $this->_programa->cargarPrgfinanc();
        $this->_view->finca = $this->_finca->cargarFincas();
       
        $this->_view->title = "Registro Créditos";
        $this->_view->renderizar('editar','creditos','Registro de Crédito');
        exit();


    }
    //------------------------------------------------------------------------------
    //Metodo para eliminar un credito
    //------------------------------------------------------------------------------
    public function eliminar($id=false)
    {
        if(validate::getInt('eliminar')==1)
        {

            $datos = array(
                   "id"=> validate::getInt('id'), 
                   "id_usuario"=>$this->_usuario,
                   "action"=>"jdelete"
            );
        
            if($this->_credito->eliminarCredito(json_encode($datos,true)))
            {
                $this->redireccionar('creditos/credito/index/');
                exit();
            }else
                {
                    $msj = $this->_credito->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
        
        }
        if($id)
        {
            $parameter = '{"action":"search","campo1":"id","v1":"'.$id.'"}';
            $datos = $this->_credito->cargarCreditos($parameter); 
            if(count($datos))
            {
                
                $this->_view->datos = $datos;
            }

            $this->_view->title = "Crédito";
            $this->_view->setJs(array('credito'));
            $this->_view->renderizar('eliminar','creditos','Registro de Crédito');
            exit();

        }
    }

    //---------------------------------------------------------------------------
    //Metodo para Aprobar un credito
    //---------------------------------------------------------------------------
    public function aprobacion($id = false)
    {
        if(validate::getInt('guardar')==3)
        {
            //print_r($_POST);
            //exit();

            $preparacion = array(
                "dias"=> validate::getPostParam('preparacion_dias_real'),
                "visitas"=>validate::getPostParam('preparacion_visitas_real'),
                "Mto-combustible"=>validate::getPostParam('preparacion_Mto-combustible_real'),
                "Lt-combustible"=>validate::getPostParam('preparacion_Lt-combustible_real'),
                "Mto-otros"=>validate::getPostParam('preparacion_Mto-otros_real') 
            );

            $siembra = array(
                "visitas"=>validate::getInt('siembra_visitas_real'),
                "dias"=> validate::getInt('siembra_dias_real'),
                "Kg-semillas"=> validate::getPostParam('siembra_Kg-semillas_real'),
                "Mto-semillas"=> validate::getPostParam('siembra_Mto-semillas_real'),
                "Lt-combustible"=> validate::getPostParam('siembra_Lt-combustible_real'),
                "Mto-combustible"=> validate::getPostParam('siembra_Mto-combustible_real'),
                "Mto-otros"=> validate::getPostParam('siembra_Mto-otros_real')
            );

            $mantenimiento = array(
                "dias"=> validate::getInt('mantenimiento_dias_real'),
                "visitas"=> validate::getInt('mantenimiento_visitas_real'),
                "Lt-combustible"=> validate::getInt('mantenimiento_Lt-combustible_real'),
                "Mto-combustible"=> validate::getPostParam('mantenimiento_Mto-combustible_real'),
                "Kg-fertilizante"=> validate::getPostParam('mantenimiento_Kg-fertilizante_real'),
                "Mto-fertilizante"=> validate::getPostParam('mantenimiento_Mto-fertilizante_real'),
                "Mto-agroquimicos"=> validate::getPostParam('mantenimiento_Mto-agroquimicos_real'),
                "Mto-labores"=> validate::getPostParam('mantenimiento_Mto-labores_real'),
                "Mto-otros"=> validate::getPostParam('mantenimiento_Mto-otros_real')
            );

            $cosecha = array(
                "visitas"=>validate::getInt('cosecha_visitas_real'),
                "dias"=>validate::getInt('cosecha_dias_real'),
                "Lt-combustible"=>validate::getPostParam('cosecha_Lt-combustible_real'),
                "Mto-combustible"=> validate::getPostParam('cosecha_Mto-combustible_real'),
                "Mto-transporte"=> validate::getPostParam('cosecha_Mto-transporte_real'),
                "Mto-otros"=> validate::getPostParam('cosecha_Mto-otros_real')
            );

            $fechas = array(
                "solicitud"=>validate::getPostParam('fechaSolicitud'),
                "aprobacion"=>validate::getPostParam('fechaAprobacion'),
                "liquidacion"=>""
            );
            
            $superficies = array(
                "solicitada"=>validate::getPostParam('superficieSolicitada'),
                "aprobada"=>validate::getPostParam('superficieAprobada'),
                "sembrada"=>"0",
                "cosechada"=>"0"
            );
            $datos = array(
                "action"=>"japrobar",
                 "usuario"=>$this->_usuario,
                "id"=>validate::getInt('idCredito'),
                "idTecnico"=>validate::getPostParam('tecnico'),
                "fechas"=>$fechas,
                 "detalles"=>[
                    "preparacion"=>$preparacion,
                    "siembra"=>$siembra,
                    "mantenimiento"=>$mantenimiento,
                    "cosecha"=>$cosecha
                 ],
                 "superficies"=>$superficies,
                 "comentario"=>validate::getPostParam('comentario'),
                 "estado"=>validate::getPostParam('estado')
                 

            );


            if($this->_credito->aprobarCredito(json_encode($datos,true)))
            {
                $msj = $this->_credito->getResult();
                
                //BUSCO CONFIGURACION DE WHATSAPP
             /*  
                $cnfWh = $this->_mensajes->cargarConfigWhatsapp();
                if($cnfWh[0]['config'] == '1')
                {
                    
                    //BUSCO DATOS DEL CREDITO
                    $idCredito = validate::getInt('idCredito');
                    $parameter = '{"action":"search","campo1":"id","v1":"'.$idCredito.'"}';
                    $datosCredito = $this->_credito->cargarCreditos($parameter);
                    
                    
                    //BUSCO DATOS DEL PRODUCTOR
                    $parameter = '{"action":"search","campo":"idFiscal","valor":"'.$datosCredito[0]['idFiscalProductor'].'"}';
                    $datosProductor = $this->_productor->cargarProductor($parameter);
                    
                    
                    $mensaje = array(
                        "fecha" =>date('Y-m-d'),
                        "hora"  =>date('H:i:s'),
                        "origen"=>["idUsuario"=>"1","nombreUsuario"=>"System"],
                        "destino"=>["idContacto"=>$datosProductor[0]['id'],"nombreContacto"=>$datosProductor[0]['razonSocial'],"telefonoContacto"=>$datosProductor[0]['tlfPersonal1']],
                        "plantilla"=>"noti_aprob_credito_meta",
                        "status" =>'xEnv',
                        "canal"=>'whatsapp',
                        "tipo"=>'SALIDA', 
                        "otrosDatos"=>["idCredito"=>$idCredito],
                        "action"=>"jinsert",
                        "usuario"=>$this->_usuario
                    );

                    $this->_mensajes->grabarMensaje(json_encode($mensaje,true));

                }
             */   
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_credito->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        //$datos = $this->_tecnico->cargarTecnicos();
        //print_r($datos); exit();
        $this->_view->tecnico = $this->_tecnico->cargarTecnicos();
       
        $this->_view->programa = $this->_programa->cargarPrgfinanc();


        
        $this->_view->setJs(array('aprobacion'));
        $this->_view->title = "Aprobación de Créditos";
        $this->_view->renderizar('aprobacion','creditos','Aprobación');
        exit();

    }
    public function cuenta($id)
    {
        
        if($id)
        {
            ///cargo datos del credito
            $parameter = '{"action":"search","campo1":"id","v1":"'.$id.'"}';

            $credito = $this->_credito->cargarCreditos($parameter);    
            $cupos = json_decode($credito[0]['cuposJson'],true);
            //print_r($cupos);

            $this->_view->credito = $credito;
            $this->_view->cupos = $cupos;


            ////cargo datos de la cuenta por cobrar a productor
            $cuenta = $this->_credito->cargarCuenta($id);
            $saldo = 0;


            // cargar intereses 
            $intereses = 0;
            
            $intereses = ($credito[0]['estado']!='LIQUIDADO')?$this->_credito->cargarIntereses($id):0;
            if(is_array($intereses))
            {
                $interesCredito = $intereses[0]['intereses'];
            }else
            {
                $interesCredito = 0;
            }   
            
            $totales = array("despachos"=>0,"recepciones"=>0);

            for($i = 0;$i < count($cuenta);$i++)
            {
                $saldo = ($cuenta[$i]['debito'] - $cuenta[$i]['credito']) + $saldo;
                $cuenta[$i]['saldo'] = $saldo;
                switch($cuenta[$i]['TipoD'])
                {
                    case 'NE': //nota de entrega insumos
                        $totales['despachos'] = $totales['despachos']+1;
                        break;
                    case 'ANU-NEI':
                        $totales['despachos'] = $totales['despachos']-1;    
                        break;
                    case 'RECEPCION':
                        $totales['recepciones'] = $totales['recepciones']+1;    
                        break;
                    case 'ANU-RCO':
                        $totales['recepciones'] = $totales['recepciones']-1;    
                        break;
                    case 'NEF':
                        $totales['despachos'] = $totales['despachos']-1;    
                        break;
                }

            }

            //------------------------------------------------------------------
            // cargo recepciones de cosecha del credito
            $recepcion = $this->_recepcion->cargarRecepcion('{"action":"search","campo":"credito","valor":"'.$id.'"}');
            $totRecepCos = 0;
            if(count($recepcion)>0)
            {
                $totRecepCos = count($recepcion);
            }

            //-------------------------------------------------------------------
            //cargo extenciones de creditos
            $totExtCred = 0;
            $parameter = '{"action":"search-ext","idCredito":"'.$id.'"}';
            $extencion_cred = $this->_credito->cargarCreditos($parameter);
            if(count($extencion_cred)>0)
            {
                $totExtCred = count($extencion_cred);
            }
            //-------------------------------------------------------------------
            $this->_view->lista = $cuenta;
            $this->_view->totales = $totales;
            $this->_view->totExtCred = $totExtCred;
            $this->_view->totRecepCos = $totRecepCos;

            $this->_view->intereses = $interesCredito;

            ////cargo datos de la finca
            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';
            $this->_view->finca = $this->_finca->cargarFincas($parameters);


            
        }
        $this->_view->tecnico = $this->_tecnico->cargarTecnicos();
        $this->_view->configCal = $this->_configCalDsp;

        $mot = $this->_credito->cargarMotivoExtCred();
        
        $this->_view->motExt = json_decode($mot[0]['motivo'],true);
        
        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));

        
        $this->_view->setJs(array('credito'));
        $this->_view->title = "Estatus del Crédito";
        $this->_view->renderizar('cuenta','creditos','Registro de Crédito');
        exit();
    }
    //--------------------------------------------------------------------------------
    //metodo que guarda una cuenta por cobrar 
    //--------------------------------------------------------------------------------
    public function cxc()
    {
        if(validate::getInt('guardar_cxc'))
        {
            $partida = (validate::getInt('aplicar_cupo')==1)?"1":"2";
            $datos = [
                "action"=>"jinsertcxc",
                "idCredito"=>validate::getInt('credito_id'),
                "docOrigen"=>"NA",
                "referencia"=>"0",
                "fecha"=>validate::getPostParam('fechacxc'),
                "tipo"=>validate::getPostParam('tipocxc'),
                "concepto"=>validate::getPostParam('conceptocxc'),
                "monto"=>validate::getPostParam('montocxc'),
                "partida"=>$partida,
                "id_usuario"=>$this->_usuario
            ];

          //  print_r($datos);
          //  exit();
            if($this->_credito->grabarDocumento(json_encode($datos,true)))
            {
                //$msj = $this->_productor->getResult();
                $this->redireccionar('creditos/credito/cuenta/'.validate::getInt('credito_id'));
                exit();
            }else
                {
                    $msj = $this->_productor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

    }

    //--------------------------------------------------------------------------------
    //metodo que guarda una solicitud de extencion de credito
    //--------------------------------------------------------------------------------
    public function adicional()
    {
        if(validate::getInt('guardar_adicional'))
        {

            
            $file = $_FILES['imagen'];
            $nombreInforme = 'inf_'.validate::getInt('credito_id').'_'.date('YmdHis');

            $datos = [
                "action"=>"jinsert-ext",
                "idCredito"=>validate::getInt('credito_id'),
                "fechaInforme"=>validate::getPostParam('fechaext'),
                "idTecnico"=>validate::getPostParam('tecnico'),
                "comentario"=>validate::getPostParam('informe'),
                "imagenInforme"=>$nombreInforme,
                "montoSolicitado"=>validate::getPostParam('montoext'),
                "motivoSolicitud"=>validate::getPostParam('motivo'),
                "hectareasAdicionales"=>validate::getPostParam('hectareasAdicionales'),
                "id_usuario"=>$this->_usuario
            ];

           // print_r($datos);
           // exit();
            if($this->_credito->grabarDocumento(json_encode($datos,true)))
            {
                if($file){
                    $this->getLibrary('uploadFile');
                    $upload = new uploadFile();
                    $upload->setFile($file);
                    $upload->setDirUpload(APP_PATH.'public'.DS.'img'.DS.'informes'.DS);
                    $upload->setRename($nombreInforme);
                    $upload->uploadFile();
                }
                //$msj = $this->_productor->getResult();
                $this->redireccionar('creditos/credito/cuenta/'.validate::getInt('credito_id'));
                exit();
            }else
                {
                    $msj = $this->_credito>getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

    }


    public function cargarCxcImp()
    {
        echo json_encode($this->_credito->cargarCxcImp(validate::getPostParam('value'),$this->_usuario),JSON_INVALID_UTF8_IGNORE);
    }

    public function cargarDespImp()
    {
        echo json_encode($this->_credito->cargarDespImp(validate::getPostParam('value'),$this->_usuario),JSON_INVALID_UTF8_IGNORE);
    }

    //---------------------------------------------------------------------
    //Carga reporte  recepciones de cosecha 
    public function cargarRecepImp()
    {
        $usu = session::get('alias');
        echo json_encode( $this->_credito->cargarRecepImp(validate::getPostParam('value'),$usu),JSON_INVALID_UTF8_IGNORE);
    }
    //---------------------------------------------------------------------


    public function buscarCreditoPorAprobar()
    {
        $parameter = '{"action":"search","campo1":"idProgFinanc","v1":"'.validate::getPostParam('value').'","campo2":"estado","v2":"POR EVALUACION"}';
        echo json_encode($this->_credito->cargarCreditos($parameter),true);
    }
    public function buscarCredito()
    {
        $parameter = '{"action":"search","campo1":"id","v1":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_credito->cargarCreditos($parameter),JSON_INVALID_UTF8_IGNORE);
    }

    public function buscarPrograma()
    {
        $parameter = '{"action":"search","campo":"id","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_programa->cargarPrgfinanc($parameter),true);
    }

    public function buscarProductor()
    {
        $parameter = '{"action":"search","campo":"idFiscal","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_productor->cargarProductor($parameter),true);
    }
    public function buscarProductorNombre()
    {
        $parameter = '{"action":"search","campo":"razon social","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_productor->cargarProductor($parameter),true);
    }
}


