<?php

abstract class API
{

    protected $method = '';
    protected $endpoint = '';
    protected $verb = '';
    protected $args = array();
    protected $file = null;

    public function __construct($request) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");


        $this->args = explode('/', rtrim($request, '/'));
        $this->endpoint = array_shift($this->args);

        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
        if($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            switch ($_SERVER['HTTP_X_HTTP_METHOD']) {
            case 'DELETE' :
                $this->method = 'DELETE';
                break;
            case 'PUT' :
                $this->method = 'PUT';
                break;
            default :
                throw new Exception("Unknown Header");
            }
        }

        switch($this->method) {
        case 'DELETE' :
        case 'POST' :
            $this->request = $this->cleanInputs($_POST);
            break;
        case 'GET' :
            $this->request = $this->cleanInputs($_GET);
            break;
        case 'PUT' :
            $this->request = $this->cleanInputs($_GET);
            $this->file = file_get_contents("php://input");
            break;
        default:
            $this->_response('Invalid Method', 405);
        }
    }

    public function process() {
        if(method_exists($this, $this->endpoint)) {
            return $this->response($this->{$this->endpoint}($this->args));
        }
        return $this->response("No Endpoint: {$this->endpoint}", 404);
    }

    private function response($data, $status = 200) {
        header("HTTP/1.1 {$status} " . $this->status($status));
        return json_encode($data); // <<<<<<<<<<<<<<<<<<<<<< What is json_encoded?
    }

    private function clean($data) {
        $in = array();
        if (is_array($data)) {
            foreach($data as $k => $v) {
                $in[$k] = $this->clean($v);
            }
        } else {
            $in = trim(strip_tags($data));
        }
        return $in;
    }

    private function status($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error');
        return $status[$code]?$status[$code]:$status[500];
    }
}


?>
