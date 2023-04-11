<?php

namespace app\exceptions;

use app\View;

class Exception extends \Exception
{
    public $title;
    public $error_text;

    /**
     * @param $message
     */
    public function __construct($message = 'Ошибка Exception')
    {
        $this->error_text = $message;
        parent::__construct($message);
    }

    /**
     * @return string|void
     */
    public function __toString()
    {
        exit(View::getContent('site/errors', ['context' => $this]));
    }
}