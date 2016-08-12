<?php
use \FileHosting\Models;
use \FileHosting\Helpers\Helper;

$app->get('/[upload]', function ($request, $response, $args) {
    $this->logger->info("Главная страница");
    return $this->view->render($response, 'upload.html', $args);
});


$app->post('/upload', function ($request, $response, $args) {
    $this->logger->info("Кнопка: отправить файл");

    #var_dump($_FILES);
//Создаём объект FileModel
    $file=new Models\FileModel();

    $file->setName($_FILES['file_to_upload']['name']);
    $file->size=$_FILES['file_to_upload']['size'];
    $file->comment=$_POST['comment'];
    var_dump($file);
//Записываем в БД
    $this->filesGW->addFile($file);
//Копируем файл на сервер
    $args['status']=$this->filesFM->addFile($file); //FM - FileManager

	//Представление
    return $this->view->render($response, 'upload.html', $args);
});


$app->get('/show_files', function ($request, $response, $args) {
    $this->logger->info("Страница последних загрузок");

    $args['files']=$this->filesGW->getLastFiles(100);

    return $this->view->render($response, 'show_files.html', $args);
});