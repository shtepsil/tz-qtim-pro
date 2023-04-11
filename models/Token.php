<?php

namespace models;

use app\exceptions\DbException;
use components\Debugger as d;
use app\Db;

class Token
{

    /**
     * @param $code
     * @return array
     */
    public static function findTokenByUserId($user_id = NULL)
    {
        $refresh_token = NULL;
        if ($user_id) {
            $db = Db::getConnection();
            $sql = 'SELECT `refresh_token` FROM `token` WHERE `user_id` = ?';
            $select = $db->prepare($sql);
            $select->bind_param('s', $user_id);
            $select->execute();
            $result = $select->get_result();
            if ($result->num_rows > 0) {
                $refresh_token = $result->fetch_assoc();
            }
            $select->close();
        }
        return $refresh_token;
    }

    /**
     * @param $code
     * @return array
     */
    public static function insertToken($data = [])
    {
        if (is_array($data) and count($data) and d::array_keys_exists(['token', 'user_id'], $data)) {
            try {
                $db = Db::getConnection();
                $sql = "INSERT INTO `token` (user_id, refresh_token) VALUES (?, ?)";
                $result = $db->prepare($sql);
                $result->bind_param('is', $data['user_id'], $data['token']);
                $result->execute();
                $result->close();
                return true;
            } catch (\Exception $e) {
                throw new DbException('Token add error');
            }
        }
        return false;
    }

    /**
     * @param array $data
     * @return bool
     * @throws DbException
     */
    public static function updateToken(array $data = [])
    {
        if (is_array($data) and count($data) and d::array_keys_exists(['user_id', 'token'], $data)) {
            try {
                $db = Db::getConnection();
                $sql = "UPDATE `token` SET `refresh_token` = ? WHERE `user_id` = ?";
                $result = $db->prepare($sql);
                $result->bind_param('si', $data['token'], $data['user_id']);
                $result->execute();
                $result->close();
                return true;
            } catch (\Exception $e) {
                throw new DbException('Update token error');
            }
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws DbException
     */
    public static function deleteToken(string $token)
    {
        if (is_string($token) and $token != '') {
            try {
                $db = Db::getConnection();
                $stmt = $db->prepare("DELETE FROM `token` WHERE `refresh_token` = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $stmt->close();
                return true;
            } catch (\Exception $e) {
                throw new DbException('Update token error');
            }
        }
    }
    /**
     * @param $code
     * @return array
     */
    public static function findToken($refresh_token)
    {
        $count_token = NULL;
        $db = Db::getConnection();
        $sql = "SELECT COUNT(*) as `refresh_token` FROM `token` WHERE `refresh_token` = ?";
        $select = $db->prepare($sql);
        $select->bind_param('s', $refresh_token);
        $select->execute();
        $result = $select->get_result();
        if ($result->num_rows > 0) {
            $count_token = $result->fetch_assoc();
        }
        $select->close();
        return $count_token;
    }

} //Class