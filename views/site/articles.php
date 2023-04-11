<?php

use components\Debugger as d;
use app\Request;
use app\View;

//d::pri($articles);

?>
<section class="main w-articles">
    <div class="container">
        <div class="row">
            <? if (!Request::get('id')): ?>
                <? if (isset($articles) and is_array($articles) and count($articles) > 0): ?>
                    <div class="col-md-12">
                        <div class="articles-list">
                            <? foreach ($articles as $article): ?>
                                <? $hover = ''; // $hover = 'hover'; ?>
                                <?= View::getContent('site/shortcodes/article', [
                                    'article' => $article
                                ]) ?>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="articles-get-more" data-type="site">
                            <img src="/template/images/animate/loading.gif" class="loading" />
                            <span>
                                <? if (isset($articles) and count($articles) > 0): ?>
                                    Показать ещё
                                <? else: ?>
                                    По пробовать загрузить стати...
                                <? endif; ?>
                            </span>
                        </button>
                    </div>
                    <? d::res() ?>
                <? else: ?>
                    <br><br>
                    <div class="h4 text-center">
                        <p>Новостей увы нет ((</p>
                        <p><a href="/gadaniya">Но пока что вы можете по гадать ))</a></p>
                    </div>
                <? endif ?>
            <? else: ?>
                <div class="col-md-12">
                    <div class="back-page bp-top">
                        <a href="/articles">
                            < < < К списку новостей</a>
                    </div>
                    <div class="article-title-item">
                        <?= $article['title'] ?>
                    </div>
                    <hr>
                    <div class="article-text-item">
                        <?= $article['text'] ?>
                    </div>
                    <div class="back-page bp-bottom">
                        <a href="/articles">
                            < < < К списку новостей</a>
                    </div>
                </div>
            <? endif ?>
        </div>
    </div>
</section>