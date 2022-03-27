<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciais = $request->all('email','password');

        $token = auth('api')->attempt($credenciais);

        if ($token) {

            return response()->json(['mensagem' => "Login efetuado com sucesso!", 'token' => $token],200);
        
        } else {

            return response()->json(['erro' => "Erro ao efetuar o login"], 403);

        }

    }

    public function logout()
    {
        return 'logout';
    }

    public function refresh()
    {
        return 'refresh';
    }

    public function me()
    {
        return 'me';
    }
}
