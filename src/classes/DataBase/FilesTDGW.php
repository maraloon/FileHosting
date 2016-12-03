<?php
namespace FileHosting\DataBase;
use \FileHosting\Models\File;
class FilesTDGW{
    protected $db;

    public function __construct(\PDO $pdoConnection){
        $this->db=$pdoConnection;
    }

    /**
     * @var \FileHosting\Models\File $file
     * @return integer id
     */
    public function addFile(File $file){
        $SqlString="INSERT INTO `files`
                (`name`,`originalName`,`path`,`size`,`mediaInfo`,`mime`,`description`,`userId`)
                VALUES
                (:name,:originalName,:path,:size,:mediaInfo,:mime,:description,:userId)";

        $rows = $this->db->prepare($SqlString);
        $rows->bindValue(':name', $file->name, \PDO::PARAM_STR);
        $rows->bindValue(':originalName', $file->originalName, \PDO::PARAM_STR);
        $rows->bindValue(':path', $file->path, \PDO::PARAM_STR);
        $rows->bindValue(':size', $file->size, \PDO::PARAM_INT);
        $rows->bindValue(':mediaInfo', $file->mediaInfo, \PDO::PARAM_STR);
        $rows->bindValue(':mime', $file->mime, \PDO::PARAM_STR);
        $rows->bindValue(':description', $file->description, \PDO::PARAM_STR);
        $rows->bindValue(':userId', $file->userId, \PDO::PARAM_INT);
        $rows->execute();

        return $this->db->lastInsertId();
    }

    public function deleteFile($id){
        $SqlString="DELETE FROM `files`
                WHERE `id`=:id";

        $rows = $this->db->prepare($SqlString);
        $rows->bindValue(':id', $id, \PDO::PARAM_INT);
        return $rows->execute();
    }

    public function addDescription(int $id, $description){
        $SqlString="UPDATE `files`
                SET `description`=:description
                WHERE `id`=:id";

        $rows = $this->db->prepare($SqlString);
        $rows->bindValue(':id', $id, \PDO::PARAM_INT);
        $rows->bindValue(':description', $description, \PDO::PARAM_STR);
        $rows->execute();
    }

    public function incrementNumberOfDownloads(int $id){
        $SqlString="UPDATE `files`
                SET `numberOfDownloads`=`numberOfDownloads`+1
                WHERE `id`=:id";

        $rows = $this->db->prepare($SqlString);
        $rows->bindValue(':id', $id, \PDO::PARAM_INT);
        $rows->execute();
    }

    public function getLastFiles($limit){
        $rows = $this->db->prepare("SELECT * FROM `files` ORDER BY `time` LIMIT :limit OFFSET 0");
        $rows->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $rows->execute();

        $filesRow=$rows->fetchAll(\PDO::FETCH_ASSOC);

        //Подготавливаем массив
        $files=array();
        foreach($filesRow as $fileRow){
            $file=new File();
            $file->addInfo($fileRow);
            $files[]=$file;
        }
        return $files;
    }

    public function getFile($id){
        $rows = $this->db->prepare("SELECT * FROM `files` WHERE `id`=:id");
        $rows->bindValue(':id', $id, \PDO::PARAM_INT);
        $rows->execute();

        $fileRow=$rows->fetch(\PDO::FETCH_ASSOC);

        if ($fileRow!=NULL) {
            $file=new File();
            $file->addInfo($fileRow);

            return $file;
        }
        return false;
    }

    
}