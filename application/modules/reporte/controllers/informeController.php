<?php
class informeController extends reporteController
{
    private $_informe;
    private $_programa;
    private $_usuario;
    private $_convenio;

    public function __construct()
    {
        parent::__construct();
        $this->_programa = $this->loadModel('programa','financiamiento');
        $this->_informe = $this->loadModel('informe');
        $this->_convenio = $this->loadModel('convenio','financiamiento');

        $this->_usuario = session::get('id_usuario');
    }

    public function index()
    {

        $this->_view->programa = $this->_programa->cargarPrgfinanc();
        $this->_view->convenio = $this->_convenio->cargarConvenio(); 

        $this->_view->setJs(["informe"]);
        $this->_view->setCss(array('print'));
        $this->_view->setJsPlugin(array('print'));
        
        $this->_view->title = "Reportes";
        $this->_view->renderizar('index','reporte','Estadisticas e Informes');
        exit();
    }






     // METODO PARA CARGAR REPORTE DE SEGUIMIENTO (FORMATO DE IMPRESION)
     public function cargarRepSeguimiento()
     {
         $parameter = '{"idUsuario":'.$this->_usuario.',"idProgFinanc":"'.validate::getPostParam('value').'"}';
         echo json_encode($this->_informe->seguimientoCred($parameter),JSON_INVALID_UTF8_IGNORE);   

     }
 

      // METODO PARA CARGAR REPORTE DE SEGUIMIENTO (FORMATO DE IMPRESION)
      public function cargarResumenPlan()
      {

          $parameter = '{"idUsuario":'.$this->_usuario.',"idProgFinanc":"'.validate::getPostParam('value').'"}';
          $contenido = $this->_informe->resumenPlan($parameter); 
          //$html = json_decode($contenido,true);
          if($contenido[0]['rep'] == null)
          {
            echo 'false';
          }else
            {
                $programa = $this->_programa->cargarPrgfinanc('{"action":"search","campo":"id","valor":"'.validate::getPostParam('value').'"}');
                
                $res[] = ["rep"=>$contenido[0]['rep'],"nombre"=>trim($programa[0]['nombre']).'.xls'];
                //echo json_encode($res,JSON_INVALID_UTF8_IGNORE);
                echo json_encode($res,true);
                
                $doc = APP_PATH.'public/excel/'.trim($programa[0]['nombre']).'.xls';  
                file_put_contents($doc, $contenido[0]['rep'],LOCK_EX);
            }    
          
           
      }

      //METODO PARA CARGR EL RESUMEN DE COSECHA
      public function cargarResumenCosecha()
      {
        
          $parameter = '{"idUsuario":"'.$this->_usuario.'","idProgFinanc":"'.validate::getPostParam('value1').'","convenio":"'.validate::getPostParam('value2').'"}';
          $contenido = json_encode($this->_informe->resumenCosecha($parameter),JSON_INVALID_UTF8_IGNORE);   
          if(!empty($contenido))
          {
            echo $contenido; 
            $doc = APP_PATH.'public/excel/MetaResumenCosecha.xls';
            $html = json_decode($contenido,true);  
            file_put_contents($doc, $html[0]['rep'],LOCK_EX);
          
            }else
                echo 'false';
          

          
      }

      




}



?>