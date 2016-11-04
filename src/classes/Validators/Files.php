<?php
namespace FileHosting\Validators;
use FileHosting\Models\File;

class Files extends Validator{
    public function descriptionValidate($description){
        $length=File::DESCRIPTION_MAX_LENGTH;
        if (mb_strlen($description)<=$length) {
            return true;
        }
        $this->errors[]='Длина описания файла превышает '.$length.' знаков';
        
        return $this->errors;
    }
}