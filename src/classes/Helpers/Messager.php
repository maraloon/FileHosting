<?php
namespace FileHosting\Helpers;
abstract class Messager{
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    abstract public function getText();
    abstract public function getType();
}