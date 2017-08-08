<?php

namespace ToDo\Helpers;
use ToDo\Models\AuthModel;

/**
 * @property \PDO                        $db
 * @property array                       $config
 * @property \Twig_Environment           $twig
 */
class Container
{
    /** @var self */
    private static $instance;

    /** @var array */
    protected $services = [];

    protected function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $name
     * @param mixed $data
     *
     * @return bool
     */
    public function __set($name, $data)
    {
        if (is_null($this->{$name})) {
            $this->services[$name] = $data;

            return true;
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        $service = "";

        if (!isset($this->services[$name])) {
            switch ($name) {
                case 'db': {
                    $service = new \PDO('mysql:host=localhost;dbname=tasksman_todo', 'tasksman_root', '123456', [\PDO::FETCH_ASSOC => true]);
                    $service->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                } break;

                case 'config': {
                    $service = [];
                } break;

                case 'twig': {
                    $loader = new \Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'] . "/views");
                    $service = new \Twig_Environment($loader);
                    $service->addGlobal('notifications', $_SESSION['notifications'] ?? []);
                    $service->addGlobal('is_logged_in', AuthModel::isLoggedIn());
                } break;
            }

            if (!empty($service)) {
                $this->services[$name] = $service;
            } else {
                throw new \Exception("Undefined service name '{$name}'");
            }
        }

        return $this->services[$name];
    }

    private function __clone() {}
    private function __wakeup() {}
}
