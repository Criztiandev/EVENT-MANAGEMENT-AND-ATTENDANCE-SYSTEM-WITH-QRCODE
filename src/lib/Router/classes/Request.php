<?php

namespace lib\Router\classes;

class Request
{
    private $params;
    private $method;
    private $header;
    private $cookies;
    private $host;
    private $agent;

    public $body;
    public $query;



    //Initialization
    public function __construct()
    {
        $this->params = [];
        $this->query = $_GET;
        $this->body = $_POST;
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->host = $_SERVER['HTTP_HOST'];
        $this->headers = $this->parseHeaders();
        $this->cookies = $_COOKIE;
        $this->agent = $_SERVER["HTTP_USER_AGENT"];
    }


    private function parseHeaders()
    {
        return [];
    }

    public function setParams()
    {
    }

    public function getParams()
    {
        echo "This is the params";
    }

}