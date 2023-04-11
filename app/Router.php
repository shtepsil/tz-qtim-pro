<?php

namespace app;

use components\Debugger as d;
use app\Request;
use app\exceptions\RouterException;
use components\StringHelper;
use models\User;

class Router
{

    private $routes;

    public function __construct()
    {
        $this->routes = Main::$a->params['routes'];
    }

    /**
     * @return void
     * @throws RouterException
     */
    public function init()
    {
        $uri = Request::getUri();
        $uri = StringHelper::getPartStrByCharacter($uri, '?', 'start');
        if (!array_key_exists($uri, $this->routes)) {
            throw new RouterException('Маршрут ' . $uri . ' не найден!');
        }
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~^$uriPattern$~", $uri)) {
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                $internalRoute = Router::routeManager($internalRoute);
                $segments = explode('/', $internalRoute);
                $controllerName = ucfirst(array_shift($segments)) . 'Controller';
                $actionName = 'action' . ucfirst(array_shift($segments));
                $parameters = $segments;
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                $controllerName = '\\controllers\\' . $controllerName;
                $controllerObject = new $controllerName;
                $controllerObject->beforeAction();
                $result = @call_user_func_array(array($controllerObject, $actionName), $parameters);
                if ($result != null) {
                    break;
                }

            }
        }
    }

    public static function routeManager($route)
    {
        $open_routes = [
            'user/register',
            'site/articles',
            'site/index',
        ];
        if (!User::checkAuth()) {
            if (!in_array($route, $open_routes)){
                $route = 'user/login';
            }
        }
        return $route;
    }

} //Class
