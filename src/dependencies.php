<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['templatePath']);
};

// view renderer twig
$container['view'] = function ($c) {
	$settings = $c->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['templatePath']);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));
    return $view;
};

/**
 * ErrorHandler
 */
/*$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Kalamba');
    };
};*/

/**
 * DataBase PDO Connection
 */
$container['db'] = function ($c) {
    $db = $c->get('settings')['db'];
    $pdo = new PDO(
        "mysql:host=".$db['host'].
        ";dbname=".$db['dbname'],
        $db['user'],
        $db['pass'],
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8, sql_mode='STRICT_ALL_TABLES'")
        );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


$container['filesGW'] = function ($c) {
    return new \FileHosting\DataBase\FilesTDGW($c['db']);
};

$container['filesFM'] = function ($c) {
    return new \FileHosting\FilesFileManager($c['db']);
};

$container['commentsGW'] = function ($c) {
    return new \FileHosting\DataBase\CommentsTDGW($c['db']);
};

$container['commentsSorter'] = function ($c) {
    return new \FileHosting\Models\CommentsSorter();
};

/**
 * 
 */
$container['ID3Choser'] = function ($c) {
    return new \FileHosting\Helpers\ID3Choser();
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};
