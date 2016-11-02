<?php
namespace FileHosting\DataBase;
use \FileHosting\Models\File;
class UsersTDGW{
    protected $db;

    public function __construct(\PDO $pdoConnection){
        $this->db=$pdoConnection;
    }
    public function addUser($user){
        $SqlString="INSERT INTO `users` (`token`) VALUES (:token)";

        $rows = $this->db->prepare($SqlString);
        $rows->bindValue(':token', $user->token, \PDO::PARAM_STR);
        $rows->execute();

        return $this->db->lastInsertId();
    }

    public function getIdByToken($token){
        $rows = $this->db->prepare("SELECT id FROM `users` WHERE `token`=:token");
        $rows->bindValue(':token', $token, \PDO::PARAM_STR);
        $rows->execute();

        $id=$rows->fetchColumn();
        if ($id!=NULL) {
            return $id;
        }
        return false;
    }
}