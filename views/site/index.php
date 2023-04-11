<?php

use components\Debugger as d;

?>
<div class="geks-go-error dn"
    style="position:fixed; top: 40px;right: 10px;padding:10px;background-color:rgb(231,89,89);opacity:.8;color:white;border-radius:8px;">
    Ошибка, по побуйте снова.</div>
<section class="main w-g">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    Авторизация с JWT устроена следующим образом:<br>
                    Пользователь регистрируется, и сразу же авторизован!<br>
                    При регистрации/авторизации, в токен "вшивается" некая информация о пользователе,<br>
                    которую потом можно использовать на сайте.<br>
                    Сверху справа - ID и Email пользователя получены из JWT токена.<br>
                    <br><br>
                    Если пользователь авторизовался на другом устройстве, то с текущего аккаунта<br>
                    пользователя "выбросит".

                </p>
            </div>
            <div class="col-md-9"></div>
        </div>
    </div>
</section>