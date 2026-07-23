<?php
$routes = [
    "/" => "info.php",
    "/posts/:postId" => "post.php",
];

function get_clean_url(): string
{
    $incoming_url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $incoming_url = preg_replace('/(^\/index\.php|\/$)/i', "", $incoming_url);

    return $incoming_url == "" ? "/" : $incoming_url;
}

function extract_params(string $path, array $params): void
{
    global $_PARAM;
    $_PARAM = [];

    preg_match_all("/\/:(\w+)/", $path, $param_regex);
    for ($i = 1; $i < count($param_regex); $i++) {
        $key = $param_regex[$i][0];
        $val = $params[$i][0];
        $_PARAM[$key] = $val;
    }
}

function find_match_route_handler(array $routes): string
{
    global $_PARAM;
    $_PARAM = [];
    $current = get_clean_url();

    foreach ($routes as $path => $handler) {
        $path_regex = preg_replace("/\//", "\/", $path);
        $path_regex =
            "/^" . preg_replace("/:\w+/", "([^\/]+)", $path_regex) . "$/";

        $match = preg_match_all(
            $path_regex,
            $current,
            $params,
            PREG_PATTERN_ORDER,
        );

        if (!$match) {
            continue;
        }

        extract_params($path, $params);
        return $handler;
    }

    return "";
}

$handler = find_match_route_handler($routes);
if ($handler == "") {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
    require_once __DIR__ . "/handler/404.php";

    die();
}

require_once __DIR__ . "/handler/" . $handler;
