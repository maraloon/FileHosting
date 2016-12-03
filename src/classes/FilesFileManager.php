<?php
namespace FileHosting;
use \FileHosting\Helpers\Helper;
class FilesFileManager
{

    public $path;
    public $originalName;
    public $name;
    protected $validTypes;

    public function __construct($validTypes)
    {
        $this->path=date("Y").'/'.date("m").'/'.date("d").'/';
        $this->validTypes=$validTypes;
    }
    /**
     * @var string $fileName
     * @var string $uploadFolder
     * @return bool status of copy
     */
    public function addFile(string $fileName, string $uploadFolder)
    {
        $this->name=$this->execDisable(Helper::transliterate($fileName));
        $this->originalName=$fileName;

        $uploaddir=$uploadFolder.$this->path;
        //Если файл с таким именем уже существует
        $isNameUnique=false;
        while ($isNameUnique != true) {
            if (file_exists($uploaddir.basename($this->name))) {
                $this->name=Helper::addHashToFileName($this->name);
            } else {
                $isNameUnique=true;
            }
        }

        $fileToUpload = $uploaddir.basename($this->name);
        //Если папки нет -> создаём
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir,0777,true);
        }

        //Копируем файл из каталога для временного хранения файлов:
        if (copy($_FILES['file']['tmp_name'], $fileToUpload)){
            return true;    
        }
        return false;
    }

    function deleteFile($path){
        return unlink($path);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getOriginalName()
    {
        return $this->originalName;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function execDisable($fileName)
    {
        if (!preg_match("/^((\w)|(\d)|[ -_])*(.)(".implode('|', $this->validTypes).")$/iu", $fileName)){
            $fileName.='.txt';
        }
        return $fileName;
    }

}