<?php

/** @var \App\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell theFramework the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', [
    'as' => 'version',
    'uses' => 'VersionController@version',
]);

/*
| Uncomment in bootstrap/app.php:
|
|
| $app->withObvious();
|
| and use this command to generate the crud files for the below routes (uncomment $app->withObvious:
| php run make:api-resource
|     {resourceName} {--decorated} {--table=} {--connection=} {--composed} {--connectionAsModelFolder}
|
| Make sure you have the table in DB before running the command, if you want the columns to be included in the model.
*/

//foreach (
//    \MacropaySolutions\CrufdWizard\Helpers\ResourceHelper::getResourceNameToControllerFQNMap(
//        \Support\DbCrudMap::MODEL_FQN_TO_CONTROLLER_MAP
//    ) as $resource => $controllerFqn
//) {
//    $controllerFqnExploded = \explode('\\', $controllerFqn);
//    $controller = \end($controllerFqnExploded);
//    //$router->get('/' . $resource . '/{identifier}/{relation}', [
//    //    'as' => $resource . '.listRelated',
//    //    'uses' => $controller . '@listRelation',
//    //]); // paid version only
//    $router->get('/' . $resource, [
//        'as' => $resource . '.list',
//        'uses' => $controller . '@list',
//    ]);
//    //$router->post('/' . $resource . '/{identifier}/{relation}/l/i/s/t', [
//    //    'as' => $resource . '.post_listRelated',
//    //    'uses' => $controller . '@listRelation',
//    //]); // paid version only
//    // or
//    //$router->query('/' . $resource . '/{identifier}/{relation}', [
//    //    'as' => $resource . '.query_listRelated',
//    //    'uses' => $controller . '@listRelation',
//    //]); // paid version only
//    //$router->post('/' . $resource . '/l/i/s/t', [
//    //    'as' => $resource . '.post_list',
//    //    'uses' => $controller . '@list',
//    //]);
//    // or
//    $router->query('/' . $resource, [
//        'as' => $resource . '.query_list',
//        'uses' => $controller . '@list',
//    ]);
//    $router->post('/' . $resource, [
//        'as' => $resource . '.create',
//        'uses' => $controller . '@create',
//    ]);
//    $router->put('/' . $resource . '/{identifier}', [
//        'as' => $resource . '.update',
//        'uses' => $controller . '@update',
//    ]);
//    $router->get('/' . $resource . '/{identifier}', [
//        'as' => $resource . '.get',
//        'uses' => $controller . '@get',
//    ]);
//    $router->delete('/' . $resource . '/{identifier}', [
//        'as' => $resource . '.delete',
//        'uses' => $controller . '@delete',
//    ]);
//
//    $router->get('/' . $resource . '/{identifier}/{relation}/{relatedIdentifier}', [
//        'as' => $resource . '.getRelated',
//        'uses' => $controller . '@getRelated',
//    ]);
//    $router->put('/' . $resource . '/{identifier}/{relation}/{relatedIdentifier}', [
//        'as' => $resource . '.updateRelated',
//        'uses' => $controller . '@updateRelated',
//    ]);
//    $router->delete('/' . $resource . '/{identifier}/{relation}/{relatedIdentifier}', [
//        'as' => $resource . '.deleteRelated',
//        'uses' => $controller . '@deleteRelated',
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
