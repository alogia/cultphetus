<?php

class ActionHandler {

    private $actions = array(); 

//    public function __construct() { }

    public function register($name, $closure) {
        $this->actions[$name] = $closure;
    }

    public function unregister($name) {
        $this->actions[$name] = null;
    }

    public function call($name, $args) {
        call_user_func_array($this->actions[$name], $args);
    }

}


?>
