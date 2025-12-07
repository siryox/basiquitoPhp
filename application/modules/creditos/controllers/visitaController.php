<?php 
class visitaController extends creditosController
{
    private $_visita;
    private $_producto;
    public function __construct()
    {
        parent::__construct();
        
        $this->_visita = $this->loadModel('visita');
        $this->_producto = $this->loadModel('producto','almacen');
    }

    public function index($pagina = 1)
    {
        $this->_view->title = "Visitas Técnicas";
       
        //$this->_view->setJsPlugin(array('validaciones'));
        $this->getLibrary('paginador');
        $paginador = new Paginador();
        
        $visitas = $this->_visita->cargarVisitas();

        //print_r($visitas); exit();
        if(count($visitas))
        {
            foreach($visitas as $row)
            {
                $data = json_decode($row['data'],true);
                $productos = (!empty($row['ArrProductos']))?json_decode($row['ArrProductos'],true):[];

               // print_r($xx);
                
                $imagenes  = (isset($data['images']))?$data['images']:[];
                $videos  = (isset($data['videos']))?$data['videos']:[];

                if(count($productos)>0)
                {
                    $listaProductos = "<ul>";
                    foreach($productos as $prod)
                    {
                        $listaProductos .= '<li>'.$prod['codigo'].' | '.$prod['descripcion'].' | Cant: '.$prod['cantidad'].'</li>';
                    }
                    $listaProductos .= "</ul>";
                }else
                    $listaProductos="";
                    $listaImagenes="";
                if(count($imagenes))
                {
                    $listaImagenes = "<ul>";
                    for($j = 0; $j < count($imagenes);$j++ )
                    {
                        $listaImagenes .= '<a href="#" class="verImagen" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-xl" title="Ver Archivo"><li>'.$imagenes[$j].'</li></a>';
                    }
                    $listaImagenes .= "</ul>";
                }
                
                $listavideos = "";
                if(count($videos))
                {
                    $listavideos = "<ul>";
                    for($j = 0; $j < count($videos);$j++ )
                    {
                        $listavideos .= '<a href="#" class="verVideos" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-lg" title="Ver Archivo"><li>'.$videos[$j].'</li></a>';
                    }
                    $listavideos .= "</ul>";
                }


                $lista[] = ["id"=>$row['id'],"fecha"=>$row['fecha'],"tecnico"=>$row['nombreTecnico'],"credito"=>$row['idCredito'],
                "rubro"=>"","productor"=>$row['productor'],"finca"=>$row['nombreFinca'],"latitud"=>$data['latitud'],"longitud"=>$data['longitud'],"etapa"=>$data['fase'],"estado"=>$data['estadoActual'],
                "cantidadProductos"=>count($productos),"cantidadImagenes"=>count($imagenes),"detalles"=>["productos"=>$listaProductos,"fondos"=>$data['fondos'],"motivo"=>(isset($data['motivoFondo']))?$data['motivoFondo']:"","imagenes"=>$listaImagenes,
                "comentarios"=>$data['comentarios'],"videos"=>$listavideos]];
            }

        }else
            $lista = [];

       // print_r($visitas);
       // exit();    

        $this->_view->lista = $paginador->paginar($lista,$pagina,10); 
        $this->_view->setCss(array('style'));
        $this->_view->setJs(array('visita'));
        //$this->_view->setExternalJs(array('https://maps.googleapis.com/maps/api/js?key=AIzaSyAyQ7LQ-TITIRc7ZpD7BOcDT5NmKX0mUdY&callback=initMap&v=weekly'));
        //$this->_view->setJsPlugin(array('googleMaps'));
        //$this->_view->setJsPlugin(['leaFlet']);
        //$this->_view->setCssPlugin(array('leatflet'));
        
        $this->_view->paginacion = $paginador->getView('paginacion','creditos/visita/index');	
        $this->_view->renderizar('index','creditos','Visitas Tecnicas');
        exit();

    }






    public function cargarImgVisitas()
    {   
        $parameter = $parameter = '{"action":"search visita","id":"'.validate::getPostParam('value').'"}';
        echo json_encode($this->_visita->cargarVisitas($parameter));
    }







}


