<?php

/**
 * Router
 * 
 * PHP version 8.0.3
 */
class Router
{
  /**
   * associative array of routes - the routing table
   * @var array
   */
  protected $routes = [];

  public function add($route, $params)
  {
    $this->routes[$route] = $params;
  }

  /**
   * Get all the routes from the routing table
   * @return array
   */
  public function getRoutes()
  {
    return $this->routes;
  }
}
