<?php

abstract class View implements iDisplay {

    private $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    abstract public function display();

} 

?>
