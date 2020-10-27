<?php
require_once __DIR__ . '/Database.php';

define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'intelcost');
define('DB_USER', 'root');
define('DB_PASS', '');

class Bienes {
	public $db;
	public $dataFromJson;
	public $dataToPass;

	public function __construct() {
		$this->db = new Database(
			DB_TYPE,
			DB_HOST,
			DB_NAME,
			DB_USER,
			DB_PASS
		);
	}

    public function add($id) {
    	if($this->getJson()) {
			$dataId = $this->findId($id);
    		if($dataId) {
    			$ciudad = $this->db->select(
					"SELECT id_ciudad
					FROM ciudad
					WHERE nombre = '{$dataId['Ciudad']}'"
				);

    			$tipo = $this->db->select(
					"SELECT id_tipo
					FROM tipo
					WHERE nombre = '{$dataId['Tipo']}'"
				);

    			$dataId['Ciudad'] = $ciudad[0]['id_ciudad'];
    			$dataId['Tipo']   = $tipo[0]['id_tipo'];

    			$result = $this->db->insert('bienes', $dataId);
    			return $result;
			} else {
    			die(json_encode([
					"code"=>"error",
					"message" => "Sin Informacion",
				]));
			}
    	}
    	
    }

    public function read(?String $city = null, ?String $type = null, ?Array $priceRange = null) {
		$sql = "SELECT 
					a.id Id, a.direccion Direccion, b.nombre Ciudad,
					a.telefono Telefono, a.codigo_postal Codigo_Postal,
					c.nombre Tipo, a.precio Precio,
					CAST(
						SUBSTRING(
							REPLACE(a.precio, ',', ''),
							2
						)
						AS INTEGER
					) PrecioNoPrefix
				FROM bienes a
					JOIN ciudad b
						ON (a.ciudad = b.id_ciudad)
					JOIN tipo c
						ON (a.tipo = c.id_tipo)";

		$binding = [];

		$sqlWhere = '';
		if ($city) {
			$sqlWhere = ' WHERE b.nombre = :city';
			$binding[':city'] = $city;
		}

		if ($type) {
			if (!$sqlWhere) {
				$sqlWhere = ' WHERE ';
			} else {
				$sqlWhere .= ' AND ';
			}

			$sqlWhere .= 'c.nombre = :type';
			$binding[':type'] = $type;
		}

		if ($priceRange) {
			$min = $priceRange[0];
			$max = $priceRange[1];
			$sqlWhere .= " HAVING :rangeMin <= PrecioNoPrefix AND :rangeMax >= PrecioNoPrefix";
			$binding[':rangeMin'] = $min;
			$binding[':rangeMax'] = $max;
		}

		$sql = $sql . $sqlWhere;
    	return $this->db->select($sql, $binding);
    }

    public function getJson() {
    	$string = file_get_contents(__DIR__ . "/../data/data.json");
		if ($string === false) {
		    echo json_encode([
				"code"=>"error",
				"message" => "no se encontro archivo",
			]);
		}

		$this->dataFromJson = json_decode($string, true);
		if ($this->dataFromJson === null) {
		    echo json_encode([
				"code"=>"error",
				"message" => "Error al decodificar",
			]);
		}
		return true;
	}
	
    public function findId($id) {
    	foreach ($this->dataFromJson as $key => $value) {
    		if($id === $value['Id']){
    			return $value;
    		}
    	}
    	return null;
    }
}
?> 