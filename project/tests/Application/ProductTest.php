<?php

namespace Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $expectedProductData = [[
            'uuid' => '00000000-0000-0000-0000-000000000001',
            'name' => 'Product 1',
            'url' => '/product/1',
            'price' => 100,
            'visible' => true,
            ]
        ];

        self::assertSame($expectedProductData, $responseData);
    }

    public function testShowVisible()
    {
        $client = static::createClient();
        $client->request('GET', '/api/products/00000000-0000-0000-0000-000000000001');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $expectedProductData = [
            'uuid' => '00000000-0000-0000-0000-000000000001',
            'name' => 'Product 1',
            'url' => '/product/1',
            'price' => 100,
            'visible' => true,
        ];

        self::assertSame($expectedProductData, $responseData);
    }

    public function testShowNonVisible()
    {
        $client = static::createClient();
        $client->request('GET', '/api/products/00000000-0000-0000-0000-000000000002');

        $this->assertResponseStatusCodeSame(404);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $expectedProductData = [
            'error' => 'Product with uuid 00000000-0000-0000-0000-000000000002 not found',
        ];

        self::assertSame($expectedProductData, $responseData);
    }

    public function testCreteWithoutToken(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/products', [
            'name' => 'New Product',
            'url' => 'product/url',
            'price' => 400,
            'visible' => true,
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreateSuccess()
    {
        $this->markTestSkipped('Some global bug in test with login endpoint');
        $client = static::createClient([], [
            'HTTP_Authorization' => 'Bearer ' . $this->getToken(),
        ]);
        $client->request('POST', '/api/products', [
            'name' => 'New Product',
            'url' => 'product/url',
            'price' => 400,
            'visible' => true,
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame('New Product', $responseData['name']);
        $this->assertSame('product/url', $responseData['url']);
        $this->assertSame(400, $responseData['price']);
        $this->assertSame(true, $responseData['visible']);
    }

    public function testCreateFail()
    {
        $this->markTestSkipped('Some global bug in test with login endpoint');
        $client = static::createClient([], [
            'HTTP_Authorization' => 'Bearer ' . $this->getToken(),
        ]);

        $client->request('POST', '/api/products', [
            'name' => 'New Product',
            'price' => -200,
            'visible' => true,
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $expectedErrors = [
            ['field' => 'url', 'message' => 'This value should not be blank.'],
            ['field' => 'price', 'message' => 'This value should be positive.'],
        ];

        $this->assertSame('Validation failed', $responseData['message']);
        $this->assertSame($expectedErrors, $responseData['errors']);
    }

    public function testUpdateWithoutToken(): void
    {
        $client = static::createClient();
        $uuid = '00000000-0000-0000-0000-000000000001';
        $client->request('PUT', "/api/products/{$uuid}", [
            'price' => 300,
            'visible' => false,
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testUpdateSuccess()
    {
        $this->markTestSkipped('Some global bug in test with login endpoint');
        $client = static::createClient([], [
            'HTTP_Authorization' => 'Bearer ' . $this->getToken(),
        ]);
        $uuid = '00000000-0000-0000-0000-000000000001'; // replace with the uuid of the product you want to update
        $client->request('PUT', "/api/products/{$uuid}", [
            'price' => 300,
            'visible' => false,
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame('Product 1', $responseData['name']);
        $this->assertSame('/product/1', $responseData['url']);
        $this->assertSame(300, $responseData['price']);
        $this->assertSame(false, $responseData['visible']);
    }

    private function getToken(): string
    {
        $client = static::createClient();
        $client->request('POST', '/api/login', [
            'username' => 'user',
            'password' => 'password',
        ], server: ['HTTP_Content-Type' => 'application/json']);

        return json_decode($client->getResponse()->getContent(), true)['token'];
    }
}