<?php
final class fincaController extends productoresController
{
    private $_finca;
    private $_usuario;
    public function __construct()
    {
        parent::__construct();
        $this->_usuario = session::get('id_usuario');
        $this->_finca = $this->loadModel('finca');
    }

    public function index()
    {
        //$this->_view->setJs(array('programa'));

        $parameters = '{"action":"search all"}';
        $this->_view->lista = $this->_finca->cargarFincas($parameters);

        $this->_view->title = "Unidades de Produccion";
        $this->_view->setJs(array('finca'));
        $this->_view->renderizar('index','productores','Unidad Produccion');
        exit();

    }



    public function agregar()
    {

        if(validate::getInt('guardar')==1)
        {

            $direccion = array(
                "direccion" =>strtoupper(validate::getPostParam('direccion')),
                "sector"    =>strtoupper(validate::getPostParam('sector')),
                "municipio" =>validate::getInt('municipio'),
                "estado"    =>validate::getInt('estado'),
                "geoloc"    =>"",
                "parroquia" =>validate::getInt('parroquia')
                 
            );

            $telefonos = array(
                "propietario"=>validate::getPostParam('tlf_propietario'),
                "finca"=>validate::getPostParam('tlf_finca'),
                "encargado"=>validate::getPostParam('tlf_encargado')
            );
            $correos = array(
                "propietario"=>validate::getPostParam('correo_propietario'),
                "finca"=>validate::getPostParam('correo_finca'),
                "encargado"=>validate::getPostParam('correo_encargado')
            );

            //-------------------------------------------------------
            $nroLote= validate::getPostParam('nroLote');
            $superficieLote = validate::getPostParam('superficieLote');
            $coordenadaLote = validate::getPostParam('coordenadaLote');
            $estadoLote =     validate::getPostParam('estadoLote');
            if(!empty($nroLote))
            {
                for($i=0;$i < count($nroLote);$i++)
                {
                    $lotes[] = ["nro"=>$nroLote[$i],"superficie"=>$superficieLote[$i],"estado"=>$estadoLote[$i],"coordenada"=>$coordenadaLote[$i]];
                }
            }else
                {
                    $lotes[] = ["nro"=>"","superficie"=>"","estado"=>"","coordenada"=>""];
                }
            

            $datos = array(
                "action"=>"jinsert",
                "nombre"=>strtoupper(validate::getPostParam('nomUnidad')),
                "superficie"=>validate::getPostParam('superfUnidad'),
                "tenenciaTierra"=>validate::getPostParam('tenenciaTierra'),
                "tipoSuelos"=>strtoupper(validate::getPostParam('tipoSuelo')),
                "idFiscalPropietario"=>validate::getPostParam('idFiscal'),
                "nombrePropietario"=>strtoupper(validate::getPostParam('nomPropietario')),
               // "otrosDatos"=>validate::getPostParam(''),
                "comentarios"=>strtoupper(validate::getPostParam('comentario')),
                "direccion"=>$direccion, 
                "telefonos"=>$telefonos,
                "correos"=>$correos,
                "lotes"=>$lotes,
                "usuario"=>$this->_usuario
            );

            $parameters = json_encode($datos,true);

            //print_r($parameters);exit();

            if($this->_finca->grabarFinca($parameters))
            {
                $msj = $this->_finca->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();

            }else
                {
                    $msj = $this->_finca->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();


                }


        }

        $this->_estado = $this->loadModel('estado','configuracion');
        $this->_view->estado = $this->_estado->cargarEstado();

        $this->_view->title = "Unidades de Produccion";
        $this->_view->setJs(array('finca'));
        $this->_view->renderizar('agregar','productores','Unidad Produccion');
        exit();
    }

    public function editar($id=0)
    {
        if(validate::getInt('guardar')==2)
        {

            $direccion = array(
                "direccion" =>strtoupper(validate::getPostParam('direccion')),
                "sector"    =>strtoupper(validate::getPostParam('sector')),
                "municipio" =>validate::getInt('municipio'),
                "estado"    =>validate::getInt('estado'),
                "geoloc"    =>validate::getPostParam('coordUnidad'),
                "parroquia" =>validate::getInt('parroquia')
                 
            );

            $telefonos = array(
                "propietario"=>validate::getPostParam('tlf_propietario'),
                "finca"=>validate::getPostParam('tlf_finca'),
                "encargado"=>validate::getPostParam('tlf_encargado')
            );
            $correos = array(
                "propietario"=>validate::getPostParam('correo_propietario'),
                "finca"=>validate::getPostParam('correo_finca'),
                "encargado"=>validate::getPostParam('correo_encargado')
            );
            //print_r($_POST);exit();
            //-------------------------------------------------------
            $nroLote= validate::getPostParam('nroLote');
            $superficieLote = validate::getPostParam('superficieLote');
            $coordenadaLote = validate::getPostParam('coordenadaLote');
            $estadoLote =     validate::getPostParam('estadoLote');
            if(!empty($nroLote))
            {
                for($i=0;$i < count($nroLote);$i++)
                {
                    $lotes[] = ["nro"=>$nroLote[$i],"superficie"=>$superficieLote[$i],"estado"=>$estadoLote[$i],"coordenada"=>$coordenadaLote[$i]];
                }
            }else
                {
                    $lotes[] = ["nro"=>0,"superficie"=>0,"estado"=>"","coordenada"=>""];

                }
            $datos = array(
                "action"=>"jupdate",
                "id"=>validate::getInt('id'),
                "nombre"=>strtoupper(validate::getPostParam('nomUnidad')),
                "superficie"=>validate::getPostParam('superfUnidad'),
                "tenenciaTierra"=>strtoupper(validate::getPostParam('tenenciaTierra')),
                "tipoSuelos"=>strtoupper(validate::getPostParam('tipoSuelo')),
                "idFiscalPropietario"=>validate::getPostParam('idFiscal'),
                "nombrePropietario"=>strtoupper(validate::getPostParam('nomPropietario')),
               // "otrosDatos"=>validate::getPostParam(''),
                "comentarios"=>strtoupper(validate::getPostParam('comentario')),
                "direccion"=>$direccion, 
                "telefonos"=>$telefonos,
                "correos"=>$correos,
                "lotes"=>$lotes,
                "usuario"=>$this->_usuario
            );

            $parameters = json_encode($datos,true);

            //print_r($parameters); exit();

            if($this->_finca->grabarFinca($parameters))
            {
                $msj = $this->_finca->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();

            }else
                {
                    $msj = $this->_finca->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();


                }


        }

        if($id)
        {
            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';
            $datos = $this->_finca->cargarFincas($parameters);
            //print_r($datos); exit();
            $this->_view->datos = $datos;

            $lotes = json_decode($datos[0]['lotes'],true);
            $this->lotes = $lotes;
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





        $this->_view->title = "Unidades de Produccion";
        $this->_view->setJs(array('finca'));
        $this->_view->renderizar('editar','productores','Unidad Produccion');
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
        
            if($this->_finca->eliminarFinca(json_encode($datos,true)))
            {
                
                $this->redireccionar('productores/finca/index/');
                exit();
            }else
                {
                    $msj = $this->_finca->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
        
        }
        if($id)
        {

            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';

            $datos = $this->_finca->cargarFincas($parameters);

            if(count($datos))
            {
                //print_r($datos);
                //exit();
                $this->_view->datos = $datos;
            }

            $this->_view->title = "Unidad de Produccion";
            $this->_view->setJs(array('finca'));
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


}

?>