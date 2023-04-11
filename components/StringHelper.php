<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 27.10.2021
 * Time: 11:29
 */

namespace components;

class StringHelper
{

    public static function getPartStrByCharacter($url, $haracter, $code = false)
    {

        switch ($code) {
            case 'start':
                $pos = strpos($url, $haracter);
                if ($pos != '')
                    $str = substr($url, 0, $pos);
                else
                    $str = $url;
                break;
            case 'last':
                $pos = mb_strripos($url, $haracter);
                if ($pos != '')
                    $str = substr($url, 0, $pos);
                else
                    $str = $url;
                break;
            case 'all_from_first':
                $pos = strpos($url, $haracter);
                if ($pos != '')
                    $str = substr($url, $pos + 1);
                else
                    $str = $url;
                break;
            default:
                $revstr = strrev($url);
                $position = strpos($revstr, $haracter);
                $str_itog_rev = substr($revstr, 0, $position);
                $str = strrev($str_itog_rev);
        }

        return $str;

    } // function getPartStrByCharacter(...)

    /**
     * Обезопасиваем данные
     * Для обработки строки вторым аргументом нужно передать false
     * @param $data
     * @param bool $array
     * @return mixed|string
     */
    public static function secureEncode($data, $array = true)
    {

        // По умолчанию обрабатывается массив
        if (!$array) {

            // если нужно обработать строку
            $data = trim($data);
            //            $data = htmlspecialchars($data, ENT_QUOTES);
            $data = htmlspecialchars($data, ENT_NOQUOTES);
            $data = addslashes($data);
            $data = str_replace('\\r\\n', '<br>', $data);
            $data = str_replace('\\r', '<br>', $data);
            $data = str_replace('\\n\\n', '<br><br>', $data);
            $data = str_replace('\\n\\n\\n', '<br><br><br>', $data);
            $data = str_replace('\\n', '<br>', $data);
            $data = stripslashes($data);
            $data = str_replace('&amp;#', '&#', $data);
            $data = str_replace('&amp;', '&', $data);
        } else {
            // если нужно обработать массив
            $response_array = array();
            foreach ($data as $key => $value) {
                /*
                 * Если массив многомерный
                 * и в рекурсии вместо строки пришел массив
                 */
                if (is_array($value))
                    $response_array[$key] = self::secureEncode($value);
                else
                    $response_array[$key] = self::secureEncode($value, false);
            }
            $data = $response_array;
        }

        return $data;

    } // function secureEncode(...)

} //Class