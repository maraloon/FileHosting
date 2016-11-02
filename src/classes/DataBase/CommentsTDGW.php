<?php
namespace FileHosting\DataBase;
use \FileHosting\Models\Comment;
class CommentsTDGW{
    protected $db;

    public function __construct(\PDO $PDO_connection){
        $this->db=$PDO_connection;
    }

    /**
     * @var \FileHosting\Models\Comment $comment
     * @return bool status of adding to DB
     */
    public function addComment(Comment $comment){
        $comment->path=$this->generatePath($comment);

        $SqlString="INSERT INTO `comments`
                (`text`,`nick`,`fileId`,`parentId`,`path`)
                VALUES
                (:text,:nick,:fileId,:parentId,:path)";

        $rows = $this->db->prepare($SqlString);
        $rows->bindValue(':text', $comment->text, \PDO::PARAM_STR);
        $rows->bindValue(':nick', $comment->nick, \PDO::PARAM_STR);
        $rows->bindValue(':fileId', $comment->fileId, \PDO::PARAM_INT);
        $rows->bindValue(':parentId', $comment->parentId, \PDO::PARAM_INT);
        $rows->bindValue(':path', $comment->path, \PDO::PARAM_INT);
        $rows->execute();
    }

    public function getComments(int $fileId){

        $rows = $this->db->prepare("SELECT * FROM `comments` WHERE `fileId`=:fileId ORDER BY `path`");
        $rows->bindValue(':fileId', $fileId, \PDO::PARAM_INT);
        $rows->execute();

        $commentsRow=$rows->fetchAll(\PDO::FETCH_ASSOC);

        //Подготавливаем массив
        $comments=array();
        foreach($commentsRow as $commentRow){
            $comment=new Comment();
            $comment->addInfo($commentRow);
            $comments[]=$comment;
        }
        return $comments;
    }
    
    protected function generatePath(Comment $comment){
        //Формат nnn[.nnn]*  ,где n - цифра
        //Если надо добавить подкоммент
        if ($comment->parentId!=NULL) {
            //Берём путь из строки родителя (001.003)
            $rows = $this->db->prepare("SELECT `path` FROM `comments` WHERE `id`=:parentId AND `fileId`=:fileId");
            $rows->bindValue(':parentId', $comment->parentId, \PDO::PARAM_INT);
            $rows->bindValue(':fileId', $comment->fileId, \PDO::PARAM_INT);
            $rows->execute();

            $path=$rows->fetchColumn();
            //Выбираем путь самого последнего дочернего коммента (001.003.007)
            $rows = $this->db->prepare("
                SELECT `path`
                FROM `comments`
                WHERE `fileId`=:fileId
                AND `path` LIKE '$path.%'
                LIMIT 1
                ");

            $rows->bindValue(':fileId', $comment->fileId, \PDO::PARAM_INT);
            $rows->execute();
            $lastCommentPath=$rows->fetchColumn();
            //Создаём новый путь равный последний дочерний+1 (001.003.008)
            if ($lastCommentPath) {
                return $this->incrementPath($lastCommentPath);
            }
            //Если нет детей у родителя то (001.003.001)
            return $path.'.001';
        }
        //Если надо добавить коммент к файлу
        else{
            $rows = $this->db->prepare("
                SELECT `path`
                FROM `comments`
                WHERE `fileId`=:fileId
                AND `path` LIKE '___' /*три символа*/
                ORDER BY `path` DESC
                LIMIT 1
                ");
            $rows->bindValue(':fileId', $comment->fileId, \PDO::PARAM_INT);
            $rows->execute();
            var_dump($rows);
            $lastCommentPath=$rows->fetchColumn();
            return $this->incrementPath($lastCommentPath);
        }

    }

    protected function incrementPath($path){
        ++$path;
        $len=strlen($path);
        if ($len<4) {
            $nulls = ($len==1) ? '00' : '0';
            return $nulls.$path;
        }
        else{
            return $path;
        }

    }
}