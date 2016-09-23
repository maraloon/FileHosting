<?php
use \FileHosting\Models;
use \FileHosting\Helpers\Helper;

//Определяем переменные, которые используются в >1 роутере
function init_vars($container){
    $vars['projectFolder']=$container->settings['projectFolder'];
    return $vars;
};

$app->get('/[upload]', function ($request, $response, $args) {
    $this->logger->info("Главная страница");
    $args=array_merge($args,init_vars($this));
    return $this->view->render($response, 'upload.html', $args);
})->setName('main');




$app->post('/upload', function ($request, $response, $args) {
    $this->logger->info("Button: отправить файл");

    //Создаём объект FileModel
    $fileModel=new Models\FileModel();
    $fileModel->setName($_FILES['file']['name']);
    $fileModel->size=$_FILES['file']['size'];
 
    //Копируем файл на сервер
    $args['status']=$this->filesFM->addFile($fileModel,$this->settings['uploadUri']);

    if ($args['status']) {
        //Записываем в БД
        $fileId=$this->filesGW->addFile($fileModel);
        //Передаём пользователю id добавленного файла, чтобы он редактировал его в add_info
        $_SESSION['fileId']=$fileId;
    }
	//Представление
    return $this->view->render($response, 'upload_status.html', $args);
});




$app->get('/add_info', function ($request, $response, $args) {
    $this->logger->info("Добавление информации после загрузки");
    $args=array_merge($args,init_vars($this));

    $args['fileId']=$_SESSION['fileId'];
    return $this->view->render($response, 'add_info.html', $args); 
})->setName('add_info');




$app->post('/add_info', function ($request, $response, $args) {
    $this->logger->info("Button: добавить информацию");
    //Добавляем описание в таблицу
    $this->filesGW->addDescription($_SESSION['fileId'],$_POST['description']);
    //Перенаправляем на страницу файла
    $url=$this->router->pathFor('show_file',['id'=>$_SESSION['fileId']]);
    $response = $response->withRedirect($url);
    return $response;
});




$app->get('/files_list', function ($request, $response, $args){
    $this->logger->info("Страница последних загрузок");
    $args=array_merge($args,init_vars($this));

    $args['uploadUri']=$this->settings['uploadUri'];
    $args['files']=$this->filesGW->getLastFiles(100);

    return $this->view->render($response, 'files_list.html', $args);
})->setName('files_list');




$app->get('/show_file/{id}', function ($request, $response, $args){
    $this->logger->info("Просмотр файла");
    $args=array_merge($args,init_vars($this));

    $fileModel=$this->filesGW->getFile($args['id']);
    if ($fileModel!=NULL) {
        $comments=$this->commentsGW->getComments($args['id']);
        $comments=$this->commentsSorter->sortComments($comments);

        $fileUri=Helper::getPathForFile($this->settings['uploadUri'],$fileModel);
        $filePath=Helper::getPathForFile($this->settings['uploadFolder'],$fileModel);

        $args['id3']=$this->ID3Choser->getTags($filePath,$fileModel->getType());

        $args['fileUri']= $fileUri;
        $args['comments']=$comments;
        $args['file']=$fileModel;
        $args['fileNameForUrl']=rawurlencode($fileModel->originalName);
    }
    return $this->view->render($response, 'show_file.html', $args);
})->setName('show_file');




$app->get('/download/{id}/{name}', function ($request, $response, $args) {
    $this->logger->info("Загрузка файла");
    //$args=array_merge($args,init_vars($this));
    
    $fileModel=$this->filesGW->getFile($args['id']);

    //Счётчик загрузок +1
    $fileModel=$this->filesGW->incrementNumberOfDownloads($args['id']);
    /*
    //Средствами PHP
    $path=Helper::getPathForFile($this->settings['uploadFolder'],$fileModel);
    $file=readfile($path);
    $response = $response->withHeader('Content-Description', 'File Transfer');
    $response = $response->withHeader('Content-Type', mime_content_type($path));
    $response = $response->withHeader('Content-Disposition', 'attachment; filename='.$fileModel->originalName);
    $response = $response->withHeader('Content-Transfer-Encoding', 'binary');
    $response = $response->withHeader('Expires','0');
    $response = $response->withHeader('Cache-Control', 'must-revalidate');
    $response = $response->withHeader('Pragma', 'public');
    $response = $response->withHeader('Content-Length', filesize($path));

    $body = $response->getBody();
    $body->write($file);
    */


    //XSendFile
    $path=Helper::getPathForFile($this->settings['uploadFolder'],$fileModel);
    var_dump(realpath($path));
    var_dump(basename($path));
    if (file_exists($path)) {
        $response = $response->withHeader('X-SendFile', realpath($path));
        //$response = $response->withHeader('Content-Type','application/octet-stream');
        //$response = $response->withHeader('Content-Type', mime_content_type($path));
        $response = $response->withHeader('Content-Disposition','attachment; filename=' . $args['name']);


    }
    var_dump($response);
    /*
    //Средствами Apache
    $uri=urlencode(Helper::getPathForFile($this->settings['uploadUri'],$fileModel));
    $uri=str_replace ( '%2F','/', $uri );
    $response = $response->withRedirect($uri);*/
    

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