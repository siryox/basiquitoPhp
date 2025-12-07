<?php
final class programaController extends financiamientoController
{
    private $_programaf;
    private $_usuario;
    public function __construct()
    {
        parent::__construct();

        $this->_programaf = $this->loadModel('programa');
        $this->_usuario = session::get('id_usuario');
    }


    public function index()
    {
        
        //$this->_view->setJs(array('programa'));

        $parameters = '{"action":"search all"}';
        $this->_view->lista = $this->_programaf->cargarPrgfinanc($parameters);

        $this->_view->title = "Programa de Financiamiento";
        $this->_view->setJs(array('programa'));
        $this->_view->renderizar('index','financiamiento','Programa');
        exit();

    }


    public function agregar()
    {

        if(validate::getInt('guardar')==1)
        {


            
            $preparacion = array(
                "dias"=>validate::getInt('prepDias'),
                "visitas"=>validate::getInt('prepVisitas'),
                "Lt-combustible"=>validate::getInt('prepCombustible'),
                "Mto-combustible"=>validate::getInt('prepCombustibleMto'),
                "Mto-otros"=>validate::getPostParam('prepOtros')
            );


            $siembra = array(
                "visitas"=>validate::getInt('siembVisitas'),
                "dias"=>validate::getInt('siembDias'),
                "Mto-semillas"=>validate::getPostParam('siembSemillasMto'),
                "Kg-semillas"=>validate::getPostParam('siembSemillasKg'),
                "Lt-combustible"=>validate::getPostParam('siembCombustible'),
                "Mto-combustible"=>validate::getPostParam('siembCombustibleMto'),
                "Mto-otros"=>validate::getPostParam('siembOtros')
            );


            $mantenimiento = array(
                "dias"=>validate::getInt('mantVisitas'),
                "visitas"=>validate::getInt('mantDias'),
                "Lt-combustible"=>validate::getPostParam('mantCombustible'),
                "Mto-combustible"=>validate::getPostParam('mantCombustibleMto'),
                "Kg-fertilizante"=>validate::getPostParam('mantFertilizante'),
                "Mto-fertilizante"=>validate::getPostParam('mantFertilizanteMto'),
                "Mto-agroquimicos"=>validate::getPostParam('mantMontoagroq'),
                "Mto-labores"=>validate::getPostParam('mantLabores'),
                "Mto-otros"=>validate::getPostParam('mantOtros'),
            );

            $cosecha = array(
                "visitas"=>validate::getInt('cosecVisitas'),
                "dias"=>validate::getInt('cosecDias'),
                "Lt-combustible"=>validate::getPostParam('cosecCombustible'),
                "Mto-combustible"=>validate::getPostParam('cosecCombustibleMto'),
                "Mto-transporte"=>validate::getPostParam('cosecTransporte'),
                "Mto-otros"=>validate::getPostParam('cosecOtros')
            );

            $fases = [
                "preparacion"=>json_encode($preparacion,true),
                "siembra"=>$siembra,
                "mantenimiento"=>$mantenimiento,
                "cosecha"=>$cosecha
            ];

            $produccion = [
                "costoKg"=> validate::getPostParam('precioLiquidacion'),
                "prodTotalKg"=>"0",
                "prodTotalMto"=>"0",
             ];  
             
             $rendimiento = [
                 "rendEstimHa"=> validate::getPostParam('rendimientoEstimado'),
                 "rendRealHa"=> "0"
             ];
            
            $programa = array(
                "rubro"=>validate::getPostParam('rubro'),
                "ciclo"=>validate::getPostParam('ciclo'),
                "estado"=>validate::getPostParam('estado'),
                "moneda"=>validate::getPostParam('moneda'),
                "tasaInteres"=>validate::getPostParam('tasaInteres'),
                "hectMin"=>validate::getPostParam('haMin'),
                "hectMax"=>validate::getPostParam('haMax'),
                "fechaInicio"=>validate::getPostParam('fechaInicio'),
                "fechaFinal"=>validate::getPostParam('fechaFinal'),
                "mtoAsisTecHa"=> validate::getPostParam('ctoAsistTec'),
                "hectCaptadas"=>validate::getPostParam('haCapt'),
                "hectAprobadas"=>validate::getPostParam('haAprob'),
                "hectFinanciadas"=>validate::getPostParam('haFinanc'),
                "hectCosechad"=> "0",
                "cantCredAprob"=> "0",
                "cantCredRechaz"=> "0",
                "produccion"=>$produccion,
                "rendimiento"=>$rendimiento,
                "fases"=>$fases
            );

            $datos = json_encode(["action"=>"jinsert","id_usuario"=>"$this->_usuario","detalles"=>$programa],true);

            if($this->_programaf->guardarPrograma($datos))
            {
                $msj = $this->_programaf->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();

            }else
                {
                    $msj = $this->_programaf->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit(); 
                }


        }

        
        $this->_view->vrubro = $this->_programaf->cargarRubros();
        $this->_view->vciclo = $this->_programaf->cargarCiclos();
        $this->_view->vmoneda = $this->_programaf->cargarMonedas();

        $this->_view->title = "Programa de Financiamiento";
        $this->_view->setJs(array('programa'));
        $this->_view->renderizar('agregar','financiamiento','Programa');
        exit();

    }


    public function editar($id=false)
    {

        if(validate::getInt('guardar')==2)
        {
            
            $preparacion = array(
                "dias"=>validate::getInt('prepDias'),
                "visitas"=>validate::getInt('prepVisitas'),
                "Lt-combustible"=>validate::getInt('prepCombustible'),
                "Mto-combustible"=>validate::getInt('prepCombustibleMto'),
                "Mto-otros"=>validate::getPostParam('prepOtros')
            );


            $siembra = array(
                "visitas"=>validate::getInt('siembVisitas'),
                "dias"=>validate::getInt('siembDias'),
                "Mto-semillas"=>validate::getPostParam('siembSemillasMto'),
                "Kg-semillas"=>validate::getPostParam('siembSemillas'),
                "Lt-combustible"=>validate::getPostParam('siembCombustible'),
                "Mto-combustible"=>validate::getPostParam('siembCombustibleMto'),
                "Mto-otros"=>validate::getPostParam('siembOtros')
            );


            $mantenimiento = array(
                "dias"=>validate::getInt('mantVisitas'),
                "visitas"=>validate::getInt('mantDias'),
                "Lt-combustible"=>validate::getPostParam('mantCombustible'),
                "Mto-combustible"=>validate::getPostParam('mantCombustibleMto'),
                "Kg-fertilizante"=>validate::getPostParam('mantFertilizante'),
                "Mto-fertilizante"=>validate::getPostParam('mantFertilizanteMto'),
                "Mto-agroquimicos"=>validate::getPostParam('mantMontoagroq'),
                "Mto-labores"=>validate::getPostParam('mantLabores'),
                "Mto-otros"=>validate::getPostParam('mantOtros'),
            );

            $cosecha = array(
                "visitas"=>validate::getInt('cosecVisitas'),
                "dias"=>validate::getInt('cosecDias'),
                "Lt-combustible"=>validate::getPostParam('cosecCombustible'),
                "Mto-combustible"=>validate::getPostParam('cosecCombustibleMto'),
                "Mto-transporte"=>validate::getPostParam('cosecTransporte'),
                "Mto-otros"=>validate::getPostParam('cosecOtros')
            );

            $fases = [
                "preparacion"=>$preparacion,
                "siembra"=>$siembra,
                "mantenimiento"=>$mantenimiento,
                "cosecha"=>$cosecha
            ];

            $produccion = [
               "costoKg"=> validate::getPostParam('precioLiquidacion'),
               "prodTotalKg"=>"0",
               "prodTotalMto"=>"0",
            ];  
            
            $rendimiento = [
                "rendEstimHa"=> validate::getPostParam('rendimientoEstimado'),
                "rendRealHa"=> "0"
            ];
            $programa = array(
                "rubro"=>validate::getPostParam('rubro'),
                "ciclo"=>validate::getPostParam('ciclo'),
                "estado"=>validate::getPostParam('estado'),
                "moneda"=>validate::getPostParam('moneda'),
                "tasaInteres"=>validate::getPostParam('tasaInteres'),
                "hectMin"=>validate::getPostParam('haMin'),
                "hectMax"=>validate::getPostParam('haMax'),
                "fechaInicio"=>validate::getPostParam('fechaInicio'),
                "fechaFinal"=>validate::getPostParam('fechaFinal'),
                "mtoAsisTecHa"=> validate::getPostParam('ctoAsistTec'),
                "hectCaptadas"=>validate::getPostParam('haCapt'),
                "hectAprobadas"=>validate::getPostParam('haAprob'),
                "hectFinanciad"=>validate::getPostParam('haFinanc'),
                "hectCosechad"=> "0",
                "cantCredAprob"=> "0",
                "cantCredRechaz"=> "0",
                "produccion"=>$produccion,
                "rendimiento"=>$rendimiento,
                "fases"=>$fases
                
            );

            
    

            $id=validate::getInt('id');
            $datos = json_encode(["action"=>"jupdate","id_usuario"=>"$this->_usuario","id"=>$id,"detalles"=>$programa],true);
            if($this->_programaf->guardarPrograma($datos))
            {
                $msj = $this->_programaf->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();

            }else
                {
                    $msj = $this->_programaf->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit(); 
                }


        }

        if($id)
        {
            $parameters = '{"action":"search","campo":"id","valor":"'.$id.'"}';

            $datos = $this->_programaf->cargarPrgfinanc($parameters);
            
            if(count($datos))
            {
                //print_r($datos);
                //exit();
                $this->_view->datos = $datos;
            }

        }       
        
        $this->_view->vrubro = $this->_programaf->cargarRubros();
        $this->_view->vciclo = $this->_programaf->cargarCiclos();
        $this->_view->vmoneda = $this->_programaf->cargarMonedas();

        $this->_view->title = "Programa de Financiamiento";
        $this->_view->setJs(array('programa'));
        $this->_view->renderizar('editar','financiamiento','Programa');
        exit();
    }


    //------------------------------------------------------------
    //metodo para eliminar un programa de financiamiento
    //------------------------------------------------------------
    public function eliminar($id=false)
    {
        if(validate::getInt('eliminar')==1)
        {

            $datos = array(
                   "id"=> validate::getInt('id'), 
                   "id_usuario"=>$this->_usuario,
                   "action"=>"jdelete"
            );
        
            if($this->_programaf->eliminarPrograma(json_encode($datos,true)))
            {
                $this->redireccionar('financiamiento/programa/index/');
                exit();
            }else
                {
                    $msj = $this->_programaf->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit();
                }
        
        }
        if($id)
        {
            $parameter = '{"action":"search","campo":"id","valor":"'.$id.'"}';
            $datos = $this->_programaf->cargarPrgfinanc($parameter); 
            if(count($datos))
            {
                
                $this->_view->datos = $datos;
            }

            $this->_view->title = "Programa de Financiamiento";
            $this->_view->setJs(array('programa'));
            $this->_view->renderizar('eliminar','financiamiento','Programa');
            exit();

        }



    }


    public function operaciones($pf=false)
    {
        
        
        if(validate::getInt('guardar')==3)
        {
            $datos = [
                "idProgFinanc"=>validate::getPostParam('programa'),
                "id_usuario"=>$this->_usuario ];
                
            if($this->_programaf->generarCuotasProgram(json_encode($datos,true)))
            {
                $msj = $this->_programaf->getResult();
                $this->redireccionar('reporte/error/alert/',$msj);
                exit();

            }else
                {
                    $msj = $this->_programaf->getResult();
                    $this->redireccionar('reporte/error/alert/',$msj);
                    exit(); 
                }    
        }    

        $datos = $this->_programaf->cargarCxc($pf);
        $this->_view->datos = $datos;
        //print_r($datos);

        $parameters = '{"action":"search","campo":"id","valor":'.$pf.'}';
        $this->_view->pfActual = $this->_programaf->cargarPrgfinanc($parameters);

        $parameters = '{"action":"search all"}';
        $this->_view->lista = $this->_programaf->cargarPrgfinanc($parameters);
        
        $this->_view->title = "Programa de Financiamiento";
        $this->_view->setJs(array('programa'));

        $this->_view->renderizar('operaciones','financiamiento','Programa');
        exit();
    }


    public function cargarcuotas()
    {

            $datos = $this->_programaf->cargarCxc(validate::getPostParam('value'));
            echo json_encode($datos);

    }

    public function cargarprograma()
    {

            $parameters = '{"action":"search","campo":"id","valor":'.validate::getPostParam('value').'}';
            $datos = $this->_programaf->cargarPrgfinanc($parameters);
            echo json_encode($datos);
    }


}