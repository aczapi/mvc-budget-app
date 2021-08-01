<?php

namespace App\Controllers;

use \Core\View;

/**
 * Password controller
 * 
 * 
 */

class Password extends \Core\Controller
{
  /**
   * Show th forgotten password page
   * 
   * @return void
   */
  public function forgotAction()
  {
    View::renderTemplate('Password/forgot.html');
  }
}
