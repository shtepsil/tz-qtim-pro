<?php

namespace app\exceptions;

class AuthException extends Exception
{

    /**
     * @param $message
     */
    public function __construct($message = 'Ошибка Auth')
    {
        $this->title = 'AuthException';
        parent::__construct($message);
    }

} //Class