<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../../')->load();

$router = new AltoRouter();

// Définition du BaseUri si il existe
if (array_key_exists('BASE_URI', $_SERVER)) {
    $router->setBasePath($_SERVER['BASE_URI']);
} else {
    $_SERVER['BASE_URI'] = '';
}

// Définition des routes
require __DIR__ . '/router.php';

//Dispatcher
$match = $router->match();

if ($match) {
    $controllerToUse = '\Runo\Controllers\\' . $match['target']['controller'];
    $methodToUse = $match['target']['action'];
    $controller = new $controllerToUse();
    if ($match['params'] && $match['params']['id'] && count($match['params']) === 1) {
        $controller->$methodToUse($match['params']['id']);
    } else {
        $controller->$methodToUse($match['params']);
    }
} else {
    //TODO: Créer un controller Error
    echo 'PAGE NON TROUVEE';
}