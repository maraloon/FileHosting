<?php
namespace FileHosting\Helpers;
/**
 * Получает тип и текст сообщения снаружи
 */
class OutMessager extends Messager{
    private $type;
    private $text;

    public function getText(){
        return $this->text;
    }
    public function setText($text){
        $this->text=$text;   
    }

    public function getType(){
        return $this->type;
    }
    public function setType($type){
        $this->type=$type;    
    }
}