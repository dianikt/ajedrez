<?php

use Illuminate\Http\Request;
use App\User;
use App\partidas;
use App\fichas;


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


/*
*Devuelve la lista de jugadores menos el que acaba de iniciar sesion
*/
Route::POST('/eligeJugador', function(Request $request) {

	$jugador = $request->only('idJugador1');

    $jugadores = User::where('id', '<>', $jugador['idJugador1'] )->get();
   
    return response()->json(
        [               
          'jugadores' => $jugadores
    ]);
});

/*
* Inicia Partida una vez haya elegido un jugador con quien jugar
*/     
Route::POST('/initPartida', function(Request $request) {

	$datosPartida = $request->only('idJugador1', 'idJugador2', 'init');

	try {
        $partida = new partidas;
            $partida['jugador1'] = $datosPartida['idJugador1']; 
            $partida['jugador2'] = $datosPartida['idJugador2'];               
            $partida['estados'] = $datosPartida['init'];
        $partida->save();

        //jugador que ha iniciado sesion
        $jugadorIni = $datosPartida['idJugador1']; 
        //jugador elegigo de la lista
        $jugadorEle = $datosPartida['idJugador2'];

        posicionInicialFichas($partida['id'], $jugadorIni, $jugadorEle);

        $estado = "Partida Init";
        $mensaje = "A Jugar !! ";

    } catch (Exception $e) {
        $estado = "Partida Exit";
        $mensaje = $e->getMessage();
    }

    return response()->json(
        [               
          'estado' => $estado, 
          'mensaje' => $mensaje,
          'partida' => $partida
    ]);
});

function posicionInicialFichas($idPartida, $jugadorIni, $jugadorEle ){
	
    $fichasJug1 = new fichas;
        $fichasJug1['jugador'] = $jugadorIni;
        $fichasJug1['idPartida'] = $idPartida;         
        $fichasJug1['fila'] = 'A4';
        $fichasJug1['columna'] = 'A4';
    $fichasJug1->save();

    $fichasJug2 = new fichas;
        $fichasJug2['jugador'] = $jugadorEle;
        $fichasJug2['idPartida'] = $idPartida;         
        $fichasJug2['fila'] = 'H5';
        $fichasJug2['columna'] = 'H5';
    $fichasJug2->save();
    $estado = "Iniciamos partida ! ";     
}


