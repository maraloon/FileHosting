<?php
namespace FileHosting;
class FilesFileManager{
	/**
	 * @var \FileHosting\Models\FileModel $file
	 * @var string $upload_folder
	 * @return bool status of copy
	 */
	public function addFile(\FileHosting\Models\FileModel $file, string $upload_folder){
		// Каталог, в который мы будем принимать файл:
		$uploaddir=$upload_folder.$file->path;
		$file_to_upload = $uploaddir.basename($file->name);
		//Если папки нет -> создаём
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir,0777,true);
		}
		//Копируем файл из каталога для временного хранения файлов:
		if (copy($_FILES['file_to_upload']['tmp_name'], $file_to_upload)){
			return true;	
		}
		else{
			return false;
		}
	}
}