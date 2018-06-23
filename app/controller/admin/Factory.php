<?php

namespace Controller\admin;

class Factory
{
    protected $view;
    public static function getEngine()
    {
        // return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates/');
        $templateVariables = ["var" => "所有的视图都可以用此变量"];  //此变量在所有的视图里面都可以用
        return new \Slim\Views\PhpRenderer(__DIR__ . '/../../../templates/',$templateVariables);
    }

    public function make($view,$data=[])
    {
        $this->view=static::getEngine()->fetch($view,$data);

        return $this;
    }

    public function render()
    {
        return $this->view;
    }
    
}