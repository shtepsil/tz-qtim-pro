<?php

namespace controllers;

use app\exceptions\DbException;
use app\View;
use components\Debugger as d;
use models\Articles;
use app\Request;

class ArticlesController extends Controller
{

    /**
     * @return int[]
     * @throws DbException
     */
    public function actionSave()
    {
        /*
         * Может быть получаем POST из какого нибудь спецкласса
         * который обрабатываем
         */
        $post = Request::post();
        //        d::ajax($post);
        if (Request::isAjax()) {
            $data = ['status' => 401];
            if (!isset($post['id'])) {
                $result = Articles::create($post['title'], $post['text']);
                if ($result) {
                    $data['status'] = 200;
                    $data['id'] = $result;
                }
            } else {
                $result = Articles::updateById($post['id'], $post['title'], $post['text']);
                if ($result) {
                    $data['status'] = 200;
                }
            }

            Request::response($data);
        }
    }

    /**
     * @return void
     * @throws DbException
     */
    public function actionGet()
    {
        $data = ['status' => 401];
        $post = Request::post();
        if (Request::isAjax() and isset($post['start'])) {
            $start = $post['start'];
            $limit = 3;
            $articles = Articles::findAll($start, $limit);
            if ($articles) {
                $data['art_items'] = '';
                foreach ($articles as $article) {
                    $data['art_items'] .= View::getContent(
                        $post['type'] . '/shortcodes/article',
                        [
                            'article' => $article
                        ]
                    );
                }
                if (count($articles) < $limit) {
                    $data['end'] = true;
                }
            }

            if ($articles) {
                $data['status'] = 200;
            }
            Request::response($data);
        }
    }

    /**
     * @return void
     * @throws DbException
     */
    public function actionDelete()
    {
        $data = ['status' => 401];
        $post = Request::post();
        if (Request::isAjax() and isset($post['id']) and is_numeric($post['id'])) {
            $result = Articles::deleteById($post['id']);
            if ($result) {
                $data = ['status' => 200];
            }
        }
        Request::response($data);
    }

} //Class