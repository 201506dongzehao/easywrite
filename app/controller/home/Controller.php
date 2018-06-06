<?php
/**
 * Created by PhpStorm.
 * User: Fairytale
 * Date: 2018/5/3
 * Time: 16:05
 */

namespace Controller\home;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    protected $container;
    public function __construct($container)
    {
        $this->container = $container;
    }
    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }

}