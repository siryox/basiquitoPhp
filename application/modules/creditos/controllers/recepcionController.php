<?php
final class recepcionController extends creditosController
{
    private $_productor;
    private $_credito;
    private $_proveedor;
    private $_almacen;
    private $_usuario;
    private $_recepcion;
    private $_convenio;

    public function __construct()
    {
        parent::__construct();
        $this->_credito   = $this->loadModel('credito');
        $this->_productor = $this->loadModel('productor','productores');
        $this->_proveedor = $this->loadModel('proveedor','compras');
        $this->_recepcion = $this->loadModel('recepcion');
        $this->_almacen = $this->loadModel('deposito','almacen');
        $this->_convenio = $this->loadModel('convenio','financiamiento');

        $this->_usuario = session::get('id_usuario');
    }

    public function index()
    {

        if(validate::getPostParam('programa')>0)
        {
            $parameter = '{"action":"search","campo":"programa","valor":"'.validate::getPostParam('programa').'"}';    
        }else
        $parameter = '{"action":"search all"}';

        $this->_view->lista = $this->_recepcion->cargarRecepcion($parameter);

        $this->_view->productor = $this->_productor->cargarProductor();


        $programa = $this->loadModel('programa','financiamiento');

        
        $this->_view->programa = $programa->cargarPrgfinanc(); 
        $this->_view->convenio = $this->_convenio->cargarConvenio();

        $this->_view->setJs(["recepcion"]);
        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));

        $this->_view->title = "Recepción de Cosecha";
        $this->_view->renderizar('index','creditos','Recepción de Cosecha');
        exit();
    }



    public function agregar()
    {
        if(validate::getInt('guardar')==1)
        {
            //print_r($_POST);
            //exit();
            $idCredito = (!empty(validate::getPostParam('credito')))?validate::getPostParam('credito'):'0'; // Added semicolon


            $camion = array(
                "conductor"=>validate::getPostParam('conductor'),
                "idFiscalConductor"=>validate::getPostParam('idFiscalConductor'),
                "modeloCamion"=>validate::getPostParam('transporte'),
                "placaCamion"=>validate::getPostParam('placa')   
            );
            
            $pesos = array(
                "entrada"=>validate::getPostParam('pesoEntrada'),
                "salida"=>validate::getPostParam('pesoSalida'),
                "neto"=>validate::getPostParam('pesoNeto'),
                "acondicionado"=>str_replace(",",".",validate::getPostParam('acondicionado'))
            );

            $analisis = array(
                "humedad"=>validate::getPostParam('humedad'),
                "infestacion"=>validate::getPostParam('infestacion'),
                "impureza"=>validate::getPostParam('impureza'),
                "porc_impureza"=> validate::getPostParam('porcImp'),
                "porc_humedad"=> validate::getPostParam('porcHum')
            );

            $datos = array(
                "action"=>"jinsert",
                "fecha"=> validate::getPostParam('fechaRecepcion'),
                "idCredito"=>$idCredito,
                "idFiscalProd"=>validate::getPostParam('idFiscal'),
                "idAlmacenadora"=>validate::getPostParam('almacenadora'),
                "idPlantaRecepcion"=>validate::getPostParam('planta'),
                "referencia" => validate::getPostParam('nroVoleta'),
                "ticEntrada"=> validate::getPostParam('ticEntrada'),
                "rubro"=>validate::getPostParam('rubro'),
                "convenio"=>validate::getPostParam('convenio_recepcion'),
                "estado"=>"XAPROBAR",
                "comentarios"=>validate::getPostParam('comentario'),
                "camion"=>$camion,
                "pesos"=>$pesos,
                "analisis"=>$analisis,
                "id_usuario"=>$this->_usuario
            );




            if($this->_recepcion->grabarRecepcion(json_encode($datos,true)))
            {
                $msj = $this->_recepcion->getResult();
                if($idCredito>0)
                {
                    $parameter = '{"action":"search","campo1":"id","v1":"'.$idCredito.'"}';
                    $credito = $this->_credito->cargarCreditos($parameter);

                    $this->_recepcion->grabarRecepcion('{"action":"recalcularRecepciones","idProgFinanc":"'.$credito[0]['idProgFinanc'].'","idUsuario":"'.$this->_usuario.'"}');
                }
                
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();

            }else
                {
                    $msj = $this->_recepcion->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        $this->_estado = $this->loadModel('estado','configuracion');
        $this->_view->estado = $this->_estado->cargarEstado();


        $this->_view->productor = $this->_productor->cargarProductor();

        $this->_view->proveedor = $this->_proveedor->cargarProveedores();
        $this->_view->convenio = $this->_convenio->cargarConvenio();

        $this->_view->vrubro = $this->_recepcion->cargarRubros();


        $this->_view->setJs(["recepcion"]);
        $this->_view->title="Recepción de Cosecha";
        $this->_view->renderizar('agregar','creditos','Recepción de Cosecha');
        exit();

    }


    public function editar($id)
    {
        if(validate::getInt('guardar')==2)
        {
            



        }

        $parameter = '{"action":"search","campo":"id","valor":"'.$id.'"}'; 
        $rec = $this->_recepcion->cargarRecepcion($parameter);
       
        $this->_view->recepcion = $this->_recepcion->cargarRecepcion($parameter);


        $this->_view->productor = $this->_productor->cargarProductor();

        $this->_view->proveedor = $this->_proveedor->cargarProveedores();

        $this->_view->deposito = $this->_almacen->cargarDeposito();



        $this->_view->credito = $this->_credito->cargarCreditos();

        
        $this->_view->renderizar('editar','creditos','Recepcion de Cosecha');
        exit();
    }



    public function anular($id = false)
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

            if( $this->_recepcion->anularRecepcion(json_encode($datos,true)))
            {
                $msj =  $this->_recepcion->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_recepcion->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }
        if($id)
        {
            $parameter = '{"action":"search","campo":"id","valor":"'.$id.'"}'; 
            $this->_view->datos = $this->_recepcion->cargarRecepcion($parameter);
  
        }
        $this->_view->setJs(array('recepcion'));
        $this->_view->renderizar('anular','creditos','Recepcion de Cosecha');
        exit();


    }



    public function buscarProductor()
    {
        $parameter = '{"action":"search","campo":"idFiscal","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_productor->cargarProductor($parameter),true);
    }



    public function cargarCredito()
    {
        $parameter = '{"action":"search","campo1":"idFiscalProductor","v1":"'.validate::getPostParam('value').'","campo2":"estado","v2":"APROBADO"}';
        echo json_encode($this->_credito->cargarCreditos($parameter),JSON_INVALID_UTF8_IGNORE);
    }
    //METODO PARA CRGAR RECEPCIONES POR PROGRAM DE FINANCIAMIENTO
    public function cargarRecepciones()
    {
        $valor = validate::getPostParam('value');
        if($valor == 0)
            $parameter = '{"action":"search all"}';
        else
            $parameter = '{"action":"search","campo":"programa","valor":"'.$valor.'"}';


        $datos = $this->_recepcion->cargarRecepcion($parameter);
        
        echo json_encode($datos,true);


    }
    
    public function cargarAlmacen()
    {
        $this->_almacen = $this->loadModel('deposito','almacen');

        $parameter = '{"action":"search","campo":"proveedor","valor":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_almacen->cargarDeposito($parameter),JSON_INVALID_UTF8_IGNORE);
    }
    //METODO PARA CARGAR NOTA DE RECEPCION (FORMATO DE IMPRESION)
    public function cargarNotaRec()
    {
        $parameter = validate::getPostParam('value');
        echo json_encode($this->_recepcion->cargarNotaRec($parameter),JSON_INVALID_UTF8_IGNORE);        
    }


    public function guardarProductor()
    {

        if(validate::getInt('guardar')==1)
        {

            $direccion = array(
                "direccion" =>strtoupper(validate::getPostParam('direccion')),
                "sector"    =>strtoupper(validate::getPostParam('sector')),
                "calle"     =>validate::getPostParam('calle'),
                "av"        =>validate::getPostParam('av'),
                "tipo"      =>validate::getPostParam('tipo_vivienda'),
                "numero"       =>validate::getPostParam('nro'),
                "municipio" =>validate::getInt('municipio'),
                "estado"    =>validate::getInt('estado'),
                "codPostal"=>validate::getPostParam('codigo_postal'),
                "parroquia" =>validate::getInt('parroquia')
                 
            );

            $telefonos = array(
                "Personal1"=>validate::getPostParam('tlf_prod1'),
                "Personal2"=>validate::getPostParam('tlf_prod2'),
                "Oficina"=>validate::getPostParam('tlf_oficina')
            );
            $correos = array(
                "Personal1"=>validate::getPostParam('correo_prod1'),        
                "Personal2"=>validate::getPostParam('correo_prod2'),
                "Oficina"=>validate::getPostParam('correo_empresa')

            );
            $wathsApp = array(
                "Personal"=>validate::getPostParam('tlf_wsp'),
                "Oficina"=>validate::getPostParam('tlf_wso')
            );
            $medioscontacto = array(
                "telefonos"=>$telefonos,
                "correos"=>$correos,
                "wathsApp"=>$wathsApp

            );
            $datos = array(
                "tipoPersona"=>validate::getPostParam('tipo'),
                "idFiscal"=>validate::getPostParam('idFiscal'),
                "razonSocial"=>strtoupper(validate::getPostParam('razon_social')),
                "direccion"=>json_encode($direccion,true),  
                "mediosContacto"=>json_encode($medioscontacto,true),
                "estado"=>"ACTIVO",
                "action"=>"jinsert",
                "evaluaciones"=>validate::getGetParam('evaluacion'),
                "idUsuario"=>$this->_usuario            
            );


            $parameters = json_encode($datos,true);
            if($this->_productor->grabarProductor($parameters))
            {
               echo '1' ;
            }else
                {
                    echo '0';                    
                }


        }


    }



}