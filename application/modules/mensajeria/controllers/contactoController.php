<?php
class contactoController extends mensajeriaController
{
    private $_contacto;
    public function __construct()
    {
        parent::__construct();
        $this->_contacto = $this->loadModel('contacto');    
    }


    public function index()
    {

        $datos = $this->_contacto->getContacto();

        print_r($datos);
        exit();

        $this->_view->renderizar('index','mensajeria','contacto');
        exit();
    }







}



