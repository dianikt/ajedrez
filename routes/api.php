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


Route::post('/auth', function(Request $request){
	
	$credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {  
       
		$rand_part = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'.uniqid());

		$user = \Auth::User();
		$email = $user['email'];
		$ok = User::where('email', $email)->update(['token' => $rand_part]);	
		return $user;
	}
	return "ko";

	//return redirect()->intended('dashboard');	
});