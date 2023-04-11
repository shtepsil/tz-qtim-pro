<?php

namespace controllers;

use components\Debugger as d;
use app\Controller;
use app\View;

class MainController implements Controller
{

    public $title;
    public $error_text;

    public function actionError()
    {
        $this->title = '404';
        $this->error_text = 'Запрошенная страница не найдена';
        View::render('site/errors', ['context' => $this]);
    }

    public function beforeAction()
    {}


} //Class