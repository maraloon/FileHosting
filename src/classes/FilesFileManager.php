<?php
namespace FileHosting;
class FilesFileManager{
	/**
	 * @var \FileHosting\Models\FileModel $file
	 * @var string $uploadUri
	 * @return bool status of copy
	 */
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
}