<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->group('/api', function () use ($app) {
    // Version group
    $app->group('/v1', function () use ($app) {

        $app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
            $name = $args['name'];
            $response->getBody()->write("Hello, $name");
            return  $response;
         });

        /** Products CRUD */
        $app->get('/product', 'ProductCtl::getAll');
        $app->get('/product/{id}', 'ProductCtl::get');
        $app->post('/product', 'ProductCtl::create');
        $app->put('/product/{id}', 'ProductCtl::update');
        $app->delete('/product/{id}', 'ProductCtl::delete');


        /** Products CRUD */
        $app->get('/list', 'CheckListCtl::getAll');
        $app->get('/list/{id}', 'CheckListCtl::get');
        $app->post('/list', 'CheckListCtl::create');
        $app->put('/list/{id}', 'CheckListCtl::update');
        $app->delete('/list/{id}', 'CheckListCtl::delete');


        /*** Tests */
        $app->get('/test', 'workingPage');
        $app->get('/test/{token}', 'workingPage2');

    });
});

?>
