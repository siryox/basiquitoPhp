<?php
final class programaModel extends model
{
    public function __construct()
    {
        parent::__construct('prog_financ');
    }

    public function cargarPrgfinanc($parameters = false)
    {
        if(!$parameters)
            $parameters = '{"action":"search all"}';

        $sql = $this->_dbi->sqliQuery("CALL gest_prog_financ('$parameters')");      
        return $sql;    
        
    }


    public function guardarPrograma($parameters)
    {
        $sql = $this->_dbi->spExec("CALL gest_prog_financ(?)","$parameters");   
              
        if($sql)
            return true;
        else
            return false;
    }


    public function generarCuotasProgram($parameters)
    {
        $sql = $this->_dbi->spExec("CALL crearGestTecAdm(?)","$parameters");   
              
        if($sql)
            return true;
        else
            return false;
    }



    public function eliminarPrograma($parameters)
    {
        $sql = $this->_dbi->spExec("CALL gest_prog_financ(?)","$parameters");   
              
        if($sql)
            return true;
        else
            return false;
    }

    public function cargarRubros()
    {
        $sql = "select * from vRubros";
        $res = $this->_db->sqlQuery($sql);

        return $res;

    }

    public function cargarCxc($pf)
    {
        $sql = "select vCxc.*,vCreditos.RazonSocial from vCxc join vCreditos on vCxc.idCredito = vCreditos.id where  concepto like '%GESTION TECNICO- ADMINISTRATIVO S/CONTRATO%' and vCreditos.idProgFinanc='$pf' order by idCredito ";
        $res = $this->_db->sqlQuery($sql);

        return $res;

    }

    public function cargarCiclos()
    {
        $sql = "select * from vCiclosSiembra";
        $res = $this->_db->sqlQuery($sql);

        return $res;

    }
    

    public function cargarMonedas()
    {
        $sql = "select * from vMonedas";
        $res = $this->_db->sqlQuery($sql);

        return $res;

    }
    //----------------------------------------------------------------------------------
    //retorna el resultado de la ultima consulta
    //----------------------------------------------------------------------------------
    public function getResult()
    {
        return $this->_dbi->getLastResult();
    }


    //--------------------------------------------------------------------------------
    //funcion que retorna contadores para dashboart de nivel de programa de financiamiento
    //
    public function cargarContDashboard($id)
    {
        $sql = $this->_dbi->sqliQuery("SELECT prep_graf_conta('$id') as contadores");      
        return $sql;
    }

    //--------------------------------------------------------------------------------
    //funcion que retorna contadores para dashboart de nivel de programa de financiamiento
    //--------------------------------------------------------------------------------
    public function cargarDatosDashboard($id)
    {
        $sql = $this->_dbi->sqliQuery("select prep_graf_program($id) as datos");      
        return $sql;
    }
    



}
