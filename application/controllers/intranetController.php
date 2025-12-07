<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Your Name <RSSistemas>
 * @date 25/07/2019
 * @time 12:32:41 AM
 */

class intranetController extends controller
{

    private $_empresa;

    private $_programa;

    public function __construct() {
        parent::__construct();
        $this->getHelper("validate");
        $this->_empresa = $this->loadModel('empresa','configuracion');
        $this->_programa = $this->loadModel('programa','financiamiento');

    }

    public function index($id = false) {


        $emp = $this->_empresa->cargarEmpresaUsuario(session::get('id_usuario'));
        //print_r($emp);exit();
        if(count($emp)>0)
        {
			if($id > 0)
			{
				$this->_empresa->inactivarEmpresaUsuario(session::get('id'));
				for($i=0;$i < count($emp);$i++)
				{
					$emp[$i]['condicion_empresa']=0;
					if($emp[$i]['id']==$id)
					{
						$this->_empresa->activarEmpresaUsuario(session::get('id'),$id);
						$emp[$i]['condicion_empresa']=1;
					}
				}
			}

                session::set('actEmp',$emp);
                session::set('empresa',$emp[0]['id']);
        }else
                session::set('actEmp',array());


        $datosPrograma = $this->_programa->cargarContDashboard(2);   
        $dp = json_decode($datosPrograma[0]['contadores'],true);
        //print_r($dp);
        $this->_view->contPrg = $dp;


        $this->_view->setJs(['pieChart']);
        $this->_view->titulo = "Bienvenido Usuario,".session::get('alias');

        if((session::get('id_usuario')==2) || (session::get('id_usuario')==14))
            $this->_view->renderizar("dashboart");
        else
            $this->_view->renderizar("index");
    }


    public function cargarDashboard()
    {
        echo json_encode($this->_programa->cargarDatosDashboard(validate::getPostParam('value')));
    }



}
