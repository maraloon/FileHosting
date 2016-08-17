<?php
use \FileHosting\Models;
use \FileHosting\Helpers\Helper;

$app->get('/[upload]', function ($request, $response, $args) {
    $this->logger->info("Главная страница");

    return $this->view->render($response, 'upload.html', $args);
})->setName('main');


$app->post('/upload', function ($request, $response, $args) {
    $this->logger->info("Кнопка: отправить файл");

//Создаём объект FileModel
    $file=new Models\FileModel();

    $file->setName($_FILES['file_to_upload']['name']);
    $file->size=$_FILES['file_to_upload']['size'];
    $file->comment=$_POST['comment'];
    
//Записываем в БД
    $this->filesGW->addFile($file);
//Копируем файл на сервер
    $args['status']=$this->filesFM->addFile($file,$this->settings['upload_folder']); //FM - FileManager

	//Представление
    return $this->view->render($response, 'upload.html', $args);
});


$app->get('/show_files', function ($request, $response, $args){
    $this->logger->info("Страница последних загрузок");

    $args['upload_folder']=$this->settings['upload_folder'];
    $args['files']=$this->filesGW->getLastFiles(100);

    return $this->view->render($response, 'show_files.html', $args);
})->setName('show_files');



$app->get('/download/{id}', function ($request, $response, $args) {
    $this->logger->info("Загрузка файла");
    
    $file=$this->filesGW->getFile($args['id']);
    
    $url=implode('/',explode('/', $_SERVER['PHP_SELF'],-1)).'/';
    $url.=$this->settings['upload_folder'];
    $url.=$file->path;
    $url.=$file->name;

    $response = $response->withHeader('Content-Disposition', 'attachment');
    $response = $response->withHeader('Location', $url);
    return $this->view->render($response, 'download.html', $args);
})->setName('download');