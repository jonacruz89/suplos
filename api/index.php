<?php

require_once "./functions.php";

$requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

switch ($requestMethod) {
    case 'get':
        response($requestMethod);
        break;

    default:
        response([
            'status' => 200,
            'message' => "No Content",
            'data' => [],
        ]);
        break;
}
