<?php

namespace app\exceptions;

class DbException extends Exception
{

    /**
     * @param $message
     */
    public function __construct($message = 'Ошибка Db')
    {
        $this->title = 'DbException';
        parent::__construct($message);
    }

} //Class