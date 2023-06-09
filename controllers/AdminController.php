<?php

namespace controllers;

use components\Debugger as d;
use app\Request;
use controllers\MainController;
use app\View;
use models\Articles;

class AdminController extends MainController
{

    /**
     * @return void
     * @throws \app\exceptions\DbException
     */
    public function actionIndex()
    {
        $data = ['articles' => Articles::findAll(0, 3)];
        View::render('admin/index', $data);
    }

    /**
     * @return void
     */
    public function actionEdit()
    {
        $data = [];
        $id = Request::get('id');
        if ($id) {
            $data['article'] = Articles::findById(Request::get('id'));
        }
        View::render('admin/edit', $data);
    }

} //Class