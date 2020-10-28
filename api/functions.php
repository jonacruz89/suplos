<?php

function dd($data)
{
    print_r('<pre>');
    print_r($data);
    print_r('</pre>');
    die();
}

function response($data)
{
    header("Content-Type: application/json; charset=UTF-8");

    $response = json_encode($data);
    echo $response;
    die();
}
