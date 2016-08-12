<?php
namespace FileHosting\Models;
use \FileHosting\Helpers\Helper;
class FileModel{
	protected $id;
	public $name;
	public $original_name;
	public $path;
	public $size;
	public $time;
	public $comment;

	function __construct(){
		$this->path=Helper::getAbsolutePath('/files/').date("Y").'/'.date("m").'/'.date("d").'/';
		$this->time=time();
	}

	public function setName(string $name){
		$this->original_name=$name;
		$this->name=Helper::execDisable(Helper::transliterate($name));
	}

	public function setId($id){
		$this->id=$id;
	}

	public function addInfo($infoArray){
		foreach($infoArray as $key=>$value){			
			$object_vars=get_object_vars($this);
			//Если есть переменная в объекте
			if(array_key_exists($key,$object_vars)){
				//Если значение из массива не пустое
				if(isset($value) and ($value!=NULL)){
					//Передать значение объекту
					$this->$key=$value;
				}
			}
		}

		if(isset($infoArray['id'])){
			$this->setId($infoArray['id']);
		}
	}

	public function showSize(){
		$kb=round($this->size/1024);
		if ($kb>800) {
			$mb=round($kb/1024,1);
			return $mb.' Mb';
		}
		return $kb.' Kb';
	}
}