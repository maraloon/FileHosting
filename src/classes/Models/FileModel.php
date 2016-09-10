<?php
namespace FileHosting\Models;
use \FileHosting\Helpers\Helper;
class FileModel{
	protected $id;
	public $name;
	public $originalName;
	public $path;
	public $size;
	public $time;
	public $description;

	function __construct(){
		$this->path=date("Y").'/'.date("m").'/'.date("d").'/';
		//$this->time=time();
	}

	public function setName(string $name){
		$this->originalName=$name;
		$this->name=Helper::execDisable(Helper::transliterate($name));
	}

	public function setId($id){
		$this->id=$id;
	}
	public function getId(){
		return $this->id;
	}

	public function addInfo($infoArray){
		foreach($infoArray as $key=>$value){			
			$objectVars=get_object_vars($this);
			//Если есть переменная в объекте
			if(array_key_exists($key,$objectVars)){
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

	public function getType(){

		$map = array(
			'image' => 'jpg|jpeg|png|gif',
			'audio' => 'mp3|ogg|wav|wv|aac|aiff|ape|flac|dts|wma|midi',
			'video' => 'mp4|avi|wmv|webm|3gp',
			);

		foreach ($map as $type => $extensionList) {
			if (preg_match("/^((\w)|(\d)|[ -_])*(.)(".$extensionList.")$/iu",$this->originalName)==1) {
				return $type;
			}
		}
		return 'other';

	}
}