<?php

use Illuminate\Http\Request;
use App\User;
use App\partidas;

Route::post('/login', function(Request $request){
	
	$credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {  
       
		$rand_part = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'.uniqid());

		$user = \Auth::User();
		$ok = User::where('email', $user['email'])->update(['token' => $rand_part]);	
		return response()->json(['email' => $user['email'],'mensaje'=>'Usuario identificado!']);
	}else {
		return response()->json(['email' => $user['email'],'mensaje'=> 'Usuario No identificado!']);
	}
});

Route::POST('/logout', function(Request $request){
	
	$user = \Auth::User();
	$ok = User::where('email', $user['email'])->update(['token' => 0]);
	return response()->json(['mensaje' => 'Logout!!.']);
	
});

Route::POST('/initPartida', function(Request $request) {

  		$datosPartida = $request->only('idJugador1', 'idJugador2', 'init');

        try {
            $partida = new partidas;
                $partida['jugador1'] = $datosPartida['idJugador1'];
                $partida['jugador2'] = $datosPartida['idJugador2'];
                $partida['estados'] = $datosPartida['init'];
            $partida->save();

            //  generarPiezas($partida, $usuarioRival, $usuarioCreador);

            $estado = "Partida Init";
            $mensaje = "A Jugar !! ";

        } catch (Exception $e) {
            $estado = "Partida Exit";
            $mensaje = $e->getMessage();
        }
        return response()->json(
            ['estado' => $estado,
            'mensaje' => $mensaje,
            'partida' => $partida
        ]);
});

     
