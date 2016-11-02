<?php

//$pathToPublic='filehosting2/public';
$pathToPublic='';

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'templatePath' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        //Path of uploaded files
        'pathToPublic'    => "$pathToPublic",
        //'pathToPublic'    => "$pathToPublic/public/",
        'uploadUri'=>"$pathToPublic/files/",
        'uploadFolder'=>__DIR__ ."/../public/files/",

        //Типы файлов пропускаемые без фильтрации. К остальным будет добавлено .txt для безопасности
        'validTypes'=> [
        'image' => 'jpg|jpeg|png|gif|tiff',
        'audio' => 'mp3|ogg|wav|wv|aac|aiff|ape|flac|dts|wma|midi',
        'video' => 'mp4|avi|wmv|webm|3gp',
        'text' => 'txt|doc|docx|pdf|rtf|xls'
        ],

        //PDO Connection
        'db'=> [
            'host'=>'localhost',
            'dbname'=>'filehosting',
            'user'=>'root',
            'pass'=>'',
        ]
    ],
];
