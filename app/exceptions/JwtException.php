<?php

namespace app\exceptions;

class JwtException extends Exception
{
    /**
     * RouterException constructor.
     * @param string $message
     */
    public function __construct($message = 'Ошибка токена', $title = 'TokenException')
    {
        $this->title = $title;
        parent::__construct($message);
    }
}