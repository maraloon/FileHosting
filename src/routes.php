<?php

$app->get('/[upload]', function ($request, $response, $args) {
    var_dump($args);
    // Sample log message
    $this->logger->info("/[upload]");

    // Render index view
    return $this->renderer->render($response, 'upload.phtml', $args);
});