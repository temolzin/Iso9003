<?php 
class Conexion{
	private $conexion;
	private static $instancia;
	private static $instanciaSucursal;
	private $arrayConsulta = array();

	//Constructor privado para crear el patrón SINGLETON
	private function __construct() {
		try{
			$this->conectar();
		}catch(PDOException $e){
			throw new PDOException("Error al conectar a la base de datos " . $e);
		}
	}
	
	public static function getInstance() {
		if(!isset(self::$instacia)) {
			self::$instancia = new Conexion();
		}
		return self::$instancia;
	}

	private function conectar() {
		try{
			if (!defined('SERVIDOR')) define('SERVIDOR', 'localhost');
			if (!defined('BASE')) define('BASE', 'CalidadSoftware');
			if (!defined('USUARIO')) define('USUARIO', 'root');
			if (!defined('PASSWORD')) define('PASSWORD', '');
			$this->conexion = new PDO("mysql:host=".SERVIDOR.";dbname=".BASE, USUARIO, PASSWORD);
			$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			throw new PDOException("Error al conectar a la base de datos " . $e);
		}
	}

	/**
	 * Metodo para hacer una consulta a la base de datos
	*/
	public function consultar($consulta) {
		$consul = $this->conexion->prepare($consulta);
		$consul->execute();
		if ($consul->rowCount() != 0) {
			while ($reg = $consul->fetch()) {
				$arrayConsulta[] = $reg;
			}
		} else {
			$arrayConsulta = null;
		}
		return $arrayConsulta;
	}

	//Método que ejecuta un insert, update y delete, según lo requiera.
	public function ejecutarAccion($accion, $valores = array()) { 
		$resultado = false;
		if($statement = $this->conexion->prepare($accion)) {  //prepara la consulta
			if(preg_match_all("/(:\w+)/", $accion, $campo, PREG_PATTERN_ORDER)){ //tomo los nombres de los campos iniciados con :xxxxx
				$campo = array_pop($campo); //isnerto en un arreglo
				foreach($campo as $parametro){
					$statement->bindValue($parametro, $valores[$parametro]);
				}
			}
			try {
				if (!$statement->execute()) {
					$resultado = false; //si no se ejecuta la consulta...
					print_r($statement->errorInfo()); //imprimir errores
				} else {
					$resultado = true;
				}
				//$resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
				$statement->closeCursor();
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}	
		}
		return $resultado;
	} 

	/*
	Estadía
	*/
	public function ejecutarAccionByConex($conexAccion, $accion, $valores = array()){
		$resultado = false;
		if($statement = $conexAccion->prepare($accion)) {  //prepara la consulta
			if(preg_match_all("/(:\w+)/", $accion, $campo, PREG_PATTERN_ORDER)){ //tomo los nombres de los campos iniciados con :xxxxx
				$campo = array_pop($campo); //isnerto en un arreglo
				foreach($campo as $parametro){
					$statement->bindValue($parametro, $valores[$parametro]);
				}
			}
			try {
				if (!$statement->execute()) {
					$resultado = false; //si no se ejecuta la consulta...
					print_r($statement->errorInfo()); //imprimir errores
				} else {
					$resultado = true;
				}
				//$resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
				$statement->closeCursor();
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}	
		}
		return $resultado;


	}

	/**
	* Método que termina(Cierra) la conexión a la base de datos.
	*/
	function cerrarConex(){
        $this->conexion = null;
    }
}
?>