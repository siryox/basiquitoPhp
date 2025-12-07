<?php 
class controlController extends administracionController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {


        $this->_view->title = "Control de Operaciones";
        $this->_view->renderizar('index','administracion','control');
        exit();
    }




}