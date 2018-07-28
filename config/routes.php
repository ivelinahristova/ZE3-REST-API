<?php
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Action\HomePageAction::class, 'home');
 * $app->post('/album', App\Action\AlbumCreateAction::class, 'album.create');
 * $app->put('/album/:id', App\Action\AlbumUpdateAction::class, 'album.put');
 * $app->patch('/album/:id', App\Action\AlbumUpdateAction::class, 'album.patch');
 * $app->delete('/album/:id', App\Action\AlbumDeleteAction::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Action\ContactAction::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

/** @var \Zend\Expressive\Application $app */

use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

$app->get('/', App\Action\HomePageAction::class, 'home');
$app->get('/contracts', App\Action\GetContractsAction::class, 'contracts');
$app->post('/contracts', App\Action\CreateContractAction::class, 'contracts.create');
$app->patch('/contracts/:number', [
        BodyParamsMiddleware::class,
        App\Action\UpdateContractAction::class
    ], 'contracts.update');
$app->delete('/contracts/:number', [App\Action\DeleteContractAction::class], 'contracts.delete');
