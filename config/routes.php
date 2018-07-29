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

$app->get('/', App\Action\Contracts\GetContractsAction::class, 'home');

/**
 * Contracts CRUD
 */
$app->get('/contracts', App\Action\Contracts\GetContractsAction::class, 'contracts');
$app->get('/contracts/:number', App\Action\Contracts\GetContractAction::class, 'contracts.get');
$app->post('/contracts', App\Action\Contracts\CreateContractAction::class, 'contracts.create');
$app->patch('/contracts/:number', [
        BodyParamsMiddleware::class,
        App\Action\Contracts\UpdateContractAction::class
    ], 'contracts.update');
$app->delete('/contracts/:number', [App\Action\Contracts\DeleteContractAction::class], 'contracts.delete');
$app->get('/contracts/:number/properties', App\Action\Contracts\GetPropertiesAction::class, 'contracts.properties.get');
/**
 * Properties CRUD
 */
$app->get('/properties', App\Action\Properties\GetPropertiesAction::class, 'properties');
$app->get('/properties/:id', App\Action\Properties\GetPropertyAction::class, 'properties.get');
$app->post('/properties', App\Action\Properties\CreatePropertyAction::class, 'properties.create');
$app->patch('/properties/:id', [
    BodyParamsMiddleware::class,
    App\Action\Properties\UpdatePropertyAction::class
], 'properties.update');
$app->delete('/properties/:id', [App\Action\Properties\DeletePropertyAction::class], 'properties.delete');

/**
 * Contract Properties CRUD
 */
//$app->get('/properties/:id', App\Action\Properties\GetPropertyAction::class, 'properties.get');
$app->post('/contract_properties', App\Action\ContractProperties\CreateContractPropertiesAction::class, 'contract_properties.create');
$app->delete('/contract_properties/:number/:property_id', [App\Action\ContractProperties\DeleteContractPropertiesAction::class], 'contract_properties.delete');

/**
 * Landlords CRUD
 */
$app->get('/landlords', App\Action\Landlords\GetLandlordsAction::class, 'landlords');
$app->get('/landlords/:id', App\Action\Landlords\GetLandlordAction::class, 'landlords.get');
$app->post('/landlords', App\Action\Landlords\CreateLandlordAction::class, 'landlords.create');
$app->delete('/landlords/:id', [App\Action\Landlords\DeleteLandlordAction::class], 'landlords.delete');
$app->patch('/landlords/:id', [
    BodyParamsMiddleware::class,
    App\Action\Landlords\UpdateLandlordAction::class
], 'landlords.update');


/**
 * Property Landlords CRUD
 * @todo: delete
 */
$app->post('/property_landlords', App\Action\PropertyLandlords\CreatePropertyLandlordsAction::class, 'property_landlords.create');
