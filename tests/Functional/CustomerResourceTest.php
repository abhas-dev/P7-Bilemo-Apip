<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\ClientFactory;
use App\Factory\CustomerFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CustomerResourceTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testCreateCustomer()
    {
        $client = self::createClient();

        $client->request('POST', '/api/customers', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "firstname" => "testFirstname",
                "lastname" => "testLastname",
                "adress" => "testAdress",
                "email" => "customer@test.fr",
                "phoneNumber" => "12512"
            ]
        ]);
        $this->assertResponseStatusCodeSame(401);
    }
}