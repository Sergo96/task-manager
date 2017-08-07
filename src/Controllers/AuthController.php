<?php

namespace ToDo\Controllers;

use Pecee\SimpleRouter\SimpleRouter;
use ToDo\Helpers\Base;
use ToDo\Models\AuthModel;

/**
 * @property AuthModel model
 */
class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = new AuthModel($this->container->db);
    }

    public function loginPageAction() : void
    {
        $this->container->twig->display('login.html.twig');
    }

    public function login()
    {
        $params = SimpleRouter::request()->getInput()->post;

        $result = $this->model->login($params['login']->value, $params['password']->value);
        $redirect_to = $result ? '/' : '/login/';
        $_SESSION['notifications'] = $this->model->getNotifications();

        Base::redirectTo($redirect_to);
    }
}
