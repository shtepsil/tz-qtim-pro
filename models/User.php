<?php

namespace models;

use app\exceptions\DbException;
use components\Debugger as d;
use app\exceptions\AuthException;
use app\Db;
use components\JwtHelper;
use models\Token;

class User
{

    public static $info = NULL;

    public static function checkAuth()
    {
        if (isset($_COOKIE['refreshToken'])) {
            $jwth = new JwtHelper();
            $user_data = $jwth->verify($_COOKIE['refreshToken']);
            User::$info = json_decode(json_encode($user_data, 256), true);
            return $user_data;
        }
        return false;
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     * @return bool
     */
    public static function register($name, $email, $password, $role)
    {
        /**
         * $password можно захэшировать какой нибудь солью...
         */
        try {
            $register_result = NULL;
            $db = Db::getConnection();
            $time = time();
            $sql = "INSERT INTO `users` (name, email, password, role, created_at) "
                . "VALUES (?, ?, ?, ?, ?)";
            $result = $db->prepare($sql);
            $result->bind_param('sssii', $name, $email, $password, $role, $time);
            if ($result->execute()) {
                $register_result = $db->insert_id;
            }
            $result->close();
            return $register_result;
        } catch (\Exception $e) {
            throw new AuthException('Registration error');
        }
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public static function checkUserData($email, $password)
    {
        try {
            $register_data = NULL;
            $db = Db::getConnection();
            $sql = 'SELECT `id` FROM `users` WHERE `email` = ? AND `password` = ?';
            $result = $db->prepare($sql);
            $result->bind_param('ss', $email, $password);
            $result->execute();
            $result->bind_result($id);
            if ($result->fetch()) {
                $register_data = $id;
            }
            return $register_data;
        } catch (\Exception $e) {
            throw new AuthException('Check user error');
        }
    }

    /**
     * @param $userId
     */
    public static function setCookie($tokens = [])
    {
        if (
            is_array($tokens) and count($tokens)
            and d::array_keys_exists(['refresh_token', 'access_token'], $tokens)
        ) {
            $refresh_token_options = [
                'expires' => strtotime('+30 days'),
                'path' => '/',
            ];
            setcookie('refreshToken', $tokens['refresh_token'], $refresh_token_options);
        }
    }

    /**
     * @throws DbException
     * @throws AuthException
     */
    public static function setJwt($user_id)
    {
        $tokens = NULL;
        $jwth = new JwtHelper();
        $user = User::getUserById($user_id);
        if ($user) {
            $domains = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
            $expire = strtotime('+7 days');
            $payload = [
                // адрес или имя удостоверяющего центра
                'iss' => $domains,
                // имя клиента для которого токен выпущен
                'aud' => $domains,
                // время, когда был выпущен JWT
                'iat' => time(),
                // время, начиная с которого может быть использован (не раньше, чем)
//                'nbf' => 1357000000
                // идентифицирует время истечения срока действия токена
                'exp' => $expire,
                'data' => $user
            ];

            $access_token = $jwth->createAccessToken($payload);
            $refresh_token = $jwth->createRefreshToken($payload);
            $token = Token::findTokenByUserId($user_id);
            // Если токен найден, обновим его в БД
            if ($token) {
                // Обновим токен у пользователя
                Token::updateToken(['user_id' => $user_id, 'token' => $refresh_token]);
            } else {
                // Если токен в БД не найден, запишем новый токен.
                Token::insertToken(['user_id' => $user_id, 'token' => $refresh_token]);
            }
            if ($access_token and $refresh_token) {
                $tokens = [
                    'access_token' => $access_token,
                    'refresh_token' => $refresh_token,
                ];
            }
            return $tokens;
        }

    }

    /**
     * @return bool
     */
    public static function isGuest()
    {
        $guest = true;
        $user_data = User::checkAuth();
        if (isset($_COOKIE['refreshToken']) and $user_data) {
            $guest = false;
        }
        return $guest;
    }

    /**
     * @param $name
     * @return bool|null
     */
    public static function checkName($name)
    {
        $result_check = NULL;
        if (strlen($name) >= 2) {
            $result_check = true;
        }
        return $result_check;
    }

    /**
     * @param $password
     * @return bool|null
     */
    public static function checkPassword($password)
    {
        $result_check = NULL;
        if (strlen($password) >= 6) {
            $result_check = true;
        }
        return $result_check;
    }

    /**
     * @param $email
     * @return bool|null
     */
    public static function checkEmail($email)
    {
        $result_check = NULL;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result_check = true;
        }
        return $result_check;
    }

    /**
     * @param $email
     * @return bool|null
     * @throws DbException
     */
    public static function checkEmailExists($email)
    {
        $result_check = NULL;
        $db = Db::getConnection();
        $sql = "SELECT * FROM `users` WHERE `email` = ?";
        $result = $db->prepare($sql);
        $result->bind_param('s', $email);
        $result->execute();
        if ($result->fetch()) {
            $result->close();
            $result_check = true;
        }
        return $result_check;
    }

    /**
     * @param $role
     * @return bool|null
     */
    public static function checkRole($role)
    {
        $result_check = NULL;
        if ($role != '') {
            $result_check = true;
        }
        return $result_check;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getUserById($id)
    {
        try {
            $db = Db::getConnection();
            $sql = "SELECT `id`,`name`,`email`,`password`, `role`, `created_at` FROM `users` WHERE `id` = ?";
            $result = $db->prepare($sql);
            $result->bind_param('s', $id);
            $result->execute();
            $result->bind_result($id, $name, $email, $password, $role, $created_at);
            $result->fetch();
            $result->close();
            return [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'created_at' => $created_at
            ];
        } catch (\Exception $e) {
            return NULL;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public static function addHistory($data)
    {
        try {
            $time = time();
            $db = Db::getConnection();
            $sql = "INSERT INTO `history` (user_id, code, question, created_at) "
                . "VALUES (?, ?, ?, ?)";
            $result = $db->prepare($sql);
            $result->bind_param('issi', $data['user_id'], $data['code'], $data['question'], $time);
            $result->execute();
            $result->close();
            return true;
        } catch (\Exception $e) {
            throw new AuthException('User add history error');
        }
    }

    /**
     * @param $id
     * @return array
     */
    public static function getHistory($user_id)
    {
        try {
            $db = Db::getConnection();
            $sql = "SELECT * FROM `history` WHERE `user_id` = ? ORDER BY `created_at` DESC";
            $select = $db->prepare($sql);
            $select->bind_param('s', $user_id);
            $select->execute();
            $result = $select->get_result();
            $history = [];
            if ($result->num_rows > 0) {
                foreach ($result as $f) {
                    $history[] = $f;
                }
            }
            $select->close();
            //            d::pex($history);
            return $history;
        } catch (\Exception $e) {
            throw new AuthException('Get user error');
        }
    }

    /**
     * @param $user_id
     * @return bool
     */
    public static function deleteHistory($user_id)
    {
        $result_delete = NULL;
        $db = Db::getConnection();
        $sql = 'DELETE FROM `history` WHERE `user_id` = ?';
        $result = $db->prepare($sql);
        $result->bind_param('i', $user_id);
        if ($result->execute()) {
            $result->close();
            $result_delete = true;
        }
        return $result_delete;
    }



    /**
     * @param $email
     * @return bool
     */
    public static function findAll()
    {
        try {
            $db = Db::getConnection();
            $sql = "SELECT * FROM `users` ORDER BY `id` DESC";
            $select = $db->prepare($sql);
            $select->execute();
            $result = $select->get_result();
            $users = [];
            if ($result->num_rows > 0) {
                foreach ($result as $f) {
                    $users[] = $f;
                }
            }
            $select->close();
            return $users;
        } catch (\Exception $e) {
            throw new AuthException('Get user error');
        }
    }

} //Class
