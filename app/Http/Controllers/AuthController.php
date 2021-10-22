<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// importamos los Facades
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

// importamos el modelo Producto
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password_confirmed' => 'required',
            'rol' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Error de validacion'], 400); // OJITO: revisar codigo de respuesta http
        } else {
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);

            // accesstoken devuelve el token en texto plano
            $accessToken = $user->createToken('authToken')->accessToken;

            // INICIO CODIGO BIJUU
            return response([
                'user' => $user,
                'access_token' => $accessToken,
                'message' => 'Creado exitosamente'
            ], 201);
            // FIN CODIGO BIJUU

            // INICIO CODIGO RODRIGO
            return response([
                'mensaje' => 'Usuario creado'
            ], 201);
            // FIN CODIGO RODRIGO
        }

        return response(['error' => $validator->errors(), 'message' => 'Error asdf'], 500);
    }

    public function login(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Error de validacion'], 400); // OJITO: revisar codigo de respuesta http
        }

        // attempt: devolvera verdadero si la autenticación fue exitosa. De lo contrario, se devolverá falso.
        if (Auth::attempt($data)) {

            /** @var \App\Models\User */
            $user = Auth::user();

            $tokenResult = $user->createToken('authToken', [$user->rol]);
            $token = $tokenResult->token;

            // RES TOKEN
            // {
            //     "id": "e4c1a4b30c5b00276850942add8604c1fece3c191b205209713177e0944adddc738a0668d03eecf6",
            //     "user_id": 3,
            //     "client_id": 1,
            //     "name": "authToken",
            //     "scopes": [
            //         "vendedor"
            //     ],
            //     "revoked": false,
            //     "created_at": "2021-10-13 08:39:13",
            //     "updated_at": "2021-10-13 08:39:13",
            //     "expires_at": "2022-10-13T08:39:13.000000Z"
            // }

            if ($request->remember_me) {
                $token->expires_at = $token->expires_at; // agregar cantidad de tiempo extra (CARBON)
            }
            // return $token;

            $accessToken = $tokenResult->accessToken;

            // INICIO CODIGO BIJUU
            return response([
                'user' => Auth::user(),
                'access_token' => $accessToken,
                'message' => 'Credenciales validas'
            ], 201);
            // FIN CODIGO BIJUU

            // INICIO CODIGO RODRIGO
            return response([
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'rol' => $user->rol,
                'expires_at' => "ASDF"
            ], 201);
            // FIN CODIGO RODRIGO

        } else {
            return response(['message' => "Credenciales invalidas"], 400); // OJITO: revisar codigo de respuesta http
        }
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $user->token()->revoke();
        return response(['message' => 'Desconexion exitosa'], 200);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
