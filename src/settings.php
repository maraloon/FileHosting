<?php

$projectFolder='filehosting2';

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
        'projectFolder'    => "/$projectFolder",
        'uploadUri'=>"/$projectFolder/public/files/",
        'uploadFolder'=>__DIR__ ."/../public/files/",

        //PDO Connection
        'db'=> [
            'host'=>'localhost',
            'dbname'=>'filehosting',
            'user'=>'root',
            'pass'=>'',
        ]
    ],
];
