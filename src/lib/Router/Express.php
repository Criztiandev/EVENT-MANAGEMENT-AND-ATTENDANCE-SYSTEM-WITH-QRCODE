<?php

namespace lib\Router;

use lib\Router\classes\Response;
use lib\Router\classes\Router;
use lib\Router\classes\Session;
use Exception;

class Express
{
    protected $middleware = [];
    protected $currentMiddleware = null;
    private $config;
    private static $instance = null;

    public function __construct()
    {

        if (self::$instance !== null) {
            throw new \RuntimeException("Express instance already exists. Use Express::getInstance() instead.");
        }

        require "helpers/debugger.php";
        require "helpers/imports.php";


        $this->initConfig();
        self::$instance = $this;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            new self(); // This will set self::$instance
        }
        return self::$instance;
    }

    private function initConfig()
    {
        $configPath = BASE_PATH . "phpconfig.php";
        if (file_exists($configPath)) {
            $this->config = require $configPath;

            if (!isset($this->config["entry_point"])) {
                throw new Exception("Entry Point is not not defiend found at: " . $configPath);
            }

            define("ENTRY_POINT", $this->config["entry_point"]);
        } else {
            throw new Exception("Config file not found at: " . $configPath);
        }
    }

    public function getConfig($key = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    public static function Router()
    {
        return Router::getInstance();
    }

    public static function Session()
    {
        return Session::getInstance();
    }

    public static function Response()
    {
        return Response::getInstance();
    }

    public function use($middleware)
    {
        Router::getInstance()->use($middleware);
        return $this;
    }


    public static function Root($path, $props = [])
    {
        $fullPath = BASE_PATH . self::getInstance()->getConfig("entry_point") . "/" . $path;

        if (!file_exists($fullPath)) {
            throw new Exception("File doesnt exist, Please try again later");
        }

        extract($props);
        require $fullPath;
    }


    public static function Handler($callback)
    {
        return function ($req, $res) use ($callback) {
            return call_user_func($callback, $req, $res);
        };
    }

    public function listen($options = [])
    {
        Router::getInstance()->listen($options);
    }
}