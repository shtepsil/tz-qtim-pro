<?php

namespace models;

use app\Db;
use app\exceptions\AuthException;
use app\exceptions\DbException;
use app\Request;

class Articles
{

    /**
     * @param $start
     * @param $limit
     * @return array
     * @throws DbException
     */
    public static function findAll($start = 0, $limit = 3)
    {
        $results = NULL;
        $db = Db::getConnection();
        $sql = "SELECT * FROM `articles` ORDER BY `id` DESC LIMIT ?, ?";
        $select = $db->prepare($sql);
        $select->bind_param('ii', $start, $limit);
        $select->execute();
        $meta = $select->result_metadata();
        while ($field = $meta->fetch_field()) {
            $parameters[] = &$row[$field->name];
        }
        call_user_func_array([$select, 'bind_result'], $parameters);
        while ($select->fetch()) {
            foreach ($row as $key => $val) {
                $x[$key] = $val;
            }
            $results[] = $x;
        }
        return $results;
    }

    /**
     * @param $id
     * @return array|null
     */
    public static function findById($id)
    {
        try {
            $db = Db::getConnection();
            $time = time();
            $sql = "SELECT `id`, `title`, `text`, `created_at` FROM `articles` WHERE `id` = ?";
            $result = $db->prepare($sql);
            $result->bind_param('s', $id);
            $result->execute();
            $result->bind_result($id, $title, $text, $created_at);
            $result->fetch();
            $result->close();
            return [
                'id' => $id,
                'title' => $title,
                'text' => $text,
                'created_at' => $time
            ];
        } catch (\Exception $e) {
            return NULL;
        }
    }

    /**
     * @param $title
     * @param $text
     * @return null
     * @throws DbException
     */
    public static function create($title, $text)
    {
        try {
            $create_result = NULL;
            $db = Db::getConnection();
            $time = time();
            $sql = "INSERT INTO `articles` (title, text, created_at) "
                . "VALUES (?, ?, ?)";
            $result = $db->prepare($sql);
            $result->bind_param('ssi', $title, $text, $time);
            if ($result->execute()) {
                $result->close();
                $create_result = $db->insert_id;
            }
            return $create_result;
        } catch (\Exception $e) {
            throw new DbException('Add article error');
        }
    }

    /**
     * @return bool
     * @throws DbException
     */
    public static function updateById($id, $title, $text)
    {
        try {
            $db = Db::getConnection();
            $sql = "UPDATE `articles` SET `title` = ?, `text` = ? WHERE `id` = ?";
            $result = $db->prepare($sql);
            $result->bind_param('ssi', $title, $text, $id);
            $result->execute();
            $result->close();
            return true;
        } catch (\Exception $e) {
            throw new DbException('Update article error');
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws DbException
     */
    public static function deleteById($id)
    {
        try {
            $db = Db::getConnection();
            $stmt = $db->prepare("DELETE FROM `articles` WHERE `id` = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (\Exception $e) {
            throw new DbException('Delete article error');
        }
    }

    /**
     * @return bool
     * @throws DbException
     */
    public static function deleteAll()
    {
        try {
            $db = Db::getConnection();
            $stmt = $db->prepare("DELETE FROM `articles`");
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (\Exception $e) {
            throw new DbException('Update token error');
        }
    }

} //Class
