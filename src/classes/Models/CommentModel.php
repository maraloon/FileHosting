<?php
namespace FileHosting\Models;
//use \FileHosting\Helpers\Helper;
class CommentModel{
	protected $id;
	public $file_id;
	public $parent_id;
	public $text;
	public $nick;
	public $time;


	function __construct(){
	}

	public function setId($id){
		$this->id=$id;
	}
	public function getId(){
		return $this->id;
	}

	public function addInfo($info_array){
		foreach($info_array as $key=>$value){			
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

		if(isset($info_array['id'])){
			$this->setId($info_array['id']);
		}
	}
}