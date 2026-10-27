<?php
try {
    spl_autoload_register(function(string $className){
        require_once __DIR__.'/'.str_replace('\\','/',$className.'.php');
    });

    $route = $_GET['route'] ?? '';
    $routes = require __DIR__ . '/src/config/routes.php';

    $isRouteFound = false;
    foreach($routes as $pattern => $controllerAndAction){
        preg_match($pattern, $route, $matches);
        if(!empty($matches)){
            $isRouteFound = true;
            break;
        }
    }
    $controller = new \src\controllers\MainController();
    if(!$isRouteFound){
        throw new \src\exceptions\NotFoundException();
    }

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];
    unset($matches[0]);

    $controller = new $controllerName;
    $controller->$actionName(...$matches);
} catch (\src\exceptions\DbException $e) {
    $controller->view->renderHtml('errors/500.php', ['error' => $e->getMessage()], 500);
} catch (\src\exceptions\NotFoundException $e) {
    $controller->view->renderHtml('errors/404.php', ['error' => $e->getMessage()], 404);
} catch (\src\exceptions\UnauthorizedException $e) {
    $controller->view->renderHtml('errors/401.php', ['error' => $e->getMessage()], 401);
}