<?php
// Autor: Rafael Perez
// comment: class data model that provides functionality for operations with Mysql databases 
// Update 26/02/2019

class model
{
        protected $_table;
        private $_registry;// class 	
        protected $_db;
        protected $_dbi;

        private $_table_foreing;
                
        public function __construct($table)
        {
           $this->_registry = registry::getInstancia();
           $this->_db = $this->_registry->_db;	
           $this->_dbi = $this->_registry->_dbi;	
                      
           $this->_table = $table;
        }
        public function has_many($value)
        {
             $this->_table_foreing = $value;   
        }
                 
        public function list($parameters)
        {
                //$field = campos a consultar ['fields'=>"id,fecha,estatus"]
                $fields = (isset($parameters['fields']))?$parameters['fields']:'*';

                //$search = campos del where  ['id'=>'1','fecha'=>'01-01-2000','estatus'='activo']
                $search = (isset($parameters['search']))?$parameters['search']:null;   
                
                //$index = campos de ordenacion ['index'=>'id,fecha']
                $index  = (isset($parameters['index']))?$parameters['index']:null;


                $sql = 'SELECT ' . $fields . ' FROM '. $this->_table;

                if(is_array($search))
                {
                        ksort($search);

                        $searchDetails = "";
                        $i=1;
                        foreach ($search as $key => $value) {
                                $searchDetails .= "$key=:$key";
                                if($i < count($search))
                                {
                                        $searchDetails .=',';   
                                }
                                $i++;
                        }

                        $sql .= ' WHERE ' . $searchDetails;   
                }

                if(!is_null($index))
                {
                $sql .= ' ORDER BY  ' . $index;
                }


                ///die($sql);
                $sth = $this->_db->prepare($sql);

                foreach ($search as $key => $value) {
                       $sth->bindValue(":$key",$value);
                }
                        
                $res = $sth->execute();
                if(!$res)
                {
                        $error =$this->getError();
                        $this->storeLog($error['2'].' in table:'.$this->_table);
                }else
                     {
                        $response = array();
                        $data= $sth->fetchAll(PDO::FETCH_ASSOC);
                        if($this->_table_foreing != "") 
                        {
                              foreach($data as $row)
                              {
                                
                                $val = $row["id_".$this->_table];
                                $sql = "SELECT * FROM ".$this->_table_foreing." WHERE ".$this->_table."_id =:val";       
                                
                                $sth = $this->_db->prepare($sql);
                                $sth->bindValue(":val",$val);
                                $res = $sth->execute();
                                if(!$res)
                                {
                                        $error =$this->getError();
                                        $this->storeLog($error['2'].' in table:'.$this->_table_foreing);
                                }else
                                    {
                                        $data_foreing = $sth->fetchAll(PDO::FETCH_ASSOC);
                                        $reponse[]= [$this->_table=>$row,"has_many"=>[$this->_table_foreing=>$data_foreing]];
                                    }               

                              }  
                        }else
                             {
                                foreach($data as $row)
                                {
                                        $reponse[] = [$this->_table=>$row];
                                }        
                             }
                             
                             return $reponse;
                     }   
                               

        }


        public function create($parameters)
        {
                //$fields = valores a insertar  ['id'=>'1','fecha'=>'01-01-2000','estatus'='activo']
                $fields = $parameters['fields'];

                if(is_array($fields) && count($fields))
                {
                        $cont = count($fields);
                        $i = 0;
                        $fieldDetails = "";
                        $valueDetails = "";
                        foreach ($fields as $key => $value) {
                                $fieldDetails .= "$key";
                                $valueDetails .= ":$key";
                                if($i < ($cont -1))
                                {
                                        $fieldDetails .=',';
                                        $valueDetails .=',';
                                }
                                $i++;
                        }
                        $sql = "INSERT INTO $this->_table($fieldDetails)VALUES($valueDetails)";
                        //die($sql);
                       // $this->storeLog($sql);
                        $sth = $this->_db->prepare($sql);

                        ksort($fields);
                        foreach ($fields as $key => $value) {
                                $sth->bindValue(":$key",$value,PDO::PARAM_STR);
                        }

                        $res = $sth->execute();
                        if(!$res)
                        {
                        
                                $error =$this->_db->getError();
                                $this->storeLog($error['2'].' in table:'.$this->_table);
                                return false;
                        }else
                               
                                return true;

                        }
                        

        }
       
        public function update($parameters)
        {

                //$fields = valores a insertar  ['id'=>'1','fecha'=>'01-01-2000','estatus'='activo']
                $fields = (isset($parameters['fields']))?$parameters['fields']:'*';

                //$search = campos del where  ['id'=>'1','fecha'=>'01-01-2000','estatus'='activo']
                $search = (isset($parameters['search']))?$parameters['search']:null;  

                ksort($fields);

		$fieldDetails = "";
                $i=0;
		foreach ($fields as $key => $value) {
			$fieldDetails .= "$key=:$key";
                        if($i < (count($fields)-1))
                        {
                                $fieldDetails .= ',';
                        }
                        $i++;
		}


                ksort($search);
                $searchDetails = "";
                $j = 0;
                foreach ($search as $key => $value) {
                        $searchDetails .= "$key=$value";
                        if($j < (count($search)-1))
                        {
                                $searchDetails .= ',';  
                        }
                        $j++;

                }


                $sql = "UPDATE $this->_table SET $fieldDetails WHERE $searchDetails ";
                //die($sql);
                //$this->storeLog($sql);
                $sth = $this->_db->prepare($sql);

                foreach ($fields as $key => $value) {
			$sth->bindValue(":$key",$value);
		}

                
                $res = $sth->execute();
                if(!$res)
                {
                        $error =$this->_db->getError();
                        $this->storeLog($error['2'].' in table:'.$this->_table);
                        return false;
                }else
                        return true;

                


        }  
        
        
        public function delete($parameters)
        {
                //$search = campos del where  ['id'=>'1','fecha'=>'01-01-2000','estatus'='activo']
                $search = (isset($parameters['search']))?$parameters['search']:null; 

                ksort($search);

                $searchDetails = "";
                foreach ($search as $key => $value) {
                        $searchDetails .= "$key=:$key";
                }


                $sql = "DELETE  FROM $this->_table WHERE " . $searchDetails; 

                $sth = $this->_db->prepare($sql);

                foreach ($search as $key => $value) {
                        $sth->bindValue(":$key",$value);
                 }

                $res = $sth->execute();
                if(!$res)
                {
                        $error =$this->_db->getError();
                        $this->storeLog($error['2'].' in table:'.$this->_table);
                        return false;
                }else
                        return true;


        }

        public function search($parameters)
        {
                //$field = campos a consultar ['fields'=>"id,fecha,estatus"]
                $fields = (isset($parameters['fields']))?$parameters['fields']:'*';

                //$search = campos del where  ['id'=>'1','fecha'=>'01-01-2000','estatus'='activo']
                $search = (isset($parameters['search']))?$parameters['search']:null;   
                
                //$index = campos de ordenacion ['index'=>'id,fecha']
                $index  = (isset($parameters['index']))?$parameters['index']:null;


                $sql = 'SELECT ' . $fields . ' FROM '. $this->_table;

                if(is_array($search))
                {
                        ksort($search);

                        $searchDetails = "";
                        $i=1;
                        foreach ($search as $key => $value) {
                                $searchDetails .= "$key=:$key";
                                if($i < count($search))
                                {
                                        $searchDetails .=',';   
                                }
                                $i++;
                        }

                        $sql .= ' WHERE ' . $searchDetails;   
                }

                if(!is_null($index))
                {
                $sql .= ' ORDER BY  ' . $index;
                }


                ///die($sql);
                $sth = $this->_db->prepare($sql);

                foreach ($search as $key => $value) {
                       $sth->bindValue(":$key",$value);
                }
                        
                $res = $sth->execute();
                if(!$res)
                {
                        $error =$this->getError();
                        $this->storeLog($error['2'].' in table:'.$this->_table);
                }else
                     {
                        $response = array();
                        $data = $sth->fetch(PDO::FETCH_ASSOC);
                        if($this->_table_foreing != "") 
                        {
                              
                                
                                $val = $data["id_".$this->_table];
                                $sql = "SELECT * FROM ".$this->_table_foreing." WHERE ".$this->_table."_id =:val";       
                                
                                $sth = $this->_db->prepare($sql);
                                $sth->bindValue(":val",$val);
                                $res = $sth->execute();
                                if(!$res)
                                {
                                        $error =$this->getError();
                                        $this->storeLog($error['2'].' in table:'.$this->_table_foreing);
                                }else
                                    {
                                        $data_foreing = $sth->fetchAll(PDO::FETCH_ASSOC);
                                        $reponse = [$this->_table=>$data,"has_many"=>[$this->_table_foreing=>$data_foreing]];
                                    }               

                                
                        }else
                             {
                                
                                        $reponse = [$this->_table=>$row];        
                             }
                             
                             return $reponse;
                     }






        }


        public function storeLog($mensaje)
        {
                if($log = fopen(LOG_PATH."logDB.txt","a+"))
                {
                        if(!empty($mensaje))
                        {
                                fwrite($log, date("F j, Y, g:i a").'  '.$mensaje. chr(13));
                        }    
                        fclose($log);
                        return TRUE;
                }
        }

         
         public function sqlQuery($sql)
         {
             $res = $this->_db->query($sql);
             if($res)
             {
                 $res->setFetchMode(PDO::FETCH_ASSOC);
                 $data = $res->fetchAll();
                 if(count($data)>1)
                 {
                        return $data;
                 }else
                      {
                        if(count($data)==1)
                                return $data[0];
                        else
                                return array();    
                      }  
                        
             }else
                  {
                        $error =$this->_db->getError();
                        $this->storeLog($error['2']);                       
                        return false;
                  }      
                 
         }
         public function sqlExec($sql)
         {
                $res = $this->_db->exec($sql);
                if(!$res)
                {
                        $error =$this->_db->getError();
                        $this->storeLog($error['2']);                       
                        return false;
                }else
                    return true; 

         }      
 
        
        	
}

?>