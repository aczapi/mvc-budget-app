<?php

namespace App\Controllers;

use \Core\View;

/**
 * Register controller
 *
 */
class Signup extends \Core\Controller
{
  /**
   * Show the register page
   *
   * @return void
   */
  public function indexAction()
  {
    View::renderTemplate('SignUp/new-user.html');
  }
}
