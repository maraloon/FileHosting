<?php
use \FileHosting\Models;
use \FileHosting\Helpers\Helper;

$app->get('/[upload]', function ($request, $response, $args) {
    $this->logger->info("Главная страница");
    $args['projectFolder']=$this->settings['projectFolder'];

    return $this->view->render($response, 'upload.html', $args);
})->setName('main');





$app->post('/upload', function ($request, $response, $args) {
    $this->logger->info("Кнопка: отправить файл");

//Создаём объект FileModel
    $file=new Models\FileModel();

    $file->setName($_FILES['fileToUpload']['name']);
    $file->size=$_FILES['fileToUpload']['size'];
    $file->description=$_POST['description'];
 
//Копируем файл на сервер
    $args['status']=$this->filesFM->addFile($file,$this->settings['uploadUri']);  
//Записываем в БД
    if ($args['status']) {
        $this->filesGW->addFile($file);
    }

	//Представление
    return $this->view->render($response, 'upload.html', $args);
});




$app->get('/files_list', function ($request, $response, $args){
    $this->logger->info("Страница последних загрузок");

    $args['uploadUri']=$this->settings['uploadUri'];
    $args['files']=$this->filesGW->getLastFiles(100);

    return $this->view->render($response, 'files_list.html', $args);
})->setName('files_list');





$app->get('/show_file/{id}', function ($request, $response, $args){
    $this->logger->info("Просмотр файла");
    $args['projectFolder']=$this->settings['projectFolder'];

    $file=$this->filesGW->getFile($args['id']);
    if ($file!=NULL) {
        $comments=$this->commentsGW->getComments($args['id']);
        $comments=$this->commentsSorter->sortComments($comments);

        $fileUri=Helper::getPathForFile($this->settings['uploadUri'],$file);

        if (in_array($file->getType(), array('audio','video'))) {
            $filePath=Helper::getPathForFile($this->settings['uploadFolder'],$file);
            $args['id3']=$this->ID3Choser->getTags($filePath,$file->getType());
        }
        
        $args['fileUri']= $fileUri;
        $args['comments']=$comments;
        $args['file']=$file;

    }

    return $this->view->render($response, 'show_file.html', $args);
})->setName('show_file');







$app->get('/download/{id}', function ($request, $response, $args) {
    $this->logger->info("Загрузка файла");

    $fileObject=$this->filesGW->getFile($args['id']);
 
    /*
    //Средствами PHP
    $path=Helper::getPathForFile($this->settings['uploadFolder'],$fileObject);
    $file=readfile($path);
    $response = $response->withHeader('Content-Description', 'File Transfer');
    $response = $response->withHeader('Content-Type', mime_content_type($path));
    $response = $response->withHeader('Content-Disposition', 'attachment; filename='.$fileObject->originalName);
    $response = $response->withHeader('Content-Transfer-Encoding', 'binary');
    $response = $response->withHeader('Expires','0');
    $response = $response->withHeader('Cache-Control', 'must-revalidate');
    $response = $response->withHeader('Pragma', 'public');
    $response = $response->withHeader('Content-Length', filesize($path));

    $body = $response->getBody();
    $body->write($file);
    return $response;*/

    //Средствами Apache
    $uri=urlencode(Helper::getPathForFile($this->settings['uploadUri'],$fileObject));
    $uri=str_replace ( '%2F','/', $uri );
    $response = $response->withRedirect($uri);
    return $response;
})->setName('download');





$app->post('/add_comment', function ($request, $response, $args){
    $this->logger->info("Кнопка: комментировать");

//Создаём объект CommentModel
    $comment=new Models\CommentModel();

    $comment->text=$_POST['comment'];
    $comment->nick=$_POST['nick'];
    $comment->fileId=$_POST['fileId'];
    $comment->parentId=$_POST['parentId'];
 
//Записываем в БД
    $this->commentsGW->addComment($comment);

    //Представление
    $url=$this->router->pathFor('show_file',['id'=>$_POST['fileId']]);
    $response = $response->withHeader('Location', $url);
    return $this->view->render($response, 'show_file.html', $args);
})->setName('add_comment');