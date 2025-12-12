<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800,
    'path' => '/',
    'httponly' => true
]);

session_start();

if (!isset($_SESSION["session_last_regeneration"])) {
    $_SESSION["session_last_regeneration"] = time();
}
else {
    $interval = 60 * 30;
    if (time() - $_SESSION['session_last_regeneration'] >= $interval) {
        regenerateSessionId(); 
    }
}

function regenerateSessionId() {
    session_regenerate_id(true);
    $_SESSION["session_last_regeneration"] = time();
}