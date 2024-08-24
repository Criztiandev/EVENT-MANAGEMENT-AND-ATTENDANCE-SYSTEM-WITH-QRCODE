<?php

namespace config;


class Credentials
{
    public static function getDevelopmentDSN()
    {
        return [
            'host' => $_ENV['DEVELOPMENT_HOST'],
            'username' => $_ENV['DEVELOPMENT_USERNAME'],
            'password' => $_ENV['DEVELOPMENT_PASSWORD'],
            'dbname' => $_ENV['DEVELOPMENT_DATABASE'],
        ];
    }

    public static function getProductionDSN()
    {
        return [
            'host' => $_ENV['PRODUCTION_HOST'],
            'username' => $_ENV['PRODUCTION_USERNAME'],
            'password' => $_ENV['PRODUCTION_PASSWORD'],
            'dbname' => $_ENV['PRODUCTION_DATABASE'],
        ];
    }
}