<?php

use Illuminate\Http\Request;
use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('partida')->match(array('GET'),'partida/{idPartida}', 'PartidaController@enviar');

Route::get('/auth/{name}', function($name){
	$users = User::where('name', $name)->select('token')->get();
	
	if($users[0]['token'] == 0){

		$rand_part = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'.uniqid());		
		
	$ok = User::where('name', $name)->update(['token' => $rand_part]);
		
	}else{
		return $users[0]['token'];
	}

});