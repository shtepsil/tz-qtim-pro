<?php

use components\Debugger as d;

?>
<br><br><br><br>
<section class="main w-register">
    <div class="container">
        <div class="row">

            <div class="col-sm-4 col-sm-offset-4 padding-right">

                <?php if ($data['result']): ?>
                    <p>Вы зарегистрированы!</p>
                <?php else: ?>

                    <div class="signup-form"><!--sign up form-->
                        <h2>Регистрация на сайте</h2>
                        <form name="form_reg" action="/user/register" method="post" class="register-form">
                            <input type="text" name="name" placeholder="Имя" value="<?= ($data['name']) ?: 'user1' ?>" />
                            <input type="email" name="email" placeholder="E-mail"
                                value="<?= ($data['email']) ?: 'test1@mail.ru' ?>" />
                            <input type="password" name="password" placeholder="Пароль"
                                value="<?= ($data['password']) ?: '123456' ?>" />
                            <select name="role" id="">
                                <option value="">Выберите роль</option>
                                <option value="0">Обычный пользователь</option>
                                <option value="1">Админ</option>
                            </select>
                            <button type="button" name="reg" class="btn btn-default">
                                <img src="/template/images/animate/loading.gif" class="loading" />
                                Регистрация
                            </button>
                            <br>
                            <div class="text-center">
                                <a href="/user/login">Авторизация</a><br>
                            </div>
                        </form>
                        <br>
                        <div class="error text-center dn"></div>
                    </div><!--/sign up form-->

                    <? d::res() ?>
                <?php endif; ?>
                <br />
                <br />
            </div>
        </div>
    </div>
</section>