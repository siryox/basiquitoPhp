<?php
class productorController extends productoresController
{
    private $_productor;
    private $_empresa;
    private $_municipio;
    private $_parroquia;
    private $_sector;
    private $_estado;
    private $_usuario;
    private $_finca;
    private $_credito;
    private $_mov;
    private $_despachos;
    public function __construct()
    {
        parent::__construct();

        $this->_productor = $this->loadModel('productor');
        $this->_empresa = session::get('empresa');
        $this->_finca = $this->loadModel('finca','productores');
        $this->_usuario = session::get('id_usuario');     
        
        
    }


    public function index(int $pagina = 1):void
    {
        $this->_view->title = "Productores";
        $this->_view->setJs(array('productor'));



        
                
        $this->_view->lista = $this->_productor->cargarProductor('{"action":"search all"}');
        $this->_view->renderizar('index','productores','Productor');
        exit();
    }
    ///-----------------------------------------------------------------------------------
    //
    //-----------------------------------------------------------------------------------
    public function agregar()
    {
        if(validate::getInt('guardar')==1)
        {

            $direccion = array(
                "direccion" =>validate::getPostParam('direccion'),
                "sector"    =>validate::getPostParam('sector'),
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

            $titular = validate::getPostParam('titular');
            $cuenta  = validate::getPostParam('cuenta');
            $banco   = validate::getPostParam('banco');
            if(is_array($titular))
            {
                for($j=0;$j < count($titular);$j++)
                {
                    $datosBancos[] = ["titular"=>$titular[$j],"banco"=>$banco[$j],"cuenta"=>$cuenta[$j]];
                }
            }
             

            $otrosDatos = array(
                "runoppa"=>validate::getPostParam('runoppa'),
                "sigesai"=>["usuario"=>validate::getPostParam('usuario_sigesai'),"clave"=>validate::getPostParam('clave_sigesai')],
                "sunagro"=>["usuario"=>validate::getPostParam('usuario_sunagro'),"clave"=>validate::getPostParam('clave_sunagro')],
                "ctasBancarias"=>$datosBancos
            );


            $datos = array(
                "tipoPersona"=>validate::getPostParam('tipo'),
                "idFiscal"=>validate::getPostParam('idFiscal'),
                "razonSocial"=>validate::getPostParam('razon_social'),
                "comentarios"=>validate::getPostParam('comentario'),
                "direccion"=>$direccion,  
                "mediosContacto"=>$medioscontacto,
                "otrosDatos"=>$otrosDatos,
                "estado"=>"ACTIVO",
                "action"=>"jinsert",
                "evaluaciones"=>validate::getGetParam('evaluacion'),
                "idUsuario"=>$this->_usuario            
            );

            //print_r($datos);exit;
            $parameters = json_encode($datos,true);
            if($this->_productor->grabarProductor($parameters))
            {
                $msj = $this->_productor->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
            }else
                {
                    $msj = $this->_productor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }


        }

        $this->_estado = $this->loadModel('estado','configuracion');
        $this->_view->estado = $this->_estado->cargarEstado();

        $this->_view->bancos = $this->_productor->cargarBancos();

        $this->_view->title = "Productores";
        $this->_view->setJs(array('productor'));
        $this->_view->setJsPlugin(["validaciones"]);
        $this->_view->renderizar('agregar','Productores','Productores');
        exit();

    }

    //--------------------------------------------------------------------------
    //metodo para editar productor
    public function editar($id=0)
    {

        if(validate::getInt('guardar')==2)
        {
           // print_r($_POST);

            $direccion = array(
                "direccion" =>validate::getPostParam('direccion'),
                "sector"    =>validate::getPostParam('sector'),
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

            $titular = validate::getPostParam('titular');
            $cuenta  = validate::getPostParam('cuenta');
            $banco   = validate::getPostParam('banco');
            if(is_array($titular))
            {
                for($j=0;$j < count($titular);$j++)
                {
                    $datosBancos[] = ["titular"=>$titular[$j],"banco"=>$banco[$j],"cuenta"=>$cuenta[$j]];
                }
            }
             

            $otrosDatos = array(
                "runoppa"=>validate::getPostParam('runoppa'),
                "sigesai"=>["usuario"=>validate::getPostParam('usuario_sigesai'),"clave"=>validate::getPostParam('clave_sigesai')],
                "sunagro"=>["usuario"=>validate::getPostParam('usuario_sunagro'),"clave"=>validate::getPostParam('clave_sunagro')],
                "ctasBancarias"=>$datosBancos
            );


            $datos = array(
                "id"=>validate::getInt('id'),
                "tipoPersona"=>validate::getPostParam('tipo'),
                "idFiscal"=>validate::getPostParam('idFiscal'),
                "razonSocial"=>validate::getPostParam('razon_social'),
                "comentarios"=>validate::getPostParam('comentario'),
                "direccion"=>$direccion,  
                "mediosContacto"=>$medioscontacto,
                "otrosDatos"=>$otrosDatos,
                "estado"=>"ACTIVO",
                //"evaluaciones"=>validate::getPostParam('evaluacion'),
                "action"=>"jupdate",
                "idUsuario"=>$this->_usuario
                
            );

            $parameters = json_encode($datos,true);
            
            //echo $parameters;
           // exit();

            if($this->_productor->grabarProductor($parameters))
            {
                
                $msj = $this->_productor->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();
                
                //$this->redireccionar('productores/productor/index/');
                //exit();

            }else
                {
                    $msj = $this->_productor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }

        }

        if($id > 0)
        {
            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';

            $datos = $this->_productor->cargarProductor($parameters);

            if(count($datos))
            {
                //print_r($datos);
                //exit();
                $this->_view->datos = $datos;
                $this->_view->cuentas = json_decode($datos[0]['ctasBancarias'],true);
            }


        }
        //cargo los datos de estados
        $this->_estado = $this->loadModel('estado','configuracion');
        $this->_view->estado = $this->_estado->cargarEstado();
        //cargo los datos de los municipios
        $this->_municipio = $this->loadModel('municipio','configuracion');
        $this->_view->municipio = $this->_municipio->cargarMunicipio();
        //cargo los datos de las parroquias
        $this->_parroquia = $this->loadModel('parroquia','configuracion');
        $this->_view->parroquia = $this->_parroquia->cargarParroquia();

        $this->_view->bancos = $this->_productor->cargarBancos();



        $this->_view->title = "Productores";
        $this->_view->setJs(array('productor'));
        $this->_view->renderizar('editar','Productores','Productores');
        exit();
    }


    public function cuenta($id)
    {


        $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';

        $datos = $this->_productor->cargarProductor($parameters);

        if(count($datos))
        {
            //print_r($datos);
            //exit();
            $this->_view->productor = $datos;
            //$this->_view->cuentas = json_decode($datos[0]['ctasBancarias'],true);


            ////cargo datos de la finca
            $parameters = '{"action":"search","campo":"propietario","valor":"'.$datos[0]['idFiscal'].'"}';
            $this->_view->finca = $this->_finca->cargarFincas($parameters);



            //-------busco los creditos del productos
            $this->_credito = $this->loadModel('credito','creditos');
            $parameters = '{"action":"search","campo1":"idFiscalProductor","v1":"'.$datos[0]['idFiscal'].'"}';
            $creditos = $this->_credito->cargarCreditos($parameters);
            $this->_view->creditos = $creditos; 
            //print_r($creditos);
            $mov = $this->loadModel('despacho','almacen');
            $movInventario = $mov->cargarDespachos('{"action":"search","campo":"movimientos","c1":"origen","v1":"'.$datos[0]['id'].'","c2":"destino","v2":"'.$datos[0]['id'].'"}');
            //print_r($movInventario);
           
            $this->_view->movimientos = $movInventario;


        }


        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));

        $this->_view->title = "Detalle de Productor";
        $this->_view->setJs(array('productor'));
        $this->_view->renderizar('cuenta','Productores','Productores');
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
        
            if($this->_productor->eliminarProductor(json_encode($datos,true)))
            {
                
                $this->redireccionar('productores/productor/index/');
                exit();
            }else
                {
                    $msj = $this->_productor->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
        
        }
        if($id)
        {

            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';

            $datos = $this->_productor->cargarProductor($parameters);

            if(count($datos))
            {
                //print_r($datos);
                //exit();
                $this->_view->datos = $datos;
            }

            $this->_view->title = "Productores";
            $this->_view->setJs(array('productor'));
            $this->_view->renderizar('eliminar','Productores','Productores');
            exit();

        }

    }



    public function loadMunicipio()
    {
        $this->_municipio = $this->loadModel('municipio','configuracion');
        echo json_encode($this->_municipio->buscarMunicipios(validate::getInt('value')));
    }


    public function loadParroquia()
    {
        $this->_parroquia = $this->loadModel('parroquia','configuracion');
        echo json_encode($this->_parroquia->buscarParroquias(validate::getInt('value')));
    }


    public function validarProductor()
    {
        $parameters = '{"action":"search","campo":"idFiscal","valor":"'.validate::getPostParam('valor').'"}';
        echo json_encode($this->_productor->cargarProductor($parameters));
    }

    public function cargarDespachoProductor()
    {
        $despachos = $this->loadModel('despacho','creditos');
        $parameters = '{"action":"search","campo1":"credito","v1":"'.validate::getPostParam('value').'"}';
        echo json_encode($despachos->cargarDespachos($parameters));
    }

    public function cargarNotaImpAlmacen()
    {
        echo json_encode($this->_productor->cargarNotaImpAlmacen(validate::getPostParam('value')),JSON_INVALID_UTF8_IGNORE);
    }


}