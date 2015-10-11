<?php

abstract class Controller {

    private $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

}

?>
