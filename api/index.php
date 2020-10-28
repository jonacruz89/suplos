<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

require_once "./connection.php";
require_once "./place.php";
require_once "./functions.php";

$requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
$uri = strtolower($_SERVER['REQUEST_URI']);

switch ($uri) {
    case '/':

        $place = new Place();
        $data = $place::all();

        response([
            'status' => 200,
            'message' => "Ok",
            'data' => $data,
        ]);
        break;

    case '/city':

        $place = new Place();
        $data = $place->query("SELECT DISTINCT city FROM places ORDER BY 1");

        response([
            'status' => 200,
            'message' => "Ok",
            'data' => $data,
        ]);
        break;

    case '/type':

        $place = new Place();
        $data = $place->query("SELECT DISTINCT `type` FROM places ORDER BY 1");

        response([
            'status' => 200,
            'message' => "Ok",
            'data' => $data,
        ]);
        break;

    default:
        response([
            'status' => 404,
            'message' => "Method Not Found",
            'data' => [],
        ]);
        break;
}
