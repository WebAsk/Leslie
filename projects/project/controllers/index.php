<?php

namespace project\controllers;

class index extends \framework\controllers\base {
   
    function __construct($view, $model = null){

        //$this->model = $model;
        $this->view = $view;

    }

    function index () {

        
        $this->view->template = 'index';
        $this->view->name = 'index';
    }

}