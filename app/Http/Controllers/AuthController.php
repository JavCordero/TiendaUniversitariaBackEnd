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
            'email' => 'required',
            'password_confirmed' => 'required',
            'nombre' => 'required',
            'rut' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Error de validacion'], 400); // OJITO: revisar codigo de respuesta http
        } else {
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);

            // accesstoken devuelve el token en texto plano
            $accessToken = $user->createToken('authToken')->accessToken;

            return response([
                'user' => $user,
                'access_token' => $accessToken,
                'message' => 'Creado exitosamente'
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Error de validacion'], 400); // OJITO: revisar codigo de respuesta http
        }

        // attempt: devolvera verdadero si la autenticación fue exitosa. De lo contrario, se devolverá falso.
        if (Auth::attempt($data)) {

            /** @var \App\Models\User */
            $user = Auth::user();
            $accessToken = $user->createToken('authToken', [$user->rol])->accessToken;
            return response([
                'user' => Auth::user(),
                'access_token' => $accessToken,
                'message' => 'Credenciales validas'
            ], 201);
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
