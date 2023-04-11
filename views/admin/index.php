<?php

use components\Debugger as d;
use app\View;

?>
<section class="main w-admin-articles">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Заголовок</th>
                            <th class="text-right">Операции</th>
                        </tr>
                    </thead>
                    <tbody class="articles-list">
                        <? if (isset($articles) and count($articles)):
                            foreach ($articles as $article): ?>
                                <?= View::getContent('admin/shortcodes/article', ['article' => $article]) ?>
                            <? endforeach; endif;
                        ?>
                    </tbody>
                </table>
                <div class="col-md-12">
                    <button type="button" class="articles-get-more" data-type="admin">
                        <img src="/template/images/animate/loading.gif" class="loading" />
                        <span>
                            <? if (isset($articles) and count($articles) > 0): ?>
                                Показать ещё
                            <? else: ?>
                                По пробовать загрузить стати...
                            <? endif; ?>
                        </span>
                    </button>
                    <? if (!isset($articles) or (isset($articles) and !count($articles))): ?>
                        <div class="text-center">
                            <br><br>
                            <a href="/admin/edit">
                                <button class="btn btn-primary">Написать новость</button>
                            </a>
                        </div>
                    <? endif ?>
                </div>
                <? d::res() ?>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-sm modal-confirm-article-delete" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">Удаление</h4>
                </div>
                <div class="modal-body">
                    Вы действительно хотите удалить статью?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Нет</button>
                    <button type="button" class="btn btn-danger article-delete" data-article-id="">
                        <img src="/template/images/animate/loading.gif" class="loading" />
                        Да
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</section>