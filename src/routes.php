<?php

$app->get('/[upload]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("/[upload]");

    // Render index view
    return $this->view->render($response, 'upload.html', $args);
});




$app->post('/upload', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Нажата кнопка отправить в upload.html");

	// Каталог, в который мы будем принимать файл:
	$uploaddir='../files/'.date("Y").'/'.date("m").'/'.date("d").'/';
	$file_to_upload = $uploaddir.basename($_FILES['file_to_upload']['name']);

	if (!file_exists($uploaddir)) {
		mkdir($uploaddir,0777,true);
	}
	//Делаем исполняемые неисполняемыми
	if (preg_match("/^(\w|\s)*(.)(php|phtml|html|js)$/iu", $_FILES['file_to_upload']['name'])){
		$file_to_upload.='.txt';
	}
	// Копируем файл из каталога для временного хранения файлов:
	$args['status']=false;
	if (copy($_FILES['file_to_upload']['tmp_name'], $file_to_upload)){
		$args['status']=true;
	}

	// Render index view
    return $this->view->render($response, 'upload.html', $args);
});