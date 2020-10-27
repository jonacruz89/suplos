<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once __DIR__ . '/Bienes.php';

$httpMethod = strtolower($_SERVER['REQUEST_METHOD']);
if ($httpMethod === 'post') {
	$action = null;
	$id = null;
	$data = $_POST;

	if (isset($data['action'])) {
		$action = $data['action'];
	}
	
	$bienes = new Bienes;
	if ($action === 'add') {
		if (!isset($data['id']) || !is_numeric($data['id'])) {
			die(json_encode([
				"code"=>"error",
				"message" => "Error Parameter",
			]));
		}
		
		$id = (int)$data['id'];
		$result = $bienes->add($id);
		if(is_null($result)) {
			die(json_encode([
				"code"=>"success",
				"message" => "Guardado con exito",
			]));
		}
	} else if ($action === 'read') {
		if (isset($data['city']) && !is_string($data['city'])) {
			die(json_encode([
				"code"=>"error",
				"message" => "Error Parameter",
			]));
		} else if (isset($data['type']) && !is_string($data['type'])) {
			die(json_encode([
				"code"=>"error",
				"message" => "Error Parameter",
			]));
		} else if (isset($data['range']) && !is_array($data['range'])) {
			if (sizeof($data['range']) !== 2) {
				die(json_encode([
					"code"=>"error",
					"message" => "Error Parameter",
				]));
			}
		}

		$city = $data['city'];
		$type = $data['type'];
		$range = $data['range'];

		if (!is_numeric($range[0]) || !is_numeric($range[1])) {
			die(json_encode([
				"code"=>"error",
				"message" => "Error Parameter",
			]));
		} else {
			$range[0] = (int)$range[0];
			$range[1] = (int)$range[1];
		}

		die(json_encode($bienes->read($city, $type, $range)));
	} else {
		die(json_encode([
			"code"=>"error",
			"message" => "Error Parameter",
		]));
	}
}


?>