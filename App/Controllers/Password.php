<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;

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
  /**
   * Send the password reset link to the supplied email
   * 
   * @return void
   */
  public function requestResetAction()
  {
    User::sendPasswordReset($_POST['email']);

    Flash::addMessage('Please check your email');
    View::renderTemplate('Password/reset_requested.html');
  }
}
