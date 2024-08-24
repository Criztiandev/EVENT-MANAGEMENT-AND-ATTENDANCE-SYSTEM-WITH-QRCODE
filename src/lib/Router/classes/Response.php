<?php
namespace lib\Router\classes;


use Exception;

class Response
{


    private static $instance = null;

    private $header = [];
    private $statusCode = 200;
    public $session = null;
    private static $config;

    private function __construct()
    {
        $this->session = Session::getInstance();
    }


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function status($code)
    {
        $this->statusCode = $code;
        return new ResponseHelper($this);
    }


    public function header($key, $value)
    {
        $this->header[$key] = $value;
        return $this;
    }


}

class ResponseHelper
{

    private $response;

    public function __construct(Response $response)
    {
        if (empty($response)) {
            throw new Exception("Parent is not initalized");
        }

        $this->response = $response;
    }

    public function json($data)
    {
        $this->response->header('Content-Type', 'application/json');
        echo json_encode($data);
        return $this->$data;
    }


    public function render($path, $props = [])
    {


        try {
            if (file_exists($path)) {
                throw new \InvalidArgumentException("View file doesnt exist");
            }

            $transformedProps = [
                ...$props,
                "error" => $this->response->session->get("error"),
                "success" => $this->response->session->get("success")
            ];

            return display($path, $transformedProps);
        } catch (Exception $e) {
            echo "ERROR: ";
            echo "<pre>";
            var_dump($e->getMessage());
            echo "</prev>";

            die();
        }
    }

    public function redirect($path, $options = [])
    {
        $session = $this->response->session;

        if (isset($options["error"])) {
            $session->flash("error", $options["error"]);
        }

        if (isset($options["success"])) {
            $session->flash("success", $options["success"]);
        }

        if (isset($options["info"])) {
            $session->flash("info", $options["info"]);
        }

        header("location: {$path}");
        die();
    }
}