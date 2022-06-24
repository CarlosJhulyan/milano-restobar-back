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
Route::post('/usuario/updateUsuario', 'App\http\Controllers\UserController@updateUser');
Route::get('/usuario/getUsuarios', 'App\http\Controllers\UserController@getUsers');
Route::patch('/usuario/updateRolUsuario', 'App\http\Controllers\UserController@updateRolUser');
Route::delete('/usuario/deleteUsuario/{idusuario}', 'App\http\Controllers\UserController@deleteUser');
// Plate
Route::post('/plato/createPlato', 'App\http\Controllers\PlateController@createPlate');
Route::post('/plato/updatePlato', 'App\http\Controllers\PlateController@UpdatePlate');
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
Route::get('/receta/getRecetas', 'App\http\Controllers\RecipeController@getRecetas');
Route::post('/receta/createReceta', 'App\http\Controllers\RecipeController@createRecipe');
Route::post('/receta/deleteRecetas', 'App\http\Controllers\RecipeController@deleteRecetas');
Route::post('/receta/getRecetaPlato', 'App\http\Controllers\RecipeController@getPlateRecipe');

// Category
Route::post('/categoria/createCategoria', 'App\http\Controllers\CategoryController@createCategory');
Route::get('/categoria/getCategoria', 'App\http\Controllers\CategoryController@getCategory');

// Ingredient
Route::post('/ingrediente/createIngrediente', 'App\http\Controllers\IngredientController@createIngredient');
Route::post('/ingrediente/updateIngrediente', 'App\http\Controllers\IngredientController@updateIngredient');
Route::get('/ingrediente/getIngredientes', 'App\http\Controllers\IngredientController@getIngredients');

// Menu
Route::post('/carta/createCarta', 'App\http\Controllers\MenuController@createMenu');
Route::get('/carta/getCartas', 'App\http\Controllers\MenuController@getMenus');
Route::post('/carta/deleteCarta', 'App\http\Controllers\MenuController@deleteMenu');
Route::post('/carta/getCartasPorRestaurante', 'App\http\Controllers\MenuController@getMenusByRestaurant');


// Restaurant
Route::post('/restaurante/createRestaurantes', 'App\http\Controllers\RestaurantController@createRestaurants');
Route::get('/restaurante/getRestaurantes', 'App\http\Controllers\RestaurantController@getRestaurants');
Route::post('/restaurante/getMesasPorRestaurante', 'App\http\Controllers\RestaurantController@getTablesByRestaurant');
Route::delete('/restaurante/deleteRestaurantes/{idrestaurant}', 'App\http\Controllers\RestaurantController@deleteRestaurants');

Route::get('/getFormaPago', 'App\http\Controllers\Controller@getCoinType');
