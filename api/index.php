<?php

require_once "./connection.php";
require_once "./place.php";
require_once "./functions.php";

$requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

switch ($requestMethod) {
    case 'get':

        $place = new Place();
        $data = $place::all();

        response([
            'status' => 200,
            'message' => "Ok",
            'data' => $data,
        ]);
        break;

    default:
        response([
            'status' => 200,
            'message' => "No Content",
            'data' => [],
        ]);
        break;
}
