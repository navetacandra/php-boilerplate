<?php
$routes = [];

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

function find_match_route_handler(): array|null
{
    global $routes;
    $current = get_clean_url();

    if (isset($routes[$current])) {
        return $routes[$current];
    }

    foreach ($routes as $path => $info) {
        $match = preg_match_all(
            $info["pattern"],
            $current,
            $params,
            PREG_PATTERN_ORDER,
        );

        if ($match) {
            if (count($params) > 1) {
                extract_params($path, $params);
            }
            return $info;
        }
    }

    return null;
}

function add_route(
    string $method = "GET",
    string $path,
    string $handler_file_name,
): void {
    global $routes;
    $pattern = str_replace("/", "\/", $path);
    $pattern = preg_replace("/:\w+/", "([^\/]+)", $pattern);

    if (!isset($routes[$path])) {
        $routes[$path] = [
            $method => $handler_file_name,
            "pattern" => "/^{$pattern}$/i",
        ];
        return;
    }

    $routes[$path][$method] = $handler_file_name;
}

function route_resolve(): void
{
    $handler_info = find_match_route_handler();
    if ($handler_info == null) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
        require_once __DIR__ . "/handler/404.php";

        die();
    }

    // Unregistered method handler
    $method = $_SERVER["REQUEST_METHOD"];
    if (!isset($handler_info[$method])) {
        header(
            $_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed",
            true,
            405,
        );
        die();
    }

    require_once __DIR__ . "/handler/" . $handler_info[$method];
}
