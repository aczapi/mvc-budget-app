<?php

/**
 * Front controller
 * 
 * PHP version 8.0.3
 */

/**
 * Routing
 */
require '../Core/Router.php';

$router = new Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);


// Display the routing table
echo '<pre>';
var_dump($router->getRoutes());
echo '</pre>';
