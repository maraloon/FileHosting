<?php
namespace FileHosting\Models;
//use \FileHosting\Helpers\Helper;
class Comment{
    protected $id;
    public $path;
    public $fileId;
    public $parentId;
    public $text;
    public $nick;
    public $time;

    const DEFAULT_NICKNAME='Anon';

    function __construct(){
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

    public function getDeep(){
        //Длина поля y=3+(4*d), где d - глубина
        //d=(y-3)/4;
        return ((strlen($this->path))-3)/4;
    }
}