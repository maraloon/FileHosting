<?php
namespace FileHosting\Models;
class User{
    protected $id;
    public $token;


    public function setId($id){
        $this->id=$id;
    }
    public function getId(){
        return $this->id;
    }
}