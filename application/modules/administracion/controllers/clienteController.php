<?php 
class clienteController extends administracionController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {


        $this->_view->title = "Clientes";
        $this->_view->renderizar('index','administracion','cliente');
        exit();
    }




}