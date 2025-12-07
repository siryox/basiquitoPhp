<?php
class configuracionController extends controller
{
    public function __construct() {
        parent::__construct();
        $this->getHelper("validate");
        $this->getHelper("logger");
        session::acceso();
    }
    public function index() {
        
    }
    
}

