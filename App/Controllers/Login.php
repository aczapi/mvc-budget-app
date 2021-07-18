<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 */
class Login extends \Core\Controller
{
  /**
   * Show the login page
   *
   * @return void
   */
  public function indexAction()
  {
    View::renderTemplate('Login/new.html');
  }
}
