<?php 
class servicioController extends administracionController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {


        $this->_view->title = "Servicios";
        $this->_view->renderizar('index','administracion','servicio');
        exit();
    }




}