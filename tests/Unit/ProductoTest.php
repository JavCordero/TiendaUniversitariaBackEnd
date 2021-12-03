<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProductoTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetProductos()
    {
        $user = User::find('1');
        $access_token = $user->createToken('authToken', ['administrador'])->accessToken;
        $authorization = 'Bearer ' . $access_token;

        $response = $this->get('/api/productos', ['Accept' => 'application/json', 'Authorization' => $authorization]);

        $response->assertStatus(200);
    }

    public function testGetProductosId()
    {
        $user = User::find('1');
        $access_token = $user->createToken('authToken', ['administrador'])->accessToken;
        $authorization = 'Bearer ' . $access_token;

        $response = $this->get('/api/productos', ['Accept' => 'application/json', 'Authorization' => $authorization]);

        $response->assertStatus(200);
    }

    public function testPutProductosId()
    {
        $producto = [
            "nombre"=> "12",
            "descripcion"=> "Esta es una mochila de 20L",
            "codigo_interno"=> "555555",
            "codigo_barra"=> "555555",
            "imagen"=> "xvideos.com",
            "precio"=> 50000,
            "cantidad"=> 100,
            "stock_critico"=> 10,
            "categoria"=> "001"
        ];

        $user = User::find('1');
        $access_token = $user->createToken('authToken', ['administrador'])->accessToken;
        $authorization = 'Bearer ' . $access_token;
        $this->json('PUT', 'api/productos/001-0001',$producto, ['Accept' => 'application/json', 'Authorization' => $authorization])
            ->assertStatus(201);
    }
}
