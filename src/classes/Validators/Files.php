<?php
namespace FileHosting\Validators;
class Files extends Validator{
    public function descriptionValidate($description){
        $length=3000;
        if (mb_strlen($description)<=$length) {
            return true;
        }
        $this->errors[]='Длина описания файла превышает '.$length.' знаков';
        
        return $this->errors;
    }
}