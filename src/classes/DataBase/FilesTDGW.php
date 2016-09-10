<?php
namespace FileHosting\DataBase;
use \FileHosting\Models\FileModel;
class FilesTDGW{
	protected $db;

	public function __construct(\PDO $pdoConnection){
		$this->db=$pdoConnection;
	}

	/**
	 * @var \FileHosting\Models\FileModel $file
	 * @return bool status of adding to DB
	 */
	public function addFile(FileModel $file){
		$SqlString="INSERT INTO `files`
				(`name`,`originalName`,`path`,`size`,`description`)
				VALUES
				(:name,:originalName,:path,:size,:description)";

		$rows = $this->db->prepare($SqlString);
		$rows->bindValue(':name', $file->name, \PDO::PARAM_STR);
		$rows->bindValue(':originalName', $file->originalName, \PDO::PARAM_STR);
		$rows->bindValue(':path', $file->path, \PDO::PARAM_STR);
		$rows->bindValue(':size', $file->size, \PDO::PARAM_INT);
		$rows->bindValue(':description', $file->description, \PDO::PARAM_STR);
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
			$file=new FileModel();
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
			$file=new FileModel();
			$file->addInfo($fileRow);

			return $file;
		}
		return false;
	}
}