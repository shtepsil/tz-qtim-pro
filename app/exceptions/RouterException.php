<?php

namespace app\exceptions;

class RouterException extends Exception
{
    /**
     * @param string $message
     * @param string $title
     */
    public function __construct($message = 'Ошибка Router', $title = 'RouterException')
    {
        $this->title = $title;
        parent::__construct($message);
    }
}