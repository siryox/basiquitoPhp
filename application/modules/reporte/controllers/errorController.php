<?php
    class errorController extends reporteController
    {

        public function __construct()
        {
            parent::__construct();

        }


        public function index()
        {
           


        }

        public function alert($mjs=false)
        {
            if($mjs)
            {
                $parameters = json_decode(base64_decode($mjs),true);

                //print_r($parameters);

                $this->_view->controlador = $parameters['controlador'];
                $this->_view->modulo = $parameters['modulo'];
                $result = json_decode($parameters['mensaje'],true);
                $this->_view->msj = $result;

                $this->_view->renderizar('respuesta');
                exit();
            }    
        }
        public function respuesta($mjs=false)
        {
            if($mjs)
            {
                $parameters = json_decode(base64_decode($mjs),true);

                //print_r($parameters);

                $this->_view->controlador = $parameters['controlador'];
                $this->_view->modulo = $parameters['modulo'];
                $result = json_decode($parameters['mensaje'],true);
                $this->_view->mensaje = $result;

                $this->_view->renderizar('respuesta','Productores','Productores');
                exit();
            }    
        }




    }


?>