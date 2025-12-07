<?php
// clase padre para conexion a base de datos Mysql extendiendo de pdo 
class dmi extends mysqli
{
    private $_last_query_result;
    public function __construct() {
        

        parent::__construct('p:'.DB_HOST,DB_USER,DB_PASS,DB_NAME);

        if (mysqli_connect_error()) {
            die('Error de Conexión (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        $this->_last_query_result ="";
        $this->set_charset("utf8");

    }
	public function getInsertedId(){
		return $this->insert_id;
	}

	public function getError(){
		return $this->error;
	} 
           
        // Method that starts transaction in MYSQL  
        public function start()
        {
            $this->begin_transaction(); 
        }
        
        
        // Method that confirms transaction in MYSQL 
        public function confirm()
        {
            $this->commit();
        }
       
        // Method that cancels transaction in MYSQL 
        public function cancel()
        {
            $this->rollBack();
        }   
	
    
        public function sqliQuery($sql)
        {
            $res = $this->query($sql);
            if($res->num_rows >0)
            {
                while ($fila = $res->fetch_assoc()) {
                    $data[] = $fila;
                }    
                $res->free_result();
                $this->next_result();
               return $data;
            }else
                {
                    $res->free_result();
                    $this->next_result();
                    return array();   
                }
                
        }
        
        
        //---------------------------------------
        //metodo para ejecutar sp tipo insercion 
        //mediante sentencias preparadas
        //------------------------------------------
        public function spExec($sql,$valor)
        {
            
            if($res = $this->prepare($sql))
            {
                $res->bind_param("s",$valor);  
                
                
                $res->execute();

                $res->bind_result($response);
                $res->fetch();

                $res->close();

                $this->_last_query_result = $response;
                $result=json_decode($response,true);
                if($result['status']=='success')
                {
                    return true;   
                }else
                    return false;
                
            }
            


        }


        public function spExecJs($sql,$v1,$v2=false)
        {
            
            if($res = $this->prepare($sql))
            {
                if($v2)
                {
                    $res->bind_param("ss",$v1,$v2);
                    
                }else
                    {
                        $res->bind_param("s",$v1);
                    }
                
                
                $res->execute();

                $res->bind_result($response);
                $res->fetch();

                $res->close();

                $result=json_decode($response,true);
                $this->_last_query_result = $result; 
                if($result['status']=='success')
                {
                    return true;   
                }else
                    {
                        $this->storeLog($this->_last_query_result['response']);
                        return false;
                    }
                
            }
            


        }

        public function getLastResult()
        {
            return $this->_last_query_result;
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

           
}

