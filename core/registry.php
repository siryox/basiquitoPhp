<?php
/*
 * Esta es una versión mejorada de la clase Registry.
 * Implementa el patrón Singleton para asegurar una única instancia
 * y la Carga Perezosa (Lazy Loading) para los objetos.
 */
class registry
{
	private static $_instancia;
	private $_data = []; // Almacenará las "recetas" (nombres de clases)
    private $_instances = []; // Almacenará los objetos ya instanciados
	
	// no se puede instanciar(para que solo se instancie dentro de la clase)
	private function __construct()
	{
	}
	
	/**
     * Obtiene la única instancia del Registry (Patrón Singleton).
     * @return registry
     */
	public static function getInstancia()
	{
		if(!self::$_instancia instanceof self)
		{
			self::$_instancia = new self();
		}
		
		return self::$_instancia;
	}
	
	/**
     * Método mágico para registrar una "receta" para un objeto.
     * Ejemplo: $registry->request = 'request';
     * @param string $name El alias del objeto (ej: 'db')
     * @param string $value El nombre de la clase a instanciar (ej: 'database')
     */
	public function __set($name, $value)
	{
		// Solo guardamos el nombre de la clase, no la instanciamos aún.
		$this->_data[$name] = $value;
	}
	
	/**
     * Método mágico para obtener un objeto. Aquí ocurre la carga perezosa.
     * Ejemplo: $db = $registry->db;
     * @param string $name El alias del objeto solicitado.
     * @return object|null La instancia del objeto.
     */
	public function __get($name)
	{
		// 1. Si ya lo creamos antes, simplemente lo devolvemos.
		if (isset($this->_instances[$name])) {
            return $this->_instances[$name];
        }

		// 2. Si no existe, pero tenemos la "receta", lo creamos ahora.
		if(isset($this->_data[$name]))
		{
			$className = $this->_data[$name];
			$this->_instances[$name] = new $className();
            return $this->_instances[$name];
		}
		return null;
	}

	// Previene la clonación de la instancia.
    public function __clone() {
        trigger_error('La clonación de esta clase no está permitida.', E_USER_ERROR);
    }
}