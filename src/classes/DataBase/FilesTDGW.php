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
		echo "FilesTDGW->add";
		//var_dump($file);
		$SqlString="INSERT INTO `files`
				(`name`,`original_name`,`path`,`size`,`comment`)
				VALUES
				(:name,:original_name,:path,:size,:comment)";

		$rows = $this->db->prepare($SqlString);
		$rows->bindValue(':name', $file->name, \PDO::PARAM_STR);
		$rows->bindValue(':original_name', $file->original_name, \PDO::PARAM_STR);
		$rows->bindValue(':path', $file->path, \PDO::PARAM_STR);
		$rows->bindValue(':size', $file->size, \PDO::PARAM_INT);
		$rows->bindValue(':comment', $file->comment, \PDO::PARAM_STR);
		$rows->execute();
	}

	public function getLastFiles($limit){
		$rows = $this->db->prepare("SELECT * FROM `files` ORDER BY `time` LIMIT :limit OFFSET 0");
		$rows->bindValue(':limit', $limit, \PDO::PARAM_INT);
		$rows->execute();

		$filesRow=$rows->fetchAll(\PDO::FETCH_ASSOC);
		//return $filesRow;

		//Подготавливаем массив
		$files=array();
		foreach($filesRow as $fileRow){
			$file=new FileModel();
			$file->addInfo($fileRow);
			$files[]=$file;
		}
		return $files;
	}
}