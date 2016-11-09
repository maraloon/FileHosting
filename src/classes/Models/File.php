<?php
namespace FileHosting\Models;

class File{
    protected $id;
    public $name;
    public $originalName;
    public $path;
    public $size;
    public $mime;
    public $mediaInfo;
    public $time;
    public $numberOfDownloads;
    public $description;
    public $userId;
    
    const DESCRIPTION_MAX_LENGTH=3000;

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

    public function getMediaInfoArray(){
        return json_decode($this->mediaInfo,true);
    }

    public function getType(){
        $pieces=explode("/", $this->mime);
        return $pieces[0];
    }

    public function getFormat(){
        $pieces=explode("/", $this->mime);
        return $pieces[1];
    }
}