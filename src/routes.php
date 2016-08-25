<?php
use \FileHosting\Models;
use \FileHosting\Helpers\Helper;

$app->get('/[upload]', function ($request, $response, $args) {
    $this->logger->info("Главная страница");
    $args['project_folder']=$this->settings['project_folder'];

    return $this->view->render($response, 'upload.html', $args);
})->setName('main');


$app->post('/upload', function ($request, $response, $args) {
    $this->logger->info("Кнопка: отправить файл");

//Создаём объект FileModel
    $file=new Models\FileModel();

    $file->setName($_FILES['file_to_upload']['name']);
    $file->size=$_FILES['file_to_upload']['size'];
    $file->description=$_POST['description'];
 
//Копируем файл на сервер
    $args['status']=$this->files_FM->addFile($file,$this->settings['upload_folder']);  
//Записываем в БД
    if ($args['status']) {
        $this->files_GW->addFile($file);
    }

	//Представление
    return $this->view->render($response, 'upload.html', $args);
});


$app->get('/files_list', function ($request, $response, $args){
    $this->logger->info("Страница последних загрузок");

    $args['upload_folder']=$this->settings['upload_folder'];
    $args['files']=$this->files_GW->getLastFiles(100);

    return $this->view->render($response, 'files_list.html', $args);
})->setName('files_list');



$app->get('/show_file/{id}', function ($request, $response, $args){
    $this->logger->info("Просмотр файла");
    $args['project_folder']=$this->settings['project_folder'];

    $file=$this->files_GW->getFile($args['id']);
    if ($file!=NULL) {
        $args['file']=$file;
        $args['file_path']=$this->settings['upload_folder'].$file->path.$file->name;

        $comments=$this->comments_GW->getComments($args['id']);
        $args['comments']=$comments;
    }

    return $this->view->render($response, 'show_file.html', $args);
})->setName('show_file');





$app->get('/download/{id}', function ($request, $response, $args) {
    $this->logger->info("Загрузка файла");

    $file=$this->files_GW->getFile($args['id']);

    $url=$this->settings['upload_folder'];
    $url.=$file->path;
    $url.=$file->name;

    $response = $response->withHeader('Content-Disposition', 'attachment');
    $response = $response->withHeader('Location', $url);
    return $this->view->render($response, 'download.html', $args);
})->setName('download');


$app->post('/add_comment', function ($request, $response, $args){
    $this->logger->info("Кнопка: комментировать");

//Создаём объект CommentModel
    $comment=new Models\CommentModel();

    $comment->text=$_POST['comment'];
    $comment->nick=$_POST['nick'];
    $comment->file_id=$_POST['file_id'];
 
//Записываем в БД
    $this->comments_GW->addComment($comment);

    //Представление
    $url=$this->router->pathFor('show_file',['id'=>$_POST['file_id']]);
    $response = $response->withHeader('Location', $url);
    return $this->view->render($response, 'show_file.html', $args);
})->setName('add_comment');


/*
$comments = array(  '1' => array('Первый',0),
                    '2' => array('Второй',0),
                    '3' => array('Первый к первому',1),
                    '4' => array('Первый к 3',3),
                    '5' => array('Второй к 3',3),
                );

$comments = array(  [1] => array('text'=>'1',
                        'comments'=>array(
                                        [1]=>array(
                                            'text'=>'1->1',
                                            'comments'=>array(
                                                        [1]=array('text'=>'1->1->1','comments'=>array()
                                                                  )
                                                             )
                                                  )
                                          )
                                ),
                    

                    [2] => array('text'=>'2','comments'=>array()),

                );
*/