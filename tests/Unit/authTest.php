<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
//use Illuminate\Testing\TestResponse;

class authTest extends TestCase
{
    /**
     * Test que simula el inicio de sesion de un usuario.
     *
     * @return void
     */
    public function test_user_login()
    {
        $datos_usuario = [
            'email'=> 'mikasa@gmail.com',
            'password'=> '123123'
        ]; 
        
        $this->json('POST', 'api/login', $datos_usuario , ['Accept' => 'application/json'])
        ->assertStatus(201);
    }

    public function test_register_user()
    {
        
        $usuario = [
            'name' => 'Luciano',
            'email' => 'luciano@ucn.cl',
            'password' => '123123',
            'password_confirmed' => '123123',
            'rol' => 'administrador'
        ];
        
        $user = User::find('1');
        $access_token = $user->createToken('authToken', ['administrador'])->accessToken;
        $authorization = 'Bearer ' . $access_token;

        $this->json('POST', 'api/register', $usuario , ['Accept' => 'application/json', 'Authorization' => $authorization])
        ->assertStatus(201);

    }


    public function test_user_logout()
    {        
        $user = User::find('1');
        $access_token = $user->createToken('authToken', ['administrador'])->accessToken;
        $authorization = 'Bearer ' . $access_token;
        
        $this->json('GET', 'api/logout', [] , ['Accept' => 'application/json', 'Authorization' => $authorization])
        ->assertStatus(200);
    }

    /*
    public function test_a_user_is_autenthicated()
        $this->assertAuthenticated($guard = null);
    {
    */
    
}