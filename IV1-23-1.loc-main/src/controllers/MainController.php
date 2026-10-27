<?php

namespace src\controllers;

class MainController extends Controller
{

    public function main()
    {
       $this->view->renderHtml('main/main.php' );
    }
    public function sayHello($name)
    {
        echo 'Привет, ' . $name;
    }
}