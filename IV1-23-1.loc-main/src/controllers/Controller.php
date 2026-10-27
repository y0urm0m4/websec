<?php

namespace src\controllers;
use src\views\View;
use src\models\UsersAuthService;
use src\services\CsrfService;
class Controller
{
    public $view;
    protected $user;
    public $layout = 'default';
    public function __construct()
    {
        $this->user = UsersAuthService::getUserByToken();
        $this->view = new View($this->layout);
        $this->view->setVar('user', $this->user);
        $this->view->setVar('csrfToken', CsrfService::getToken());
    }
}