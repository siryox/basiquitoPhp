<?php 
final class movimientoController extends almacenController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {





        $this->_view->title = "Movimientos de Inventario";
	    $this->_view->renderizar('index','almacen','Movimientos');
        exit();        
    }

    public function agregar()
    {







        $this->_view->renderizar('agregar','almacen','Movimientos');
        exit();

    }

}