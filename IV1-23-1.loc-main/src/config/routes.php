<?php

return [
    '~^articles/add$~' => [\src\controllers\ArticlesController::class,'add'],
    '~^articles$~' => [\src\controllers\ArticlesController::class,'index'],
    '~^article/(\d+)$~' => [\src\controllers\ArticlesController::class,'view'],
    '~^article/(\d+)/edit$~' => [\src\controllers\ArticlesController::class,'edit'],
    '~^article/(\d+)/delete$~' => [\src\controllers\ArticlesController::class,'delete'],
    '~^article/search$~' => [\src\controllers\ArticlesController::class,'search'],
    '~^user/register$~' => [\src\controllers\UsersController::class,'signUp'],
    '~^user/login$~' => [\src\controllers\UsersController::class,'login'],
    '~^user/logout$~' => [\src\controllers\UsersController::class,'logout'],
    '~^hello/(.*)$~' => [\src\controllers\MainController::class,'sayHello'],
    '~^$~' => [\src\controllers\MainController::class,'main'],
];