<?php

?>
<div class="wrap-errors" style="text-align: center;min-height: 600px;">

    <br>
    <br>
    <br>
    <br>
    <br>
    <div style="font-size: 20px;">
        Специальная страница ошибок<br>
        <font color="#8b0000">views/site/error</font>
    </div>
    <h1>
        <?= $context->title ?>
    </h1>
    <div style="font-size: 20px;">
        <?= $context->error_text ?><br><br>
        <a href="/">На сайт</a>
    </div>

</div>