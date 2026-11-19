<?php

use App\Http\Controllers\VersionController;

/** @var \App\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell the framework the URIs it should respond to
| and the controller method to call when that URI is requested.
|
*/

$router->get('/', [
    'as' => 'version',
    'uses' => [VersionController::class, 'version'],
]);

/*
| Use this command to generate the crud files for the below routes:
| php run make:api-resource
|     {resourceName} {--decorated} {--table=} {--connection=} {--composed} {--connectionAsModelFolder}
|
| Make sure you have the table in DB before running the command, if you want the columns to be included in the model.
*/

//foreach (
//    \MacropaySolutions\CrufdWizard\Helpers\ResourceHelper::getResourceNameToControllerFQNMap(
//        \App\Support\DbCrudMap::MODEL_FQN_TO_CONTROLLER_MAP
//    ) as $resource => $controllerFqn
//) {
//    //$router->get('/' . $resource . '/{identifier}/{relation}', [
//    //    'as' => $resource . '.listRelated',
//    //    'uses' => [$controllerFqn, 'listRelation'],
//    //]); // paid version only
//    $router->get('/' . $resource, [
//        'as' => $resource . '.list',
//        'uses' => [$controllerFqn, 'list'],
//    ]);
//    //$router->post('/' . $resource . '/{identifier}/{relation}/l/i/s/t', [
//    //    'as' => $resource . '.post_listRelated',
//    //    'uses' => [$controllerFqn, 'listRelation'],
//    //]); // paid version only
//    // or
//    //$router->query('/' . $resource . '/{identifier}/{relation}', [
//    //    'as' => $resource . '.query_listRelated',
//    //    'uses' => [$controllerFqn, 'listRelation'],
//    //]); // paid version only
//    //$router->post('/' . $resource . '/l/i/s/t', [
//    //    'as' => $resource . '.post_list',
//    //    'uses' => [$controllerFqn, 'list'],
//    //]);
//    // or
//    $router->query('/' . $resource, [
//        'as' => $resource . '.query_list',
//        'uses' => [$controllerFqn, 'list'],
//    ]);
//    $router->post('/' . $resource, [
//        'as' => $resource . '.create',
//        'uses' => [$controllerFqn, 'create'],
//    ]);
//    $router->put('/' . $resource . '/{identifier}', [
//        'as' => $resource . '.update',
//        'uses' => [$controllerFqn, 'update'],
//    ]);
//    $router->get('/' . $resource . '/{identifier}', [
//        'as' => $resource . '.get',
//        'uses' => [$controllerFqn, 'get'],
//    ]);
//    $router->delete('/' . $resource . '/{identifier}', [
//        'as' => $resource . '.delete',
//        'uses' => [$controllerFqn, 'delete'],
//    ]);
//
//    $router->get('/' . $resource . '/{identifier}/{relation}/{relatedIdentifier}', [
//        'as' => $resource . '.getRelated',
//        'uses' => [$controllerFqn, 'getRelated'],
//    ]);
//    $router->put('/' . $resource . '/{identifier}/{relation}/{relatedIdentifier}', [
//        'as' => $resource . '.updateRelated',
//        'uses' => [$controllerFqn, 'updateRelated'],
//    ]);
//    $router->delete('/' . $resource . '/{identifier}/{relation}/{relatedIdentifier}', [
//        'as' => $resource . '.deleteRelated',
//        'uses' => [$controllerFqn, 'deleteRelated'],
//    ]);
//}

/*
| for decorated routes add to each of the above routes one of:
| 'middleware' => \App\Http\Middleware\ModelExampleMiddleware::class . ':list'
| 'middleware' => \App\Http\Middleware\Decorators\{PluralModelName}Middleware::class . ':get'
| 'middleware' => \App\Http\Middleware\Decorators\{PluralModelName}Middleware::class . ':getRelated'
| 'middleware' => \App\Http\Middleware\Decorators\{PluralModelName}Middleware::class . ':update'
| 'middleware' => \App\Http\Middleware\Decorators\{PluralModelName}Middleware::class . ':updateRelated'
| 'middleware' => \App\Http\Middleware\Decorators\{PluralModelName}Middleware::class . ':create'
| 'middleware' => \App\Http\Middleware\Decorators\{PluralModelName}Middleware::class . ':delete'
| 'middleware' => \App\Http\Middleware\Decorators\{PluralModelName}Middleware::class . ':deleteRelated'
*/
