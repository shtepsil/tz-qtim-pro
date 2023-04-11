<?php

namespace app\exceptions;

class JwtException extends Exception
{
    /**
     * @param string $message
     * @param string $title
     */
    public function __construct($message = 'Ошибка токена', $title = 'TokenException')
    {
        $this->title = $title;
        parent::__construct($message);
    }
}