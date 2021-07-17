<?php

/**
 * Front controller
 * 
 * PHP version 8.0.3
 */

/**
 * Autoloader
 */
spl_autoload_register(function ($class) {
  $root = dirname(__DIR__);   // get the parent directory
  $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
  if (is_readable($file)) {
    require $root . '/' . str_replace('\\', '/', $class) . '.php';
  }
});

/**
 * Routing
 */

$router = new Core\Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
