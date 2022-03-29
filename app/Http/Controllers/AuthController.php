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

        auth('api')->logout();
        return response()->json(["mensagem" => "Logout efetuado com sucesso!"]);

    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return response()->json(["token" => $token]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
