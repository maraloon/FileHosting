<?php
namespace FileHosting\Validators;

use FileHosting\Models\Comment;

class Comments extends Validator
{

    protected $masks=array(
        'text'=>array(
            'type'=>'string',
            'regexp'=>"//",
            'min' => 1,
            'max' => Comment::COMMENT_MAX_LENGTH,
            'name' => 'Текст комментария',
            'message' => 'может состоять из любых символов' //по-идее, это никогда не выводится
        ),
        'nick'=>array(
            'type'=>'string',
            'regexp'=>"/^([a-z0-9][-]*)+$/iu",
            'min' => 1,
            'max' => Comment::NICKNAME_MAX_LENGTH,
            'name' => 'Ник',
            'message' => 'должен состоять из латиницы и цифр, может содержать знак "-"'
        ),

        'fileId'=>array(
            'name' => 'fileId',
            'type' => 'int',
            'required' => true
        ),
        'parentId'=>array(
            'name' => 'parentId',
            'type' => 'int',
            'required' => false
        ),
    );

    public function validate(Comment $c)
    {
        $masks=$this->masks;
        $e=array();

        foreach($masks as $field => $mask) {           
            if($mask['type']=='string') {

                if(!preg_match($mask['regexp'],$c->$field)) {
                    $e[]=$mask['name'].' '.$mask['message'];
                }
                if(mb_strlen($c->$field)<$mask['min']) {
                    $e[]=$mask['name'].' должно быть минимум '.$mask['min'].' символов';
                }
                if(mb_strlen($c->$field)>$mask['max']) {
                    $e[]=$mask['name'].' должно быть максимум '.$mask['max'].' символов.';
                }
                
            } elseif($mask['type']=='int') {

                if ( ($mask['required']) and ($c->$field==NULL)) {
                        $e[]='Поле '.$mask['name'].' не может быть пустым';
                }

                if ($c->$field!=NULL) {
                    if((!is_numeric($c->$field))) {
                        $e[]='Поле '.$mask['name'].' должно быть числом';
                    }
                }

            }
        }


        if (empty($e)) {
           return true;
        }
        return $e;
    }
}