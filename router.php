<?php

$routes = [
    "/" => "info.php",
    "/posts/:postId" => "post.php",
];

function get_clean_url()
{
    $incoming_url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $incoming_url = preg_replace('/(^\/index\.php|\/$)/i', "", $incoming_url);

    return $incoming_url == "" ? "/" : $incoming_url;
}

function find_match_route($routes)
{
    global $_PARAM;
    $_PARAM = array();
    $current = get_clean_url();

    foreach ($routes as $path => $handler) {
        $path_regex = preg_replace("/\//", "\/", $path);
        $path_regex = "/^" . preg_replace("/:\w+/", "([^\/]+)", $path_regex) . "$/";

        $match = preg_match_all(
            $path_regex,
            $current,
            $params,
            PREG_PATTERN_ORDER,
        );
        if (!$match) continue;

        preg_match_all("/\/:(\w+)/", $path, $param_regex);
        for ($i = 1; $i < count($param_regex); $i++)
        {
            $key = $param_regex[$i][0];
            $val = $params[$i][0];
            $_PARAM[$key] = $val;
        }

        return $handler;
    }

    return '';
}

$handler = find_match_route($routes);
if ($handler == "") {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
    include "./handler/404.php";

    die;
}

include "./handler/" . $handler;
