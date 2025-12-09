<?php
        ini_set('display_errors', 1);
        error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
        

        // define el separador de directorio
        define('DS', DIRECTORY_SEPARATOR);

        //define la ruta base
        define('ROOT',realpath(dirname(__FILE__)).DS);

        //define la ruta de la aplicaion
        define('APP_PATH',ROOT . 'application' . DS);
        //define la ruta del core
        define('CORE_PATH',ROOT . 'core' . DS);

        //define la ruta de las librerias externas
        define('LIB_PATH',APP_PATH. 'libs' . DS);

        //define la ruta de los log
        define('LOG_PATH',APP_PATH . 'log' . DS);

         //define la ruta de configuracion
        define('CONF_PATH',APP_PATH . 'config' . DS);
       
        
        try{  
            
            // 1. Cargar el autoloader de Composer. Será el único necesario.
            require_once ROOT. 'vendor/autoload.php';

            // 2. Cargar configuración esencial (como la de la BD)
            require_once CORE_PATH . 'config.php' ;	  		
           
            // 3. Obtener la instancia del Registry
            $registry = registry::getInstancia();
            
            // 4. Registrar las "recetas" de los componentes del núcleo (sin instanciar)
            $registry->request = 'request';
            $registry->db = 'database';
            $registry->dbi = 'dmi';
            $registry->acl = 'acl';
            
            // 5. Iniciar el bootstrap. Bootstrap ahora pedirá el request al registry.
            bootstrap::start($registry);
          
            }catch(errorsys $e)
                {
                    echo $e->errorMessage();
                }
		
			 
	 
	 
?>
