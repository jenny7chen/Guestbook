<?php

class DBPostActions
{
    public function getPostData($id)
    {
        require_once 'DBSetting.php';
        $dbSetting = new DBSetting();
        $filePath = '';
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = 'SELECT * FROM posts WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();
            $result = $stmt->fetchAll();

            $postArray = array();
            foreach ($result as $value) {
                $post = new Post();
                $post->content = $value['content'];
                $post->filePath = $value['filepath'];
                $post->author = $value['author'];
                $post->title = $value['title'];
                $post->id = $value['id'];
                $post->update_time = $value['update_time'];
                array_push($postArray, $post);
            }
            return $postArray[0];
        } catch (PDOException $e) {
            // echo 'get data'.$e->getMessage();
        return null;
        }
        unset($pdo);
    }

    public function fetchPostsFromDB()
    {
        require_once 'DBSetting.php';
        $dbSetting = new DBSetting();
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = 'SELECT * FROM posts ORDER BY update_time DESC';
            $sth = $pdo->prepare($sql);
            $success = $sth->execute();
            $result = $sth->fetchAll();
        } catch (PDOException $e) {
            // echo 'fetch post data : '.$e->getMessage();
            return null;
        }
        unset($pdo);

        $postArray = array();
        foreach ($result as $value) {
            $post = new Post();
            $post->content = $value['content'];
            $post->filePath = $value['filepath'];
            $post->author = $value['author'];
            $post->title = $value['title'];
            $post->id = $value['id'];
            $post->update_time = $value['update_time'];
            array_push($postArray, $post);
        }

        return $postArray;
    }

    public function createNewPost($title, $content, $author, $filePath, $todaysDate)
    {
        require_once 'DBSetting.php';
        $dbSetting = new DBSetting();
        $filePath = '';
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = 'INSERT INTO posts (author, update_time, title, content, filepath) VALUES (:author, :update_time, :title, :content, :filepath)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':update_time', $todaysDate, PDO::PARAM_STR, 10);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':filepath', $filePath);
            $success = $stmt->execute();
            unset($pdo);

            return $success;
        } catch (PDOException $e) {
            // echo 'create data'.$e->getMessage();
            return false;
        }
    }

    public function modifyPost($id, $title, $content, $filePath, $todaysDate)
    {
        require_once 'DBSetting.php';
        $dbSetting = new DBSetting();
        $filePath = '';
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = 'UPDATE posts SET update_time = :update_time,
                      title = :title,
                      content = :content,
                      filepath = :filepath
                      WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':update_time', $todaysDate, PDO::PARAM_STR, 10);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':filepath', $filePath);
            $stmt->bindParam(':id', $id);
            $success = $stmt->execute();

            return $success;
        } catch (PDOException $e) {
            // echo 'modifyPost data'.$e->getMessage();
          return false;
        }
        unset($pdo);
    }

    public function clearAllPost()
    {
        require_once 'DBSetting.php';
        $dbSetting = new DBSetting();
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = 'DELETE FROM posts';
            $sth = $pdo->prepare($sql);
            $success = $sth->execute();
            unset($pdo);

            return $success;
        } catch (PDOException $e) {
            // echo 'delete post data : '.$e->getMessage();
            return false;
        }
    }

    public function deletePostById($id)
    {
        require_once 'DBSetting.php';
        $dbSetting = new DBSetting();
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = "DELETE FROM posts WHERE id=$id";
            $sth = $pdo->prepare($sql);
            $success = $sth->execute();
            unset($pdo);

            return $success;
        } catch (PDOException $e) {
            // echo 'delete post data : '.$e->getMessage();
            return false;
        }
    }
}
