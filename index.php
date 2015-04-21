<?php
require 'vendor/autoload.php';
$app = new \Slim\Slim();
$app->get('/',
    function () {
        $index = new \Controller\IndexController();
        $index->display();
    });
$app->get('/dca',
    function (){
        $dca = new \Controller\DcaController();
        $dca->display();
    });
$app->post('/dca',
    function (){
        $dca = new \Controller\DcaController();
        $dca->generate();
    });

$app->get('/parametre',
    function(){
        $config = new \Controller\ParametreController();
        $config->display();
    });
$app->post('/parametre',
    function() {
        $config = new \Controller\ParametreController();
        $config->maj();
    });
$app->post('/api/resetConfig',
    function () use ($app) {
        $app->response->headers->set('Content-Type', 'application/json');
        $config = new \Controller\ParametreController();
        $config->resetConfig();
    });
$app->get('/api/fields',
    function() use ($app) {
        $app->response->headers->set('Content-Type', 'application/json');
        $config = new \Controller\ParametreController();
        $config->getFields();
    });
$app->post('/api/field',
    function () use ($app) {
        $app->response->headers->set('Content-Type', 'application/json');
        $config = new \Controller\ParametreController();
        $config->getFieldValue();
    });
$app->get('/api/evals',
    function() use ($app) {
        $app->response->headers->set('Content-Type', 'application/json');
        $config = new \Controller\ParametreController();
        $config->getEvals();
    });
$app->post('/api/eval',
    function () use ($app) {
        $app->response->headers->set('Content-Type', 'application/json');
        $config = new \Controller\ParametreController();
        $config->getEvalValue();
    });
$app->notFound(function () {
    $index = new \Controller\IndexController();
    $index->display();
});

$app->run();