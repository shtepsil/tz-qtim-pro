<?php

namespace controllers;

use app\Db;
use components\Debugger as d;
use app\View;
use components\JwtHelper;
use models\User;
use app\Request;
use models\Token;
use controllers\Controller;

class UserController extends Controller
{
    /**
     * @throws \app\exceptions\DbException
     * @throws \app\exceptions\AuthException
     */
    public function actionRegister()
    {
        $data = ['status' => 401];
        $errors = [];

        if (Request::isAjax()) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }
            if (!User::checkRole($role)) {
                $errors[] = 'Выберите роль для пользователя';
            }
            if (count($errors) == 0) {
                $user_id = User::register($name, $email, $password, $role);
                if ($user_id) {
                    $tokens = User::setJwt($user_id);
                    $data['status'] = 200;
                    $data['refresh_token'] = $tokens['refresh_token'];
                } else {
                    $errors[] = 'Ошибка регистрации';
                }
            } else {
                $data['errors'] = $errors;
            }
            Request::response($data);
        }
        View::render('user/register', [
            'data' => $data,
            'errors' => $errors,
        ]);
    }

    /**
     * @throws \app\exceptions\DbException
     */
    public function actionLogout()
    {
        $post = $_POST; // Тут можно обезопасить данные POST
        Token::deleteToken($post['refresh_token']);
        d::ajax('Токен из БД очищен');
    }

    /**
     * @throws \app\exceptions\AuthException
     * @throws \app\exceptions\DbException
     */
    public function actionLogin()
    {
        if (Request::isAjax()) {
            $post = $_POST; // Тут можно обезопасить данные POST
//            d::ajax($post);
            $data = ['status' => 401];
            $data['errors'] = [];
            $email = $post['email'];
            $password = $post['password'];
            if (!User::checkEmail($email)) {
                $data['errors'][] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $data['errors'][] = 'Пароль не должен быть короче 6-ти символов';
            }

            $user_id = User::checkUserData($email, $password);
            if (!$user_id) {
                $data['errors'][] = 'Логин или пароль не правильные';
            } else {
                $tokens = User::setJwt($user_id);
                $data['status'] = 200;
                $data['refresh_token'] = $tokens['refresh_token'];
            }
            Request::response($data);
        }
        if (!User::isGuest()) {
            header('Location: /');
        }
        View::render('user/login');
    }

    /**
     * @throws \app\exceptions\DbException
     * @throws \app\exceptions\JwtException
     */
    public function actionRefresh()
    {
        if (isset($_COOKIE['refreshToken'])) {
            $jwth = new JwtHelper();
            // $user_data - это объект
            $user_data = $jwth->verify($_COOKIE['refreshToken']);
            $token_from_db = Token::findToken($_COOKIE['refreshToken']);
            if (!$user_data and !$token_from_db) {
                Request::response([
                    'status' => 401,
                    'message' => 'Ошибка авторизации'
                ]);
            } else {
                User::setJwt($user_data->id);
            }
        }
    }

} //Class
