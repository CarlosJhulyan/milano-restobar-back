<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/usuario/login', 'App\http\Controllers\AuthController@loginUser');
Route::post('/admin/login', 'App\http\Controllers\AuthController@loginAdmin');

// User
Route::post('/usuario/createUsuario', 'App\http\Controllers\UserController@createUser');
Route::put('/usuario/updateUsuario', 'App\http\Controllers\UserController@updateUser');
Route::get('/usuario/getUsuarios', 'App\http\Controllers\UserController@getUsers');
Route::patch('/usuario/updateRolUsuario', 'App\http\Controllers\UserController@updateRolUser');
Route::delete('/usuario/deleteUsuario/{idusuario}', 'App\http\Controllers\UserController@deleteUser');
// Plate
Route::post('/plato/createPlato', 'App\http\Controllers\PlateController@createPlate');
Route::get('/plato/getPlatos', 'App\http\Controllers\PlateController@getPlates');
Route::post('/plato/getPlatosCarta', 'App\http\Controllers\PlateController@getPlatesByCodMenu');

// Order
Route::post('/pedido/generarPedido', 'App\http\Controllers\OrderController@generateOrder');
Route::post('/pedido/getPedidosUsuario', 'App\http\Controllers\OrderController@getRecentOrdersByUser');
Route::patch('/pedido/updateEstadoPedido', 'App\http\Controllers\OrderController@changeStatusOrder');
Route::post('/pedido/getPedidosDetalles', 'App\http\Controllers\OrderController@getOrderDetailsByHeader');
Route::post('/pedido/getPedidosDetallesComp', 'App\http\Controllers\OrderController@getOrderDetailsComplete');
Route::get('/pedido/getPedidosAtendidos', 'App\http\Controllers\OrderController@getOrdersFulfilled');
Route::post('/pedido/generarFormaPagoPedido', 'App\http\Controllers\OrderController@generatePaymentFormOrder');

// Recipe
Route::post('/receta/createReceta', 'App\http\Controllers\RecipeController@createRecipe');
Route::post('/receta/getRecetaPlato', 'App\http\Controllers\RecipeController@getPlateRecipe');

// Category
Route::post('/categoria/createCategoria', 'App\http\Controllers\CategoryController@createCategory');

// Ingredient
Route::post('/ingrediente/createIngrediente', 'App\http\Controllers\IngredientController@createIngredient');
Route::get('/ingrediente/getIngredientes', 'App\http\Controllers\IngredientController@getIngredients');

// Menu
Route::post('/carta/createCarta', 'App\http\Controllers\MenuController@createMenu');
Route::get('/carta/getCartas', 'App\http\Controllers\MenuController@getMenus');
Route::post('/carta/deleteCarta', 'App\http\Controllers\MenuController@deleteMenu');
Route::post('/carta/getCartasPorRestaurante', 'App\http\Controllers\MenuController@getMenusByRestaurant');


// Restaurant
Route::get('/restaurante/getRestaurantes', 'App\http\Controllers\RestaurantController@getRestaurants');
Route::post('/restaurante/getMesasPorRestaurante', 'App\http\Controllers\RestaurantController@getTablesByRestaurant');


Route::get('/getFormaPago', 'App\http\Controllers\Controller@getCoinType');
