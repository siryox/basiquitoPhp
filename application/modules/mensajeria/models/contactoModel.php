<?php
class contactoModel extends model
{
    private $_db_remota;
    public function __construct()
    {
        $this->_db_remota = new database("bw.iot-ve.com","bw_ve_sicca","bw_bduser","rHP2023*#152!wBd");
    }


    public function getContacto()
    {
        $sql ="select * from contactos";

        $res = $this->_db_remota->query($sql);
        if($res){
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res->fetchAll();
        }else
            {
                $error =$this->_db->getError();
                logger::errorLog($error['2'],'DB');           
                return array();	
            }

    }

}