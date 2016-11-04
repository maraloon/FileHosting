<?php
namespace FileHosting\Helpers;
abstract class Messager{
    /**
     *  Для возможности отображения сообщений с правильным цветом значения констант TYPE_* должны соответствовать значениям классов div'ов из http://getbootstrap.com/components/#alerts
     */
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'danger';
    //const TYPE_INFO = 'info';
    abstract public function getText();
    abstract public function getType();
}