<?php

namespace app;

use components\Debugger as d;

class Main
{

    public static $a;
    public $router;
    public $params;

    /**
     * @param $params
     */
    public function __construct($params = [])
    {
        // Настройка приложения
        static::$a = $this;
        $this->params = $params;
        $this->router = new Router();

    }

    /**
     * @return void
     * @throws exceptions\RouterException
     */
    public function run()
    {
        // Инициализация приложения
        $this->router->init();
    }

} //Class