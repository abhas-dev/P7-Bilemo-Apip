<?php

use App\Factory\ClientFactory;
use App\Factory\CustomerFactory;
use App\Factory\PhoneFactory;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

Zenstruck\Foundry\Test\TestState::addGlobalState(function () {
    ClientFactory::createOne(['customers' => CustomerFactory::createMany(50)]);
    PhoneFactory::createMany(100);
});
