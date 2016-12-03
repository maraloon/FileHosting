<?php
namespace FileHosting\Helpers;
/**
 * Получает код ошибки
 * Отправляет тип и текст соответствующие коду ошибки
 */
class CodeMessager extends Messager{
    private $messages = array(
        'upload_failed' =>array(self::TYPE_ERROR,'Загрузка не удалась'),
        'access_denied' =>array(self::TYPE_ERROR,'У вас нет прав на изменение данного файла. Вы не его создатель'),
        'token_not_valid' =>array(self::TYPE_ERROR,'Ошибка идентификации. Почистите куки'),
        'file_not_deleted_from_db' =>array(self::TYPE_ERROR,'Файл не удалён из БД'),
        'file_not_deleted_from_fs' =>array(self::TYPE_ERROR,'Файл не удалён из ФС'),
        'file_deleted' =>array(self::TYPE_SUCCESS,'Файл удалён')
    );
    /**
     * $code код сообщения из массива $messages
     * @var string
     */
    private $code;

    public function __construct(){
        foreach ($this->messages as $key => &$code) {
            $code['type']=$code[0];
            $code['text']=$code[1];
            unset($code[0]);
            unset($code[1]);
            unset($code);
        }
    }

    public function setCode($code){
        if (!array_key_exists($code, $this->messages)) {
            return false;
        }
        $this->code=$code;
        return true;
    }

    public function getText(){
        return $this->messages[$this->code]['text'];
    }

    public function getType(){
        return $this->messages[$this->code]['type'];
    }
}