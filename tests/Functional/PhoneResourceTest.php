<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Phone;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PhoneResourceTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    private string $token = '';

    public function testLogin(): string
    {
        $client = static::createClient();

        $response = static::createClient()->request('POST', '/api/login', [
            'headers' => ['content-type' => 'application/json'],
            'json' => [
            'username' => 'client1@test.fr',
            'password' => '12345678',
        ]]);
        $this->assertResponseIsSuccessful();

        $json = $response->toArray();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/api/telephones', ['auth_bearer' => '']);
        $this->assertResponseStatusCodeSame(200);

        // test authorized
        $client->request('GET', '/api/telephones', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();

//        $this->token = $content->token;

//        return $content->token;
        return $json['token'];
    }

    public function testGetCollection(): void
    {
        $client = static::createClient();

        $token = $this->testLogin();
        $response = $client->request(
            'GET',
            '/api/telephones',
            ['headers' => ['auth_bearer' => $token]]
        );
        $this->assertResponseIsSuccessful();

        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHasHeader('content-type', 'application/ld+json');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            "token" => $token ,
            "@context" => "/api/contexts/Phone",
            "@id" => "/api/telephones",
            "@type" => "hydra:Collection",
            "hydra:totalItems" => 100,
            "hydra:view" => [
                "@id" => "/api/telephones?page=1",
                "@type" => "hydra:PartialCollectionView",
                "hydra:first" => "/api/telephones?page=1",
                "hydra:last" => "/api/telephones?page=10",
                "hydra:next" => "/api/telephones?page=2"
            ]
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(100, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Phone::class);
    }
}