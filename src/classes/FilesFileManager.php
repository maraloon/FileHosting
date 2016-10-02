<?php
namespace FileHosting;
use \FileHosting\Helpers\Helper;
class FilesFileManager{

	public $path;
	public $originalName;
	public $name;
/*


	public function addFile(\FileHosting\Models\FileModel $file, string $uploadUri){
		// Каталог, в который мы будем принимать файл:
		$uploaddir=$_SERVER['DOCUMENT_ROOT'].$uploadUri.$file->path;
		$fileToUpload = $uploaddir.basename($file->name);
		//Если папки нет -> создаём
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir,0777,true);
		}
		//Копируем файл из каталога для временного хранения файлов:
		if (copy($_FILES['file']['tmp_name'], $fileToUpload)){
			return true;	
		}
		else{
			return false;
		}
	}
*/
	public function __construct(){
		$this->path=date("Y").'/'.date("m").'/'.date("d").'/';
	}

	public function addFile(string $fileName, string $uploadUri){
		$this->name=Helper::execDisable(Helper::transliterate($fileName));
		$this->originalName=$fileName;

		$uploaddir=$_SERVER['DOCUMENT_ROOT'].$uploadUri.$this->path;
		$fileToUpload = $uploaddir.basename($this->name);
		//Если папки нет -> создаём
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir,0777,true);
		}
		//Копируем файл из каталога для временного хранения файлов:
		if (copy($_FILES['file']['tmp_name'], $fileToUpload)){
			return true;	
		}
		else{
			return false;
		}
	}

	public function getPath(){
		return $this->path;
	}

	public function getOriginalName(){
		return $this->originalName;
	}

	public function getName(){
		return $this->name;
	}

	/**
	 * @var \FileHosting\Models\FileModel $file
	 * @var string $uploadUri
	 * @return bool status of copy
	 */
	
}