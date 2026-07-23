<?php
// header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none';");
// header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), camera=(), microphone=()");
header_remove("X-Powered-By");

require_once __DIR__ . "/router.php";
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/database.php";
