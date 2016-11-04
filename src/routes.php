<?php
use FileHosting\Helpers\CodeMessager;
use FileHosting\Helpers\Helper;
use FileHosting\Helpers\OutMessager;
use FileHosting\Models\Comment;
use FileHosting\Models\File;
use FileHosting\Models\User;

//Определяем переменные, которые используются в >1 роутере
function init_vars($container){
    $vars['pathToPublic']=$container->settings['pathToPublic'];
    return $vars;
};

/**
 * Главная страница
 * Представление: Загрузка файла на сервер
 */
$app->get('/[message/{code}]', function ($request, $response, $args) {
    $args=array_merge($args,init_vars($this));
    //Если есть сообщение, формируем его
    if (isset($args['code'])) {
        $messager=new CodeMessager();
        $isValidCode=$messager->setCode($args['code']);
        if ($isValidCode) {
            $args['messagers']['messager']=$messager;
        } 
    }

    //return $this->view->render($response, 'upload_nojs.html', $args); //когда нужно тестировать процесс загрузки
    return $this->view->render($response, 'upload.html', $args);
})->setName('main');



/**
 * Процесс: Загрузка файла на сервер
 */
$app->post('/upload', function ($request, $response, $args) {
    //Копируем файл на сервер
    $status=$this->filesFM->addFile($_FILES['file']['name'],$this->settings['uploadFolder']);

    if ($status) {
        //Создаём модель
        $fileModel=new File();
        $fileModel->size=$_FILES['file']['size'];
        $fileModel->originalName=$this->filesFM->getOriginalName();
        $fileModel->name=$this->filesFM->getName();
        $fileModel->path=$this->filesFM->getPath();
        $fileModel->mime=mime_content_type(Helper::getPathForFile($this->settings['uploadFolder'],$fileModel));
        //Устанавливаем связь файлов с пользователями
        $userModel=new User();
        
        $needNewToken=false;
        if (isset($_COOKIE['userToken'])) {
            $userModel->token=$_COOKIE['userToken'];
            $isValidToken=$userModel->setId($this->usersGW->getIdByToken($_COOKIE['userToken']));
            if (!$isValidToken) {
                $needNewToken=true;
            }
        }
        //Добавляем новый токен
        if ($needNewToken) {
            $userModel->token=Helper::randHash();
            setcookie('userToken',$userModel->token);
            $userModel->setId($this->usersGW->addUser($userModel));
        }

        $fileModel->userId=$userModel->getId();

        //Записываем в БД
        $fileId=$this->filesGW->addFile($fileModel);
        setcookie('fileId',$fileId);

    }

    //Решаем, что вернуть
    if (isset($_POST['nojs'])) {
        if ($status){
            $url=$this->router->pathFor('add_info');
        }
        else{
            $url=$this->router->pathFor('main',['code'=>'upload_failed']);
        }
        $response = $response->withRedirect($url);
        return $response;
    }
    return json_encode($status);
})->setName('upload');


/**
 * Представление: Добавить описание файла после его загрузки
 * 
 * Вызывается при onComplete загрузчика dmuploader.js
 */
$app->map(['GET', 'POST'],'/add_info', function ($request, $response, $args) {
    $args=array_merge($args,init_vars($this));
    //Если юзер уже добавил и отправил описание
    if (  (isset($_COOKIE['userToken']))  and  (isset($_COOKIE['fileId'])) and (isset($_POST['description'])) ) {

        $userId['users']=$this->usersGW->getIdByToken($_COOKIE['userToken']);
        $userId['files']=$this->filesGW->getFile($_COOKIE['fileId'])->userId;

        //Имеет ли пользователь право добавлять описание
        if ($userId['users']==$userId['files']) {
            //Добавляем описание
            $validErrors=$this->filesValidator->descriptionValidate($_POST['description']);
            
            if ($validErrors===true) {
                $this->filesGW->addDescription($_COOKIE['fileId'],$_POST['description']);
                //Перенаправляем на страницу файла
                $url=$this->router->pathFor('show_file',['id'=>$_COOKIE['fileId']]);
            }
            else{
                foreach ($validErrors as $error) {
                    $messager=new OutMessager();
                    $messager->setType(OutMessager::TYPE_ERROR);
                    $messager->setText($error);
                    $args['messagers'][]=$messager;
                }
            }

        }
        else{
            //У вас нет прав на изменение данного файла. Вы не его создатель
            $url=$this->router->pathFor('main',['code'=>'access_denied']);
        }
        setcookie('fileId','',time()-3600);
    }

    $args['descriptionMaxLength']=File::DESCRIPTION_MAX_LENGTH;
    //Перенаправить или отобразить
    if (isset($url)) {
        $response = $response->withRedirect($url);
        return $response; 
    }
    else{
        return $this->view->render($response, 'add_info.html', $args);
    }
})->setName('add_info');



/**
 * Последние загрузки
 */
$app->get('/files_list', function ($request, $response, $args){
    $args=array_merge($args,init_vars($this));

    $args['uploadUri']=$this->settings['uploadUri'];
    $args['files']=$this->filesGW->getLastFiles(100);

    return $this->view->render($response, 'files_list.html', $args);
})->setName('files_list');



/**
 * Просмотр файла/Добавление комментария
 */
$app->map(['GET', 'POST'],'/show_file/{id}', function ($request, $response, $args){
    $args=array_merge($args,init_vars($this));

    //Добавление комментария
    if (isset($_POST['comment'])) {
        //Создаём объект Comment
        $comment=new Comment();

        $comment->text=$_POST['comment'];
        $comment->fileId=$_POST['fileId'];
        if (isset($_POST['parentId'])) {
            $comment->parentId=$_POST['parentId'];
        } else {
            $comment->parentId=NULL;
        }
        if ($_POST['nick']==NULL) {
            $comment->nick=Comment::DEFAULT_NICKNAME;
        } else {
            $comment->nick=$_POST['nick'];
        }


        //Валидация
        $validErrors=$this->commentsValidator->validate($comment);

        if ($validErrors===true) {
            //Записываем в БД
            $this->commentsGW->addComment($comment);
        }
        else{
            foreach ($validErrors as $error) {
                $messager=new OutMessager();
                $messager->setType(OutMessager::TYPE_ERROR);
                $messager->setText($error);
                $args['messagers'][]=$messager;
            }
        }
    }

    //Вывод страницы
    $fileModel=$this->filesGW->getFile($args['id']);
    if ($fileModel) {
        $comments=$this->commentsGW->getComments($args['id']);
        $fileUri=Helper::getPathForFile($this->settings['uploadUri'],$fileModel);
        $filePath=Helper::getPathForFile($this->settings['uploadFolder'],$fileModel);

        $args['id3']=$this->ID3Choser->getTags($filePath,$fileModel->getType());

        $args['fileUri']= $fileUri;
        $args['comments']=$comments;
        $args['file']=$fileModel;
        $args['defaultNickname']=Comment::DEFAULT_NICKNAME;
    }
    else{
        $args['file']=false;
        throw new \Slim\Exception\NotFoundException($request,$response);

    }
    return $this->view->render($response, 'show_file.html', $args);
})->setName('show_file');



/**
 * Загрузка файла на устройство
 */
$app->get('/download/{id}/{name}', function ($request, $response, $args) {
    $this->logger->info("Загрузка файла");
    //$args=array_merge($args,init_vars($this));
    
    $fileModel=$this->filesGW->getFile($args['id']);
    //Счётчик загрузок +1
    $this->filesGW->incrementNumberOfDownloads($args['id']);


    $path=Helper::getPathForFile($this->settings['uploadFolder'],$fileModel);
    $path=realpath($path);
    $path=str_replace('\\', '/', $path); //Что-бы и под linux'ом работало
    if (file_exists($path)) {
        $response = $response->withHeader('Content-Type', mime_content_type($path));
        //$response = $response->withHeader('Content-Disposition','attachment; filename=' . $args['name']);

        $response = $response->withHeader('Content-Disposition','attachment; filename*'.$args['name']);
        $response = $response->withHeader('Content-Length', filesize($path));

        if (in_array('mod_xsendfile', apache_get_modules())) {
            //Средствами XSendFile
            $response = $response->withHeader('X-SendFile', $path);
        } else {
            //Средствами PHP
            $file=readfile($path);

        /*
            //Средствами Apache
            $uri=urlencode(Helper::getPathForFile($this->settings['uploadUri'],$fileModel));
            $uri=str_replace ( '%2F','/', $uri );
            $response = $response->withRedirect($uri);
        */
        }
    }

    
    return $response;
})->setName('download');