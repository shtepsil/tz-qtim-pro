<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 25.10.2021
 * Time: 10:53
 */
namespace app;

use components\Debugger as d;

class Request
{

    /**
     * @return array
     */
    public function request()
    {

        $request = [];
        $uri = self::getUri();
        $level_pos = strripos($uri, '?');
        if ($level_pos !== false) {
            $request['url'] = explode('/', trim(substr($uri, 0, $level_pos), '/'));
        } else {
            if ($uri)
                $request['url'] = explode('/', trim($uri, '/'));
            else
                $request['url'] = [];
        }
        $get_pos = strpos($uri, '?');
        if ($get_pos !== false)
            $get_query = substr($uri, ($get_pos + 1), 1);
        else
            $get_query = false;
        if ($get_query) {
            $str_get = substr($uri, ($get_pos + 1));
            if ($str_get) {
                $get = explode('&', $str_get);
                $arr_get = [];
                foreach ($get as $item) {
                    $param = explode('=', $item);
                    if ($param[0] == '')
                        continue;
                    $arr_get[$param[0]] = $param[1];
                }
                $request['get'] = $arr_get;
            }
        }
        return $request;
    }

    /**
     * @return string
     */
    public static function getUri()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    /**
     * @return bool
     */
    public static function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    /**
     * @param $data
     */
    public static function response($data)
    {
        header('Content-type: text/json');
        header("Content-type: application/json");
        echo json_encode($data, 256);
        exit();
    }

    public static function post($key = NULL)
    {
        $_post = self::serializeToArray($_POST);
        $data = array_merge($_POST, $_post);

        if ($key and is_string($key)) {
            $data = $_POST[$key] ?? NULL;
        }
        return $data;
    }

    public static function get($key = NULL)
    {
        $_post = self::serializeToArray($_GET);
        $data = array_merge($_GET, $_post);

        if ($key and is_string($key)) {
            $data = $_GET[$key] ?? NULL;
        }
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function serializeToArray($data = [])
    {
        $array = [];
        if (is_array($data) and count($data)) {
            foreach ($data as $name => $value) {
                if ($value == 'on') {
                    $array[$name] = true;
                } else {
                    $array[$name] = $value;
                }
            }
        }
        return $array;
    }

} // Class