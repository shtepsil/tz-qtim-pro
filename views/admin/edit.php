<?php

use components\Debugger as d;

$id = isset($article) ? $article['id'] : '';
$title = isset($article) ? $article['title'] : '';
$text = isset($article) ? $article['text'] : '';

?>
<style>
    #container {
        width: 1000px;
        margin: 20px auto;
    }

    .ck-editor__editable[role="textbox"] {
        /* editing area */
        min-height: 200px;
    }

    .ck-content .image {
        /* block images */
        max-width: 80%;
        margin: 20px auto;
    }
</style>
<section class="main w-admin-articles">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="back-page bp-top">
                    <a href="/admin">
                        < < < К списку новостей</a>&nbsp;&nbsp;&nbsp;
                            <a href="/admin/edit" class="btn btn-success btn-sm">Написать новость</a>
                </div>
            </div>
            <div class="col-md-12">
                <form action="/articles/save" method="POST">
                    <label for="title">Заголовок</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= $title ?>"><br>
                    <label for="ckeditor">Текст статьи</label>
                    <div id="ckeditor">
                        <?= $text ?>
                    </div>
                    <br>
                    <button type="submit" class="article-send" data-id="<?= $id ?>">
                        <img src="/template/images/animate/loading.gif" class="loading" />
                        Сохранить
                    </button>
                </form>
                <? d::res() ?>
            </div>
        </div>
    </div>
</section>